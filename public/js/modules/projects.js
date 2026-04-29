(function () {
  "use strict";

  function initProjectTable() {
    if (!window.$ || !$.fn.DataTable) return;

    const table = $("#projectsTable").DataTable({
      processing: true,
      serverSide: false,
      ajax: {
        url: projectDataUrl,
        dataSrc: "",
      },

      pageLength: 10,
      lengthMenu: [10, 25, 50, 100],

      scrollX: true,
      autoWidth: false,

      order: [[0, "desc"]],

      dom: '<"d-flex justify-content-between mb-2"l f>rtip',
      order: [[0, "desc"]], 
      columns: [
        { data: "sno" },
        { data: "project_name" },
        { data: "project_number" },
        { data: "project_code" },
        { data: "project_region" },
        { data: "project_client" },
        { data: "construction_manager" },
        { data: "project_manager" },
        { data: "supervisor" },
        { data: "project_engineer" },
        { data: "contract_admin" },
        { data: "commencement_date" ,render: (d) => formatDate(d),},

        /* STATUS COLUMN (UPDATED) */
        {
          data: "status",
          className: "text-center",
          render: function (status, type, row) {
            const labels = {
              1: "Active",
              2: "Deactive",
              3: "Archive",
              4: "Defects",
              5: "Complete",
            };

            const badgeClass = {
              1: "success",
              2: "secondary",
              3: "dark",
              4: "warning",
              5: "primary",
            };

            // Editable only for 1,4,5
            if ([1, 4, 5].includes(status)) {
              return `
                <select class="form-select form-select-sm status-change"
                        data-id="${row.id}">
                  <option value="1" ${status == 1 ? "selected" : ""}>Active</option>
                  <option value="4" ${status == 4 ? "selected" : ""}>Defects</option>
                  <option value="5" ${status == 5 ? "selected" : ""}>Complete</option>
                </select>
              `;
            }

            return `<span class="badge bg-${badgeClass[status]}">
              ${labels[status]}
            </span>`;
          },
        },

        {
          data: "id",
          orderable: false,
          className: "text-center",
          render: function (id) {
            return `
              <a href="${projectBaseUrl}/${id}" class="btn btn-sm btn-secondary">
                <i class="fa fa-eye"></i>
              </a>
              <a href="${projectBaseUrl}/${id}/edit" class="btn btn-sm btn-success ms-1">
                <i class="fa fa-pencil"></i>
              </a>
            `;
          },
        },
      ],
    });

    /* STATUS UPDATE (AJAX) */
    $("#projectsTable").on("change", ".status-change", function () {
      const id = this.dataset.id;
      const status = this.value;

      const statusMap = {
        1: "Active",
        4: "Defects",
      };

      const statusText = statusMap[status] || "Complete"; // ⚠️ use status, not id

      fetch(`${projectBaseUrl}/${id}/update-status`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({ status }),
      })
        .then((res) => res.json())
        .then((res) => {
          showToast(
            `Project Status set to ${statusText} successfully.`,
            res.status == 1 ? "success" : "secondary",
          );
        })
        .catch(() => {
          showToast("Failed to update status.", "danger");
        });
    });

    /* GLOBAL SEARCH */
    const searchInput = document.getElementById("globalSearch");
    if (searchInput) {
      searchInput.addEventListener("input", function () {
        table.search(this.value).draw();
      });
    }

    /* CLEAR FILTER */
    const clearBtn = document.getElementById("btnClearFilter");
    if (clearBtn) {
      clearBtn.addEventListener("click", function () {
        table.search("").columns().search("").draw();
        if (searchInput) searchInput.value = "";
      });
    }

    /* EXPORT CSV */
    const exportBtn = document.getElementById("btnExport");
    if (exportBtn) {
      exportBtn.addEventListener("click", function () {
        const rows = table.rows({ search: "applied" }).data().toArray();

        const statusMap = {
          1: "Active",
          2: "Deactive",
          3: "Archive",
          4: "Defects",
          5: "Complete",
        };

        const headers = [
          "S.No",
          "Project Name",
          "Project No",
          "Project Code",
          "Region",
          "Client",
          "Construction Manager",
          "Project Manager",
          "Supervisor",
          "Project Engineer",
          "Contract Admin",
          "Date Commenced",
          "Status",
        ];

        const csv = [
          headers.join(","),
          ...rows.map((r) =>
            [
              r.sno,
              `"${r.project_name}"`,
              `"${r.project_number}"`,
              `"${r.project_code}"`,
              `"${r.project_region}"`,
              `"${r.project_client}"`,
              `"${r.construction_manager}"`,
              `"${r.project_manager}"`,
              `"${r.supervisor}"`,
              `"${r.project_engineer}"`,
              `"${r.contract_admin}"`,
              `"${r.commencement_date}"`,
              statusMap[r.status],
            ].join(","),
          ),
        ].join("\n");

        const blob = new Blob([csv], { type: "text/csv" });
        const url = URL.createObjectURL(blob);

        const a = document.createElement("a");
        a.href = url;
        a.download = "projects.csv";
        a.click();

        URL.revokeObjectURL(url);
      });
    }
  }

  document.addEventListener("DOMContentLoaded", function () {
    initProjectTable();
  });
})();
