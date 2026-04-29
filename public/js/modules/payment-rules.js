$(document).ready(function () {
  if (!$("#paymentRulesTable").length) return;

  const statusBadge = {
    1: '<span class="badge bg-success" style="color: white;">Active</span>',
    0: '<span class="badge bg-secondary" style="color: white;>Inactive</span>',
  };

  const table = $("#paymentRulesTable").DataTable({
    processing: true,
    ajax: { url: paymentRulesDataUrl, dataSrc: "" },
    scrollX: true,
    dom: '<"d-flex justify-content-between align-items-center mb-2"lf>rtip',
    order: [[0, "desc"]],
    columns: [
      {
        data: null,
        render: (d) => (d.supplier ? d.supplier.supplier_name : "-"),
      },
      { data: "project_number", defaultContent: "-" },
      {
        data: "payment_date",
        render: (d) => formatDate(d),
      },
      {
        data: "end_date",
        render: (d) => formatDate(d),
      },
      {
        data: null,
        render: (d) => (d.frequency_payment ? d.frequency_payment.name : "-"),
      },
      // {
      //   data: "value_inc_gst",
      //   render: (d) =>
      //     d
      //       ? `<span class="fw-semibold">$${Number(d).toLocaleString("en-AU", { minimumFractionDigits: 2 })}</span>`
      //       : "-",
      // },
      // { data: "project_code", defaultContent: "-" },
      {
        data: "status",
        orderable: false,
        render: (d) => statusBadge[d] ?? statusBadge[0],
      },
      {
        data: "id",
        orderable: false,
        render: (id) => `
          <a href="${paymentRuleBaseUrl}/${id}/edit"
             class="action-btn btn btn-sm btn-success py-1" title="Edit">
            <i class="fa fa-edit"></i>
          </a>
          <a href="${paymentRuleBaseUrl}/${id}"
             class="action-btn btn btn-sm btn-secondary ms-1 py-1" title="View">
            <i class="fa fa-eye"></i>
          </a>
          <button class="action-btn btn btn-sm btn-danger ms-1 py-1 btn-delete"
                  data-id="${id}" title="Delete">
            <i class="fa fa-trash"></i>
          </button>
        `,
      },
    ],

  });

  $("#filterSupplier").on("change", function () {
    table.column(0).search(this.value).draw();
  });

  $("#filterFrequency").on("change", function () {
    table.column(2).search(this.value).draw();
  });

  $("#filterStatus").on("change", function () {
    table.column(7).search(this.value).draw();
  });

  $("#btnClearFilter").on("click", function () {
    $("#filterSupplier, #filterFrequency, #filterStatus").val("");
    table.search("").columns().search("").draw();
  });

  $("#btnExport").on("click", function () {
    const rows = table.rows({ search: "applied" }).data().toArray();
    const header = [
      "Supplier",
      "Payment Date",
      "Frequency",
      "End Date",
      "Value (inc. GST)",
      "Project Number",
      "Project Code",
      "Status",
    ];

    const csv = [
      header.join(","),
      ...rows.map((r) =>
        [
          csvEscape(r.supplier ? r.supplier.supplier_name : ""),
          csvEscape(r.payment_date),
          csvEscape(r.frequency_payment ? r.frequency_payment.name : ""),
          csvEscape(r.end_date),
          csvEscape(r.value_inc_gst),
          csvEscape(r.project_number),
          csvEscape(r.project_code),
          csvEscape(r.status == 1 ? "Active" : "Inactive"),
        ].join(","),
      ),
    ].join("\n");

    const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = "payment-rules.csv";
    a.click();
    URL.revokeObjectURL(url);
  });

  function csvEscape(val) {
    if (val == null) return "";
    val = String(val).replace(/"/g, '""');
    return /[,"\n]/.test(val) ? `"${val}"` : val;
  }

  $("#paymentRulesTable").on("click", ".btn-delete", function () {
    const id = $(this).data("id");
    if (!confirm("Are you sure you want to delete this payment rule?")) return;

    $.ajax({
      url: `${paymentRuleBaseUrl}/${id}`,
      type: "DELETE",
      data: { _token: csrfToken },
      success: function () {
        table.ajax.reload(null, false);
        showToast("Payment rule deleted.", "success");
      },
      error: () => showToast("Failed to delete payment rule.", "danger"),
    });
  });
});
