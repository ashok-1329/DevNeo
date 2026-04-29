$(document).ready(function () {
  if ($("#diaryProductsTable").length) {
    const statusToggle = (id, status) => `
      <div class="form-check form-switch d-flex justify-content-center mb-0">
        <input class="form-check-input status-toggle custom-switch" 
            type="checkbox" role="switch"
            data-id="${id}" ${status == 1 ? "checked" : ""}
            style="cursor:pointer; width:2.2em; height:1.15em;">
      </div>`;

    const table = $("#diaryProductsTable").DataTable({
      processing: true,
      serverSide: false,
      ajax: {
        url: diaryProductsDataUrl,
        dataSrc: "",
      },
      scrollX: true,
      pageLength: 25,
      dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-2"l><"ms-auto"f>>rtip',

      columns: [
        { data: "name", defaultContent: "-", title: "Product Name" },
        { data: "category_name", defaultContent: "-", title: "Category" },
        {
          data: "status",
          title: "Status",
          orderable: true,
          searchable: false,
          render: (d, type, row) => {
            if (type === "sort" || type === "type") return d;
            return statusToggle(row.id, d);
          },
        },
        {
          data: "created_at",
          title: "Created",
          defaultContent: "-",
          render: (d) => formatDate(d),
        },
        {
          data: "id",
          title: "Action",
          orderable: false,
          searchable: false,
          className: "text-center",
          render: (id) => `
            <a href="${diaryProductBaseUrl}/${id}"
               class="btn btn-sm btn-secondary" title="View">
              <i class="fa fa-eye"></i>
            </a>
            <a href="${diaryProductBaseUrl}/${id}/edit"
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
        search: "",
        searchPlaceholder: "Search products…",
        lengthMenu: "Show _MENU_ entries",
        processing:
          '<div class="spinner-border spinner-border-sm text-secondary" role="status"></div>',
        emptyTable: "No products found.",
        zeroRecords: "No matching products found.",
        info: "Showing _START_ to _END_ of _TOTAL_ products",
        infoEmpty: "No products available",
        infoFiltered: "(filtered from _MAX_ total)",
      },

      order: [[0, "asc"]],

      initComplete: function () {
        $("#diaryProductsTable_filter input")
          .addClass("form-control")
          .css("width", "260px");
        // $("#diaryProductsTable_length select")
        //   .addClass("form-select")
        //   .css("width", "auto");
      },
    });

    $("#diaryProductsTable").on("change", ".status-toggle", function () {
      const id = $(this).data("id");
      const $toggle = $(this);

      // Optimistic UI — revert on failure
      $.ajax({
        url: `${diaryProductToggleUrl}/${id}/toggle-status`,
        type: "PATCH",
        data: { _token: csrfToken },
        success: (res) => {
          showToast(
            `Product ${res.status == 1 ? "activated" : "deactivated"} successfully.`,
            res.status == 1 ? "success" : "secondary",
          );
        },
        error: () => {
          // Revert toggle on failure
          $toggle.prop("checked", !$toggle.prop("checked"));
          showToast("Failed to update status.", "danger");
        },
      });
    });

    $("#diaryProductsTable").on("click", ".btn-delete", function () {
      const id = $(this).data("id");

      if (
        !confirm(
          "Are you sure you want to delete this product? This action cannot be undone.",
        )
      ) {
        return;
      }

      $.ajax({
        url: `${diaryProductBaseUrl}/${id}`,
        type: "DELETE",
        data: { _token: csrfToken },
        success: () => {
          table.ajax.reload(null, false);
          showToast("Product deleted successfully.", "success");
        },
        error: () => showToast("Failed to delete product.", "danger"),
      });
    });
  }

  const form = document.getElementById("diaryProductForm");
  if (!form) return;

  const RULES = {
    name: { required: true, label: "Product Name" },
    category_id: { required: true, label: "Category" },
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

    return null;
  }

  function applyState(name, error) {
    const el = form.elements[name];
    if (!el) return;

    const wrapper = el.closest(".col-md-6, .col-md-4, .col-md-12");
    const fb = wrapper ? wrapper.querySelector(".invalid-feedback") : null;

    el.classList.toggle("is-invalid", !!error);
    // el.classList.toggle("is-valid", !error && touched.has(name));

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

    el.addEventListener("blur", () => checkField(name));
    el.addEventListener("change", () => checkField(name));
    el.addEventListener("input", () => {
      if (touched.has(name)) checkField(name);
    });
  });

  form.addEventListener("submit", function (e) {
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
