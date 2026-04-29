$(document).ready(function () {

  // ─────────────────────────────────────────────────────────────────────────
  // DATATABLE
  // ─────────────────────────────────────────────────────────────────────────
  if ($("#labourTable").length) {

    const typeBadge = {
      Internal: '<span class="badge bg-success">Internal</span>',
      External: '<span class="badge bg-secondary">External</span>',
    };

    const table = $("#labourTable").DataTable({
      processing: true,
      serverSide: false,
      ajax: { url: labourDataUrl, dataSrc: "" },
      scrollX: true,
      pageLength: 25,
      dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-2"l><"ms-auto"f>>rtip',

      columns: [
        { data: "name",            defaultContent: "-", title: "Name" },
        { data: "employment_type", defaultContent: "-", title: "Employment Type" },
        { data: "position",        defaultContent: "-", title: "Position" },
        { data: "employer",        defaultContent: "-", title: "Employer" },
        { data: "region",          defaultContent: "-", title: "Region" },
        {
          data: "rate",
          title: "Rate",
          render: (d) => `<span>$${d}</span>`,
        },
        // {
        //   data: "labour_type",
        //   title: "Type",
        //   render: (d) => typeBadge[d] ?? `<span class="badge bg-secondary">${d}</span>`,
        // },
        {
          data: "id",
          title: "Action",
          orderable: false,
          searchable: false,
          className: "text-center",
          render: (id) => `
            <a href="${labourBaseUrl}/${id}"
               class="btn btn-sm btn-secondary" title="View">
              <i class="fa fa-eye"></i>
            </a>
            <a href="${labourBaseUrl}/${id}/edit"
               class="btn btn-sm btn-success ms-1" title="Edit">
              <i class="fa fa-edit"></i>
            </a>
            <button class="btn btn-sm btn-danger ms-1 btn-delete"
                    data-id="${id}" title="Delete">
              <i class="fa fa-trash"></i>
            </button>
          `,
        },
      ],
      order: [[0, "desc"]], // newest first

      language: {
        search:            "",
        searchPlaceholder: "Search labour…",
        lengthMenu:        "Show _MENU_ entries",
        processing:        '<div class="spinner-border spinner-border-sm text-secondary" role="status"></div>',
        emptyTable:        "No labour records found.",
        zeroRecords:       "No matching records found.",
        info:              "Showing _START_ to _END_ of _TOTAL_ records",
        infoEmpty:         "No records available",
        infoFiltered:      "(filtered from _MAX_ total)",
      },

      order: [[0, "asc"]],

      initComplete: function () {
        $("#labourTable_filter input")
          .addClass("form-control")
          .css("width", "260px");
        // $("#labourTable_length select")
        //   .addClass("form-select")
        //   .css("width", "auto");
      },
    });

    // ── DELETE ───────────────────────────────────────────────────────────────
    $("#labourTable").on("click", ".btn-delete", function () {
      const id = $(this).data("id");

      if (!confirm("Are you sure you want to delete this labour record? This action cannot be undone.")) {
        return;
      }

      $.ajax({
        url:  `${labourBaseUrl}/${id}`,
        type: "DELETE",
        data: { _token: csrfToken },
        success: () => {
          table.ajax.reload(null, false);
          showToast("Labour deleted successfully.", "success");
        },
        error: () => showToast("Failed to delete labour.", "danger"),
      });
    });
  }

  // ─────────────────────────────────────────────────────────────────────────
  // FORM  (create + edit share same id)
  // ─────────────────────────────────────────────────────────────────────────
  const form = document.getElementById("labourForm");
  if (!form) return;

  // ── Name Autocomplete ────────────────────────────────────────────────────
  const $nameInput    = $("#labourName");
  const $userIdHidden = $("#labourUserId");
  const $suggestions  = $("#labourSuggestions");
  let   acTimer       = null;

  $nameInput.on("input", function () {
    const term = $(this).val().trim();

    // Clear hidden user id whenever user types manually
    $userIdHidden.val("");

    clearTimeout(acTimer);

    if (term.length < 2) {
      $suggestions.hide().empty();
      return;
    }

    acTimer = setTimeout(() => {
      $.getJSON(labourAutocompleteUrl, { term }, function (data) {
        $suggestions.empty();

        if (!data.length) {
          $suggestions.hide();
          return;
        }

        data.forEach((item) => {
          const $li = $(`
            <li class="list-group-item list-group-item-action"
                style="cursor:pointer; font-size:.875rem;">
              ${item.label}
            </li>`);

          $li.on("click", function () {
            $nameInput.val(item.value);
            $userIdHidden.val(item.id);
            $suggestions.hide().empty();

            // Trigger rate lookup
            fetchRate(item.id);

            // Mark field as touched and clear error
            touched.add("name");
            applyState("name", null);
          });

          $suggestions.append($li);
        });

        $suggestions.show();
      });
    }, 250);
  });

  // Hide suggestions on outside click
  $(document).on("click", function (e) {
    if (!$(e.target).closest("#labourName, #labourSuggestions").length) {
      $suggestions.hide();
    }
  });

  // ── Rate Auto-fill ────────────────────────────────────────────────────────
  function fetchRate(userId) {
    if (!userId || typeof labourRateUrl === "undefined") return;

    $.getJSON(labourRateUrl, { user_id: userId }, function (res) {
      if (res.rate !== null && res.rate !== undefined) {
        const $rateField = $("#employerRate");
        $rateField.val(parseFloat(res.rate).toFixed(2));
        $rateField.prop("readonly", true).addClass("bg-light");
        touched.add("employer_rate");
        applyState("employer_rate", null);
      }
    });
  }

  // Allow rate to be editable again if name is manually cleared
  $nameInput.on("change", function () {
    if (!$userIdHidden.val()) {
      $("#employerRate").prop("readonly", false).removeClass("bg-light");
    }
  });

  // ── Region "Add New" toggle ───────────────────────────────────────────────
  const $regionSelect = $("#regionSelect");
  const $regionInput  = $("#regionNameInput");

  function checkRegionToggle() {
    if (parseInt($regionSelect.val()) === newRegionId) {
      $regionInput.show().attr("required", true);
    } else {
      $regionInput.hide().removeAttr("required").val("");
    }
  }

  $regionSelect.on("change", checkRegionToggle);
  checkRegionToggle(); // run on page load (edit mode pre-selection)

  // ─────────────────────────────────────────────────────────────────────────
  // FORM VALIDATION
  // ─────────────────────────────────────────────────────────────────────────
  const RULES = {
    name:              { required: true,  label: "Name" },
    region:            { required: true,  label: "Region" },
    employment_type:   { required: true,  label: "Employment Type" },
    employer_position: { required: true,  label: "Title / Position" },
    employer_supplier: { required: true,  label: "Employer" },
    employer_rate:     { required: true,  label: "Rate", numeric: true },
  };

  const touched = new Set();

  function validate(name) {
    const rule = RULES[name];
    if (!rule) return null;

    const el = form.elements[name];
    if (!el) return null;

    const val = el.value.trim();

    if (rule.required && !val) {
      return `${rule.label} is required.`;
    }

    if (rule.numeric && val && isNaN(parseFloat(val))) {
      return `${rule.label} must be a valid number.`;
    }

    if (rule.numeric && val && parseFloat(val) < 0) {
      return `${rule.label} cannot be negative.`;
    }

    return null;
  }

  function applyState(name, error) {
    const el = form.elements[name];
    if (!el) return;

    const wrapper = el.closest(".col-md-4, .col-md-6, .col-md-8, .col-md-12");
    const fb      = wrapper ? wrapper.querySelector(".invalid-feedback") : null;

    el.classList.toggle("is-invalid", !!error);
    // el.classList.toggle("is-valid",   !error && touched.has(name));

    if (fb) fb.textContent = error || "";
  }

  function checkField(name) {
    touched.add(name);
    const error = validate(name);
    applyState(name, error);
    return !error;
  }

  Object.keys(RULES).forEach((name) => {
    const el = form.elements[name];
    if (!el) return;

    el.addEventListener("blur",   () => checkField(name));
    el.addEventListener("change", () => checkField(name));
    el.addEventListener("input",  () => { if (touched.has(name)) checkField(name); });
  });

  // ── Submit ────────────────────────────────────────────────────────────────
  form.addEventListener("submit", function (e) {

    // If "Add New" region is selected, also validate the text input
    if (parseInt($regionSelect.val()) === newRegionId) {
      const regionName = $regionInput.val().trim();
      if (!regionName) {
        $regionInput.addClass("is-invalid");
        e.preventDefault();
        $regionInput.scrollIntoView({ behavior: "smooth", block: "center" });
        return;
      } else {
        $regionInput.removeClass("is-invalid");
      }
    }

    let allValid = true;

    Object.keys(RULES).forEach((name) => {
      touched.add(name);
      const error = validate(name);
      applyState(name, error);
      if (error) allValid = false;
    });

    if (!allValid) {
      e.preventDefault();

      const firstInvalid = form.querySelector(".is-invalid");
      if (firstInvalid) {
        firstInvalid.scrollIntoView({ behavior: "smooth", block: "center" });
        firstInvalid.focus({ preventScroll: true });
      }
    }
  });

});
