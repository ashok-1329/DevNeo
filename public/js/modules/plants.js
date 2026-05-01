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
    if (fb && fb.classList.contains("invalid-feedback")) fb.textContent = "";
  }

  /* ═══════════════════════════════════════════════ */
  /* FORM VALIDATION                                */
  /* ═══════════════════════════════════════════════ */

  function initFormValidation() {
    const form = document.getElementById("plantForm");
    if (!form) return;

    form.addEventListener("submit", function (e) {
      let valid = true;

      // PROJECT
      const project = document.getElementById("projectSelect");
      if (project && !project.value) {
        setFieldError("#projectSelect", "Project is required.");
        valid = false;
      } else clearFieldError("#projectSelect");

      // PLANT NAME
      const plantName = document.getElementById("plantNameInput");
      if (plantName && !plantName.value.trim()) {
        setFieldError("#plantNameInput", "Plant name is required.");
        valid = false;
      } else clearFieldError("#plantNameInput");

      // PLANT CODE
      const plantCode = document.getElementById("plantCodeInput");
      if (plantCode && !plantCode.value.trim()) {
        setFieldError("#plantCodeInput", "Plant code is required.");
        valid = false;
      } else clearFieldError("#plantCodeInput");

      // SUPPLIER
      const supplier = document.getElementById("supplierSelect");
      if (supplier && !supplier.value) {
        setFieldError("#supplierSelect", "Supplier is required.");
        valid = false;
      } else clearFieldError("#supplierSelect");

      // UNIT
      const unit = document.getElementById("unitInput");
      if (unit && !unit.value.trim()) {
        setFieldError("#unitInput", "Unit is required.");
        valid = false;
      } else clearFieldError("#unitInput");

      // RATE
      const rate = document.getElementById("rateInput");
      if (rate) {
        const val = parseFloat(rate.value);
        if (!rate.value.trim() || isNaN(val) || val < 0) {
          setFieldError("#rateInput", "Enter valid rate.");
          valid = false;
        } else clearFieldError("#rateInput");
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
        if (firstInvalid) {
          firstInvalid.scrollIntoView({
            behavior: "smooth",
            block: "center",
          });
        }
      }
    });
  }

  /* ═══════════════════════════════════════════════ */
  /* DELETE (SHOW PAGE)                             */
  /* ═══════════════════════════════════════════════ */

  function initShowPageDelete() {
    const btn = document.getElementById("btnDeletePlant");
    if (!btn) return;

    btn.addEventListener("click", function () {
      const id = this.dataset.id;

      if (!confirm("Delete this plant?")) return;

      fetch(`${plantBaseUrl}/${id}`, {
        method: "DELETE",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((r) => r.json())
        .then((data) => {
          if (data.message) {
            window.location.href = plantBaseUrl;
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
    const tableEl = document.getElementById("plantTableProject");
    if (!tableEl || typeof $.fn.DataTable === "undefined") return;

    const table = $("#plantTableProject").DataTable({
      processing: true,
      destroy: true,
      ajax: { url: plantDataUrl, dataSrc: "" },

      columns: [
        { data: "plant_code" },
        { data: "plant_type" },
        { data: "plant_capacity" },
        { data: "supplier" },
        { data: "plant_name" },
        { data: "unit" },
        { data: "rate" },

        {
          data: "is_docket",
          render: (d) =>
            d === "Yes"
              ? '<span class="badge bg-success">Yes</span>'
              : '<span class="badge bg-secondary">No</span>',
        },

        // {
        //   data: "id",
        //   orderable: false,
        //   className: "text-center",
        //   render: (id, type, row) => `
        //     <div class="form-check d-flex justify-content-center">
        //       <input type="checkbox" class="form-check-input resource-checkbox" data-id="${id}" data-type="plant">
        //     </div>
        //   `,
        // },

        {
          data: "id",
          orderable: false,
          className: "text-center",
          render: (id) => `
            <a href="${plantBaseUrl}/${id}" class="btn btn-sm btn-secondary">
              <i class="fa fa-eye"></i>
            </a>
            <a href="${plantBaseUrl}/${id}/edit" class="btn btn-sm btn-success ms-1">
              <i class="fa fa-edit"></i>
            </a>
            <button class="btn btn-sm btn-danger ms-1 btn-delete" data-id="${id}">
              <i class="fa fa-trash"></i>
            </button>
          `,
        },
      ],

      order: [[0, "desc"]],
    });

    /* DELETE */
    $("#plantTableProject").on("click", ".btn-delete", function () {
      const id = this.dataset.id;
      if (!confirm("Delete this plant?")) return;

      fetch(`${plantBaseUrl}/${id}`, {
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

    /* ASSIGN */
    $("#plantTableProject").on("change", ".resource-checkbox", function () {
      const id = this.dataset.id;
      const checked = this.checked;

      fetch(`${plantBaseUrl}/${id}/assign`, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          assigned: checked ? 1 : 0,
        }),
      });
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
