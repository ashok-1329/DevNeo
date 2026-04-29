$(document).ready(function () {
  if ($("#clientsTable").length) {
    const statusBadge = {
      1: '<span class="badge bg-success">Active</span>',
      0: '<span class="badge bg-secondary">Inactive</span>',
      2: '<span class="badge bg-danger">Blocked</span>',
    };

    const table = $("#clientsTable").DataTable({
      processing: true,
      serverSide: false,
      ajax: {
        url: clientsDataUrl,
        dataSrc: "",
      },
      scrollX: true,
      pageLength: 25,
      dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-2"l><"ms-auto"f>>rtip',

      columns: [
        { data: "client_name", defaultContent: "-", title: "Client Name" },
        { data: "client_abn", defaultContent: "-", title: "ABN" },
        { data: "client_phone", defaultContent: "-", title: "Phone" },
        {
          data: "client_representative",
          defaultContent: "-",
          title: "Representative",
        },
        { data: "client_rep_email", defaultContent: "-", title: "Rep Email" },
        {
          data: "status",
          title: "Status",
          render: (d) => statusBadge[d] ?? statusBadge[0],
        },
        {
          data: "id",
          title: "Action",
          orderable: false,
          searchable: false,
          className: "text-center",
          render: (id) => `
            <a href="${clientBaseUrl}/${id}"
               class="btn btn-sm btn-secondary" title="View">
              <i class="fa fa-eye"></i>
            </a>
            <a href="${clientBaseUrl}/${id}/edit"
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
        searchPlaceholder: "Search clients…",
        lengthMenu: "Show _MENU_ entries",
        processing:
          '<div class="spinner-border spinner-border-sm text-secondary" role="status"></div>',
        emptyTable: "No clients found.",
        zeroRecords: "No matching clients found.",
        info: "Showing _START_ to _END_ of _TOTAL_ clients",
        infoEmpty: "No clients available",
        infoFiltered: "(filtered from _MAX_ total)",
      },

      order: [[0, "asc"]],

      initComplete: function () {
        // Style the search input to match form-control-sm
        $("#clientsTable_filter input")
          .addClass("form-control")
          .css("width", "260px");
        // $("#clientsTable_length select")
        //   .addClass("form-select")
        //   .css("width", "auto");
      },
    });

    $("#clientsTable").on("click", ".btn-delete", function () {
      const id = $(this).data("id");

      if (
        !confirm(
          "Are you sure you want to delete this client? This action cannot be undone.",
        )
      ) {
        return;
      }

      $.ajax({
        url: `${clientBaseUrl}/${id}`,
        type: "DELETE",
        data: { _token: csrfToken },
        success: () => {
          table.ajax.reload(null, false);
          showToast("Client deleted successfully.", "success");
        },
        error: () => showToast("Failed to delete client.", "danger"),
      });
    });
  }

  // ─────────────────────────────────────────────────────────────────────────
  // QUILL NOTES EDITOR
  // ─────────────────────────────────────────────────────────────────────────
  let quill = null;

  if (document.getElementById("clientEditor")) {
    quill = new Quill("#clientEditor", { theme: "snow" });

    const hidden = document.getElementById("clientNotesHidden");

    // Pre-fill with existing / old value
    if (hidden && hidden.value.trim()) {
      quill.root.innerHTML = hidden.value;
    }

    // Keep hidden input in sync as user types
    quill.on("text-change", function () {
      hidden.value = quill.getText().trim() === "" ? "" : quill.root.innerHTML;
    });
  }

  // ─────────────────────────────────────────────────────────────────────────
  // FORM VALIDATION
  // ─────────────────────────────────────────────────────────────────────────
  const form = document.getElementById("clientForm");
  if (!form) return;

  function isEditMode() {
    return !!form.querySelector('input[name="_method"]');
  }

  const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  // Rules: required = must have value; email = valid email format; numeric = digits only
  const RULES = {
    client_name: { required: true, label: "Client Name" },
    client_abn: { required: true, label: "ABN", numeric: true },
    client_phone: { required: true, label: "Phone", numeric: true },
    client_representative: { required: true, label: "Representative" },
    client_rep_email: {
      required: true,
      label: "Representative Email",
      email: true,
    },
    client_account_email: {
      required: false,
      label: "Account Email",
      email: true,
    },
    client_terms: { required: false, label: "Terms" },
    client_address: { required: true, label: "Address" },
    client_logo: {
      required: !isEditMode(),
      label: "Client Logo",
      isFile: true,
    },
  };

  const touched = new Set();

  // ── Restrict ABN field to digits only ────────────────────────────────────
  const abnField = form.elements["client_abn"];
  if (abnField) {
    abnField.addEventListener("keypress", function (e) {
      if (
        !/[0-9]/.test(e.key) &&
        !["Backspace", "Delete", "Tab", "ArrowLeft", "ArrowRight"].includes(
          e.key,
        )
      ) {
        e.preventDefault();
      }
    });
    abnField.addEventListener("paste", function (e) {
      const pasted = (e.clipboardData || window.clipboardData).getData("text");
      if (!/^[0-9]+$/.test(pasted)) e.preventDefault();
    });
  }

  // ── Validate a single field ───────────────────────────────────────────────
  function validate(name) {
    const rule = RULES[name];
    if (!rule) return null;

    const el = form.elements[name];
    if (!el) return null;

    const isFile = rule.isFile;
    const rawVal = isFile ? null : el.value.trim();
    const hasVal = isFile ? el.files && el.files.length > 0 : rawVal !== "";

    if (rule.required && !hasVal) {
      return `${rule.label} is required.`;
    }

    if (rule.email && rawVal && !EMAIL.test(rawVal)) {
      return "Enter a valid email address.";
    }

    if (rule.numeric && rawVal && !/^[0-9]+$/.test(rawVal)) {
      return `${rule.label} must contain digits only.`;
    }

    return null;
  }

  // ── Apply valid / invalid state ──────────────────────────────────────────
  function applyState(name, error) {
    const el = form.elements[name];
    if (!el) return;

    // Find the closest column wrapper
    const wrapper = el.closest(
      ".col-md-4, .col-md-8, .col-md-12, .upload-zone-wrapper",
    );
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
    if (quill) {
      const hidden = document.getElementById("clientNotesHidden");
      hidden.value = quill.getText().trim() === "" ? "" : quill.root.innerHTML;
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

// function showToast(msg, type = "success") {
//   const id = "toast_" + Date.now();

//   const html = `
//     <div id="${id}"
//          class="toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3"
//          role="alert" style="z-index:9999">
//       <div class="d-flex">
//         <div class="toast-body">${msg}</div>
//         <button type="button" class="btn-close btn-close-white me-2 m-auto"
//                 data-bs-dismiss="toast"></button>
//       </div>
//     </div>`;

//   $("body").append(html);
//   const el = document.getElementById(id);
//   new bootstrap.Toast(el, { delay: 3000 }).show();
//   el.addEventListener("hidden.bs.toast", () => el.remove());
// }
