/**
 * subcontractors.js
 * COMPLETE FIXED VERSION
 */

$(document).ready(function () {
  /* ───────────────────────────────────────────── */
  /* INIT TABLE (INDEX PAGE)                       */
  /* ───────────────────────────────────────────── */
  if ($("#diarySubcontractorsTable").length) {
    _initTable();
  }

  /* ───────────────────────────────────────────── */
  /* INIT FORM (CREATE / EDIT)                    */
  /* ───────────────────────────────────────────── */
  if (document.getElementById("dsForm")) {
    _initForm();
  }
});

/* ═══════════════════════════════════════════════════════════════ */
/*  1. DATATABLE                                                  */
/* ═══════════════════════════════════════════════════════════════ */

function _initTable() {
  const statusBadge = {
    1: '<span class="badge bg-success">Active</span>',
    0: '<span class="badge bg-secondary">Inactive</span>',
  };

  const docketBadge = {
    Yes: '<span class="badge bg-success px-2">Yes</span>',
    No: '<span class="badge bg-secondary px-2">No</span>',
  };

  const table = $("#diarySubcontractorsTable").DataTable({
    processing: true,
    ajax: { url: dsDataUrl, dataSrc: "" },
    scrollX: true,
    dom: '<"d-flex justify-content-between align-items-center mb-2"lf>rtip',
    order: [[0, "asc"]],
    columns: [
      { data: "business_name", defaultContent: "-" },
      { data: "rep_name", defaultContent: "-" },
      { data: "subcontractor_asset_id", defaultContent: "-" },
      { data: "work_type_name", defaultContent: "-" },
      {
        data: "is_docket",
        orderable: false,
        render: (d) => docketBadge[d] ?? docketBadge["No"],
      },
      {
        data: "status",
        render: (d) => statusBadge[d] ?? statusBadge[0],
      },
      {
        data: "id",
        orderable: false,
        className: "text-center",
        render: (id) => `
                    <a href="${dsBaseUrl}/${id}" class="btn btn-sm btn-secondary">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="${dsBaseUrl}/${id}/edit" class="btn btn-sm btn-success ms-1">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button class="btn btn-sm btn-danger ms-1 btn-delete" data-id="${id}">
                        <i class="fa fa-trash"></i>
                    </button>
                `,
      },
    ],
  });

  /* SEARCH */
  $("#globalSearch").on("input", function () {
    table.search(this.value).draw();
  });

  /* FILTERS */
  $("#filterDocket").on("change", function () {
    table.column(4).search(this.value).draw();
  });

  $("#filterStatus").on("change", function () {
    table.column(5).search(this.value).draw();
  });

  $("#btnClearFilter").on("click", function () {
    $("#globalSearch").val("");
    $("#filterDocket, #filterStatus").val("");
    table.search("").columns().search("").draw();
  });

  /* DELETE */
  $("#diarySubcontractorsTable").on("click", ".btn-delete", function () {
    const id = $(this).data("id");

    if (!confirm("Delete this record?")) return;

    $.ajax({
      url: `${dsBaseUrl}/${id}`,
      type: "DELETE",
      data: { _token: csrfToken },
      success: function () {
        table.ajax.reload(null, false);
        _showToast("Deleted successfully");
      },
      error: () => _showToast("Delete failed", "danger"),
    });
  });
}

/* ═══════════════════════════════════════════════════════════════ */
/*  2. FORM HANDLING                                              */
/* ═══════════════════════════════════════════════════════════════ */

function _initForm() {
  const form = document.getElementById("dsForm");

  const businessSelect = document.getElementById("businessNameSelect");
  const businessOtherWrap = document.getElementById("businessNameOtherWrap");
  const businessOtherInput = document.getElementById("businessNameOther");

  const workSelect = document.getElementById("typeOfWorkSelect");
  const workOtherWrap = document.getElementById("typeOfWorkOtherWrap");
  const workOtherInput = document.getElementById("typeOfWorkOther");

  const repName = document.getElementById("repName");

  /* ───────────────────────────────────────────── */
  /* OTHER FIELD TOGGLE                            */
  /* ───────────────────────────────────────────── */

  function toggleBusinessOther() {
    const selected = businessSelect.options[businessSelect.selectedIndex];
    const isOther = selected?.dataset.other === "1";

    businessOtherWrap.classList.toggle("d-none", !isOther);

    if (!isOther) {
      businessOtherInput.value = "";
    }

    if (selected?.dataset.rep) {
      repName.value = selected.dataset.rep;
    }
  }

  function toggleWorkOther() {
    const selected = workSelect.options[workSelect.selectedIndex];
    const isOther = selected?.dataset.other === "1";

    workOtherWrap.classList.toggle("d-none", !isOther);

    if (!isOther) {
      workOtherInput.value = "";
    }
  }

  businessSelect.addEventListener("change", toggleBusinessOther);
  workSelect.addEventListener("change", toggleWorkOther);

  toggleBusinessOther();
  toggleWorkOther();

  /* ───────────────────────────────────────────── */
  /* VALIDATION                                   */
  /* ───────────────────────────────────────────── */

  function error(el, msg) {
    el.classList.add("is-invalid");
    const fb = el.closest(".col-md-6")?.querySelector(".invalid-feedback");
    if (fb) fb.textContent = msg;
  }

  function clear(el) {
    el.classList.remove("is-invalid");
  }

  function validate() {
    let valid = true;

    if (!businessSelect.value) {
      error(businessSelect, "Business Name required");
      valid = false;
    } else clear(businessSelect);

    if (!repName.value.trim()) {
      error(repName, "Representative Name required");
      valid = false;
    } else clear(repName);

    if (!workSelect.value) {
      error(workSelect, "Type of Work required");
      valid = false;
    } else clear(workSelect);

    if (
      !businessOtherWrap.classList.contains("d-none") &&
      !businessOtherInput.value.trim()
    ) {
      error(businessOtherInput, "Specify business");
      valid = false;
    }

    if (
      !workOtherWrap.classList.contains("d-none") &&
      !workOtherInput.value.trim()
    ) {
      error(workOtherInput, "Specify work type");
      valid = false;
    }

    const docket = form.querySelector('input[name="is_docket"]:checked');
    const fb = document.getElementById("docketFeedback");
    const row = form.querySelector(".ds-docket-row");

    if (!docket) {
      fb.textContent = "Select Yes/No";
      row.classList.add("ds-docket-error");
      valid = false;
    } else {
      fb.textContent = "";
      row.classList.remove("ds-docket-error");
    }

    return valid;
  }

  /* SUBMIT */
  form.addEventListener("submit", function (e) {
    if (!validate()) {
      e.preventDefault();

      const first = form.querySelector(".is-invalid, .ds-docket-error");
      if (first) {
        first.scrollIntoView({ behavior: "smooth", block: "center" });
      }
    }
  });
}
