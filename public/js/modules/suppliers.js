$(document).ready(function () {
  if (!$("#suppliersTable").length) return;

  const rankClass = { 1: "rank-1", 2: "rank-2", 3: "rank-3" };

  const table = $("#suppliersTable").DataTable({
    processing: true,
    ajax: { url: suppliersDataUrl, dataSrc: "" },
    scrollX: true,
    dom: '<"d-flex justify-content-between align-items-center mb-2"lf>rtip',
    order: [[0, "desc"]], // newest first
    columns: [
      {
        data: "category",
        render: (d) => (d ? d.name : "-"),
      },
      { data: "supplier_name", defaultContent: "-" },
      { data: "supplier_email", defaultContent: "-" },
      { data: "supplier_phone", defaultContent: "-" },
      { data: "supplier_abn", defaultContent: "-" },
      { data: "supplier_bank_email", defaultContent: "-" },
      {
        data: null,
        render: (d) => {
          const term = d.paymentTerm || d.payment_term || null;
          if (!term) return "-";
          return d.payment_term_days != null
            ? `${term.name}`
            : term.name;
        },
      },
      {
        data: "supplier_address",
        render: (d) => `<span class="notes-cell">${d || "-"}</span>`,
      },
      {
        data: "supplier_notes",
        render: (d) => {
          if (!d) return '<span class="notes-cell">-</span>';
          const stripped = d.replace(/<[^>]*>/g, "").trim();
          return `<span class="notes-cell">${stripped || "-"}</span>`;
        },
      },
      {
        data: null,
        orderable: false,
        render: function (data) {
          const rank = data.supplier_rank;
          const cls = rank ? rankClass[rank] : "rank-null";
          const opts = [
            `<option value="">Please select</option>`,
            `<option value="1" ${rank == 1 ? "selected" : ""}>Do Not Use</option>`,
            `<option value="2" ${rank == 2 ? "selected" : ""}>Use With Caution</option>`,
            `<option value="3" ${rank == 3 ? "selected" : ""}>Satisfactory</option>`,
          ].join("");
          return `<select class="form-control rank-select ${cls}" data-id="${data.id}">${opts}</select>`;
        },
      },
      {
        data: "id",
        orderable: false,
        render: (id) => `
          <a href="${supplierBaseUrl}/${id}/edit" class="py-2 action-btn btn btn-sm btn-success" title="Edit">
            <i class="fa fa-edit"></i>
          </a>
          <a href="${supplierBaseUrl}/${id}" class="py-2 action-btn btn btn-sm btn-secondary ms-1" title="View">
            <i class="fa fa-eye"></i>
          </a>
          <button class="action-btn btn btn-sm btn-danger ms-1 btn-delete py-2" data-id="${id}" title="Delete">
            <i class="fa fa-trash"></i>
          </button>
        `,
      },
    ],
  });

  $("#filterCategory").on("change", function () {
    table.column(0).search(this.value).draw();
  });

  $("#filterPaymentTerm").on("change", function () {
    table.column(6).search(this.value).draw();
  });

  $("#btnClearFilter").on("click", function () {
    $("#filterCategory, #filterPaymentTerm").val("");
    table.search("").columns().search("").draw();
  });

  $("#btnExport").on("click", function () {
    const rows = table.rows({ search: "applied" }).data().toArray();
    const header = [
      "Category",
      "Business Name",
      "Email",
      "Phone",
      "ABN",
      "Account Email",
      "Payment Term",
      "Payment Days",
      "Address",
      "Notes",
      "Rank",
    ];
    const rankText = {
      1: "Do Not Use",
      2: "Use With Caution",
      3: "Satisfactory",
    };

    const csv = [
      header.join(","),
      ...rows.map((r) => {
        const term = r.paymentTerm || r.payment_term || null;
        return [
          csvEscape(r.category ? r.category.name : ""),
          csvEscape(r.supplier_name),
          csvEscape(r.supplier_email),
          csvEscape(r.supplier_phone),
          csvEscape(r.supplier_abn),
          csvEscape(r.supplier_bank_email),
          csvEscape(term ? term.name : ""),
          csvEscape(r.payment_term_days != null ? r.payment_term_days : ""),
          csvEscape(r.supplier_address),
          csvEscape(
            r.supplier_notes ? r.supplier_notes.replace(/<[^>]*>/g, "") : "",
          ),
          csvEscape(rankText[r.supplier_rank] || ""),
        ].join(",");
      }),
    ].join("\n");

    const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = "suppliers.csv";
    a.click();
    URL.revokeObjectURL(url);
  });

  function csvEscape(val) {
    if (val == null) return "";
    val = String(val).replace(/"/g, '""');
    return /[,"\n]/.test(val) ? `"${val}"` : val;
  }

  $("#suppliersTable").on("change", ".rank-select", function () {
    const select = $(this);
    const id = select.data("id");
    const val = select.val();

    select.removeClass("rank-1 rank-2 rank-3 rank-null");
    select.addClass(val ? rankClass[val] : "rank-null");

    $.ajax({
      url: `${supplierBaseUrl}/${id}/rank`,
      type: "PATCH",
      data: { supplier_rank: val, _token: csrfToken },
      success: () => showToast("Rank updated successfully.", "success"),
      error: () => showToast("Failed to update rank.", "danger"),
    });
  });

  $("#suppliersTable").on("click", ".btn-delete", function () {
    const id = $(this).data("id");
    if (!confirm("Are you sure you want to delete this supplier?")) return;

    $.ajax({
      url: `${supplierBaseUrl}/${id}`,
      type: "DELETE",
      data: { _token: csrfToken },
      success: function () {
        table.ajax.reload(null, false);
        showToast("Supplier deleted.", "success");
      },
      error: () => showToast("Failed to delete supplier.", "danger"),
    });
  });
});


// function showToast(msg, type = "success") {
//   const id = "toast_" + Date.now();
//   const html = `
//     <div id="${id}" class="toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3"
//          role="alert" style="z-index:9999">
//       <div class="d-flex">
//         <div class="toast-body">${msg}</div>
//         <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
//       </div>
//     </div>`;
//   $("body").append(html);
//   const el = document.getElementById(id);
//   new bootstrap.Toast(el, { delay: 3000 }).show();
//   el.addEventListener("hidden.bs.toast", () => el.remove());
// }
