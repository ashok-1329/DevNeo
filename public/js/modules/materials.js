(function () {
  "use strict";

  /* ═══════════════════════════════════════════════ */
  /* FIELD ERROR HELPERS                            */
  /* ═══════════════════════════════════════════════ */

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
    if (fb && fb.classList.contains("invalid-feedback")) fb.textContent = "";
  }

  /* ═══════════════════════════════════════════════ */
  /* FORM VALIDATION                                */
  /* ═══════════════════════════════════════════════ */

  function initFormValidation() {
    const form = document.getElementById("matForm");
    if (!form) return;

    form.addEventListener("submit", function (e) {
      let valid = true;

      // PROJECT
      const project = document.getElementById("projectSelect");
      if (project && !project.value) {
        setFieldError("#projectSelect", "Project is required.");
        valid = false;
      } else {
        clearFieldError("#projectSelect");
      }

      // CATEGORY
      const category = document.getElementById("categorySelect");
      if (category && !category.value) {
        setFieldError("#categorySelect", "Category is required.");
        valid = false;
      } else {
        clearFieldError("#categorySelect");
      }

      // ITEM
      const item = document.getElementById("itemInput");
      if (item && !item.value.trim()) {
        setFieldError("#itemInput", "Item is required.");
        valid = false;
      } else {
        clearFieldError("#itemInput");
      }

      // SUPPLIER
      const supplier = document.getElementById("supplierSelect");
      if (supplier && !supplier.value) {
        setFieldError("#supplierSelect", "Supplier is required.");
        valid = false;
      } else {
        clearFieldError("#supplierSelect");
      }

      // UNIT
      const unit = document.getElementById("unitSelect");
      if (unit && !unit.value) {
        setFieldError("#unitSelect", "Unit is required.");
        valid = false;
      } else {
        clearFieldError("#unitSelect");
      }

      // RATE
      const rate = document.getElementById("rateInput");
      if (rate) {
        const val = parseFloat(rate.value);
        if (!rate.value.trim() || isNaN(val) || val < 0) {
          setFieldError("#rateInput", "Enter valid rate.");
          valid = false;
        } else {
          clearFieldError("#rateInput");
        }
      }

      // IS DOCKET
      const docket = form.querySelector('input[name="is_docket"]:checked');
      const docketFeedback = document.getElementById("docketFeedback");

      if (!docket) {
        if (docketFeedback)
          docketFeedback.textContent = "Select docket option.";
        valid = false;
      } else {
        if (docketFeedback) docketFeedback.textContent = "";
      }

      // ADD TO DIARY
      const diary = form.querySelector('input[name="add_to_diary"]:checked');
      const diaryFeedback = document.getElementById("diaryFeedback");

      if (!diary) {
        if (diaryFeedback) diaryFeedback.textContent = "Select diary option.";
        valid = false;
      } else {
        if (diaryFeedback) diaryFeedback.textContent = "";
      }

      if (!valid) {
        e.preventDefault();
        const firstInvalid = form.querySelector(".is-invalid");
        if (firstInvalid)
          firstInvalid.scrollIntoView({
            behavior: "smooth",
            block: "center",
          });
      }
    });
  }

  /* ═══════════════════════════════════════════════ */
  /* DELETE (SHOW PAGE)                             */
  /* ═══════════════════════════════════════════════ */

  function initShowPageDelete() {
    const btn = document.getElementById("btnDeleteMaterial");
    if (!btn) return;

    btn.addEventListener("click", function () {
      const id = this.dataset.id;

      if (!confirm("Delete this material?")) return;

      fetch(`${matBaseUrl}/${id}`, {
        method: "DELETE",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((r) => r.json())
        .then((data) => {
          if (data.message) {
            window.location.href = matBaseUrl;
          } else {
            showToast("Delete failed.", "error");
          }
        })
        .catch(() => showToast("Error occurred.", "error"));
    });
  }

  /* ═══════════════════════════════════════════════ */
  /* DATATABLE                                     */
  /* ═══════════════════════════════════════════════ */

  function initDataTable() {
    const tableEl = document.getElementById("materialsTable");
    if (!tableEl || typeof $.fn.DataTable === "undefined") return;

    const table = $("#materialsTable").DataTable({
      processing: true,
      ajax: { url: matDataUrl, dataSrc: "" },

      columns: [
        { data: "id" },
        { data: "category_name" },
        { data: "item" },
        { data: "supplier_name" },
        { data: "unit_name" },
        { data: "rate" },
        {
          data: "is_docket",
          render: (d) =>
            d === "Yes"
              ? '<span class="badge bg-success">Yes</span>'
              : '<span class="badge bg-secondary">No</span>',
        },
        {
          data: "add_to_diary",
          render: (d) =>
            d === "Yes"
              ? '<span class="badge bg-success">Yes</span>'
              : '<span class="badge bg-secondary">No</span>',
        },
        {
          data: "id",
          orderable: false,
          render: (id) => `
            <a href="${matBaseUrl}/${id}" class="btn btn-sm btn-secondary">
              <i class="fa fa-eye"></i>
            </a>
            <a href="${matBaseUrl}/${id}/edit" class="btn btn-sm btn-success ms-1">
              <i class="fa fa-pencil"></i>
            </a>
            <button class="btn btn-sm btn-danger ms-1 btn-delete" data-id="${id}">
              <i class="fa fa-trash"></i>
            </button>
          `,
        },
      ],
    });

    /* DELETE */
    $("#materialsTable").on("click", ".btn-delete", function () {
      const id = this.dataset.id;
      if (!confirm("Delete this material?")) return;

      fetch(`${matBaseUrl}/${id}`, {
        method: "DELETE",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((r) => r.json())
        .then(() => {
          showToast("Deleted successfully", "success");
          table.ajax.reload(null, false);
        })
        .catch(() => showToast("Error occurred", "error"));
    });
  }

  /* ═══════════════════════════════════════════════ */
  /* INIT                                           */
  /* ═══════════════════════════════════════════════ */

  document.addEventListener("DOMContentLoaded", function () {
    initFormValidation();
    initShowPageDelete();
    initDataTable();
  });
})();
