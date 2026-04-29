(function () {
  "use strict";

  function setFieldError(selector, message) {
    const el = document.querySelector(selector);
    if (!el) return;

    el.classList.add("is-invalid");

    let fb = el.nextElementSibling;
    if (!fb || !fb.classList.contains("invalid-feedback")) {
      fb = document.createElement("div");
      fb.className = "invalid-feedback";
      el.insertAdjacentElement("afterend", fb);
    }

    fb.textContent = message;
  }

  function clearFieldError(selector) {
    const el = document.querySelector(selector);
    if (!el) return;

    el.classList.remove("is-invalid");

    const fb = el.nextElementSibling;
    if (fb && fb.classList.contains("invalid-feedback")) {
      fb.textContent = "";
    }
  }

  function initFormValidation() {
    const form = document.getElementById("docketForm");
    if (!form) return;

    form.addEventListener("submit", function (e) {
      let valid = true;

      /* Docket Number */
      const docketNumber = document.getElementById("docketNumber");
      if (!docketNumber.value.trim()) {
        setFieldError("#docketNumber", "Docket number is required.");
        valid = false;
      } else {
        clearFieldError("#docketNumber");
      }

      /* Docket Date */
      const docketDate = document.getElementById("docketDate");
      if (!docketDate.value) {
        setFieldError("#docketDate", "Docket date is required.");
        valid = false;
      } else {
        clearFieldError("#docketDate");
      }

      /* Supplier */
      const supplier = document.getElementById("supplierSelect");
      if (!supplier.value) {
        setFieldError("#supplierSelect", "Supplier is required.");
        valid = false;
      } else {
        clearFieldError("#supplierSelect");
      }

      /* Job Code */
      const jobCode = document.getElementById("jobCode");
      if (!jobCode.value.trim()) {
        setFieldError("#jobCode", "Cost code is required.");
        valid = false;
      } else {
        clearFieldError("#jobCode");
      }

      /* Category */
      const category = document.getElementById("categorySelect");
      if (!category.value) {
        setFieldError("#categorySelect", "Category is required.");
        valid = false;
      } else {
        clearFieldError("#categorySelect");
      }

      /* Submitted Date */
      const submittedDate = document.getElementById("submittedDate");
      if (!submittedDate.value) {
        setFieldError("#submittedDate", "Submitted date is required.");
        valid = false;
      } else {
        clearFieldError("#submittedDate");
      }

      /* File Validation */
      const file = document.getElementById("docketFile");
      if (file && file.files.length > 0) {
        const allowed = ["application/pdf", "image/jpeg", "image/png"];
        if (!allowed.includes(file.files[0].type)) {
          setFieldError("#docketFile", "Only PDF/JPG/PNG allowed.");
          valid = false;
        } else {
          clearFieldError("#docketFile");
        }
      }

      if (!valid) {
        e.preventDefault();

        const firstInvalid = form.querySelector(".is-invalid");
        if (firstInvalid) {
          firstInvalid.scrollIntoView({
            behavior: "smooth",
            block: "center",
          });
        }
      }
    });

    [
      "docketNumber",
      "docketDate",
      "supplierSelect",
      "jobCode",
      "categorySelect",
      "submittedDate",
    ].forEach((id) => {
      const el = document.getElementById(id);
      if (!el) return;

      el.addEventListener("input", () => clearFieldError(`#${id}`));
      el.addEventListener("change", () => clearFieldError(`#${id}`));
    });
  }

  function initShowDelete() {
    const btn = document.getElementById("btnDeleteDocket");
    if (!btn) return;

    btn.addEventListener("click", function () {
      const id = this.dataset.id;

      if (!confirm("Delete this docket?")) return;

      fetch(`${docketBaseUrl}/${id}`, {
        method: "DELETE",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((r) => r.json())
        .then(() => {
          window.location.href = docketBaseUrl;
        })
        .catch(() => alert("Error deleting record"));
    });
  }

  function initTable() {
    if (!window.$ || !$.fn.DataTable) return;

    const table = $("#docketsTable").DataTable({
      processing: true,
      ajax: { url: docketDataUrl, dataSrc: "" },

      columns: [
        { data: "id" },
        { data: "docket_number" },
        { data: "supplier" },
        { data: "job_code" },
        { data: "category" },
        {
          data: "date",
          render: (d) => formatDate(d),
        },
        {
          data: "status",
          render: (d) =>
            d == 1
              ? '<span class="badge bg-success">Active</span>'
              : '<span class="badge bg-secondary">Inactive</span>',
        },
        {
          data: "id",
          orderable: false,
          render: (id) => `
            <a href="${docketBaseUrl}/${id}" class="btn btn-sm btn-secondary">
              <i class="fa fa-eye"></i>
            </a>
            <a href="${docketBaseUrl}/${id}/edit" class="btn btn-sm btn-success ms-1">
              <i class="fa fa-pencil"></i>
            </a>
            <button class="btn btn-sm btn-danger ms-1 btn-delete" data-id="${id}">
              <i class="fa fa-trash"></i>
            </button>
          `,
        },
      ],
      order: [[0, "desc"]], // newest first
    });

    /* DELETE */
    $("#docketsTable").on("click", ".btn-delete", function () {
      const id = this.dataset.id;

      if (!confirm("Delete this docket?")) return;

      fetch(`${docketBaseUrl}/${id}`, {
        method: "DELETE",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then(() => {
          table.ajax.reload(null, false);
        })
        .catch(() => alert("Error deleting record"));
    });

    /* EXPORT CSV */
    const exportBtn = document.getElementById("btnExport");
    if (exportBtn) {
      exportBtn.addEventListener("click", function () {
        const rows = table.rows({ search: "applied" }).data().toArray();

        const headers = [
          "ID",
          "Docket Number",
          "Supplier",
          "Job Code",
          "Category",
          "Date",
        ];

        const csv = [
          headers.join(","),
          ...rows.map((r) =>
            [
              r.id,
              `"${r.docket_number}"`,
              `"${r.supplier}"`,
              `"${r.job_code}"`,
              `"${r.category}"`,
              `"${r.date}"`,
            ].join(","),
          ),
        ].join("\n");

        const blob = new Blob([csv], { type: "text/csv" });
        const url = URL.createObjectURL(blob);

        const a = document.createElement("a");
        a.href = url;
        a.download = "dockets.csv";
        a.click();

        URL.revokeObjectURL(url);
      });
    }
  }

  document.addEventListener("DOMContentLoaded", function () {
    initFormValidation();
    initShowDelete();
    initTable();
  });
})();
