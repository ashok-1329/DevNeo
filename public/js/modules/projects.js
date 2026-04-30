(function () {
  "use strict";

  /* ================================================================
     SHARED UTILITIES
  ================================================================ */

  const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

  window.showToast = function (message, type = "success") {
    const id = "toast_" + Date.now();
    $("body").append(`
      <div id="${id}" class="toast align-items-center text-white bg-${type} border-0 position-fixed top-0 end-0 m-3"
           style="z-index:99999" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">${message}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>`);
    new bootstrap.Toast(document.getElementById(id), { delay: 3500 }).show();
    setTimeout(() => $("#" + id).remove(), 4200);
  };

  function escHtml(str) {
    return String(str ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  /* ================================================================
     INDEX PAGE — DATATABLE
  ================================================================ */

  function initProjectTable() {
    if (!window.$ || !$.fn.DataTable) return;
    if (!document.getElementById("projectsTable")) return;

    const table = $("#projectsTable").DataTable({
      processing: true,
      serverSide: false,
      ajax: { url: projectDataUrl, dataSrc: "" },
      pageLength: 10,
      lengthMenu: [10, 25, 50, 100],
      scrollX: true,
      autoWidth: false,
      order: [[0, "desc"]],
      dom: '<"d-flex justify-content-between mb-2"l f>rtip',
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
        { data: "commencement_date" },

        /* ── STATUS ── */
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

            // Editable dropdown only for statuses 1, 4, 5
            if ([1, 4, 5].includes(Number(status))) {
              return `
                <select class="form-select form-select-sm status-change" data-id="${row.id}">
                  <option value="1" ${status == 1 ? "selected" : ""}>Active</option>
                  <option value="4" ${status == 4 ? "selected" : ""}>Defects</option>
                  <option value="5" ${status == 5 ? "selected" : ""}>Complete</option>
                </select>`;
            }

            return `<span class="badge bg-${badgeClass[status] ?? "secondary"}">
                      ${escHtml(labels[status] ?? status)}
                    </span>`;
          },
        },

        /* ── ACTIONS ── */
        {
          data: "id",
          orderable: false,
          className: "text-center",
          render: function (id) {
            return `
              <a href="${projectBaseUrl}/${id}"      class="btn btn-sm btn-secondary" title="View">
                <i class="fa fa-eye"></i>
              </a>
              <a href="${projectBaseUrl}/${id}/edit" class="btn btn-sm btn-success ms-1" title="Edit">
                <i class="fa fa-pencil"></i>
              </a>`;
          },
        },
      ],
    });

    /* ── STATUS CHANGE via AJAX ── */
    $("#projectsTable").on("change", ".status-change", function () {
      const id = this.dataset.id;
      const status = this.value;
      const statusMap = { 1: "Active", 4: "Defects", 5: "Complete" };
      const label = statusMap[status] ?? "Unknown";

      fetch(`${projectBaseUrl}/${id}/update-status`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({ status }),
      })
        .then((r) => r.json())
        .then(() => {
          showToast(
            `Status set to <strong>${label}</strong> successfully.`,
            "success",
          );
        })
        .catch(() => showToast("Failed to update status.", "danger"));
    });

    /* ── GLOBAL SEARCH ── */
    const searchInput = document.getElementById("globalSearch");
    if (searchInput) {
      searchInput.addEventListener("input", function () {
        table.search(this.value).draw();
      });
    }

    /* ── CLEAR FILTERS ── */
    const clearBtn = document.getElementById("btnClearFilter");
    if (clearBtn) {
      clearBtn.addEventListener("click", function () {
        table.search("").columns().search("").draw();
        if (searchInput) searchInput.value = "";
      });
    }

    /* ── EXPORT CSV ── */
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
              statusMap[r.status] ?? r.status,
            ].join(","),
          ),
        ].join("\n");

        const blob = new Blob([csv], { type: "text/csv" });
        const a = document.createElement("a");
        a.href = URL.createObjectURL(blob);
        a.download = "projects.csv";
        a.click();
        URL.revokeObjectURL(a.href);
      });
    }
  }

  /* ================================================================
     CREATE / EDIT PAGE — MULTI-STEP FORM
  ================================================================ */

  function initMultiStepForm() {
    if (!document.getElementById("step1")) return;

    /* ── State ─────────────────────────────────────────────── */
    let projectId = null;
    let completedSteps = new Set(
      typeof completedStepsData !== "undefined" ? completedStepsData : [],
    );

    /* ── Flatpickr ──────────────────────────────────────────── */
    if (typeof flatpickr !== "undefined") {
      flatpickr(".datepicker", { dateFormat: "d/m/Y", allowInput: true });
    }

    /* ── Pre-fill from existing project (draft recovery / edit) */
    if (typeof existingProject !== "undefined" && existingProject) {
      projectId = existingProject.id;
      prefillStep1(existingProject);
      prefillStep2(existingProject);
      prefillStep3(existingProject);
      prefillStep4(existingProject);
      prefillStep5(existingProject);
      completedSteps.forEach((s) => markComplete(s));
    }

    /* ── Step nav buttons ───────────────────────────────────── */
    $(document).on("click", ".step-btn", function () {
      showStep(parseInt($(this).data("step")));
    });

    /* ── Next / Prev bindings ───────────────────────────────── */
    $("#step1_next").on("click", () => nextStep(1));
    $("#step2_next").on("click", () => nextStep(2));
    $("#step3_next").on("click", () => nextStep(3));
    $("#step4_next").on("click", () => nextStep(4));
    $("#step5_next").on("click", () => nextStep(5));
    $("#step6_next").on("click", () => nextStep(6));
    $("#step7_next").on("click", () => nextStep(7));
    $("#step8_next").on("click", () => nextStep(8));
    $("#step9_next").on("click", () => nextStep(9));
    $("#step10_next").on("click", () => nextStep(10));
    $("#step11_next").on("click", () => nextStep(11));
    $("#step12_next").on("click", () => nextStep(12));
    // $("#step13_next").on("click", () => nextStep(13));
    $("#step13_finish").on("click", () => nextStep(13));

    $("#step2_prev").on("click", () => showStep(1));
    $("#step3_prev").on("click", () => showStep(2));
    $("#step4_prev").on("click", () => showStep(3));
    $("#step5_prev").on("click", () => showStep(4));
    $("#step6_prev").on("click", () => showStep(5));
    $("#step7_prev").on("click", () => showStep(6));
    $("#step8_prev").on("click", () => showStep(7));
    $("#step9_prev").on("click", () => showStep(8));
    $("#step10_prev").on("click", () => showStep(9));
    $("#step11_prev").on("click", () => showStep(10));
    $("#step12_prev").on("click", () => showStep(11));
    $("#step13_prev").on("click", () => showStep(12));

    /* ── Region → show "Other" input + auto-fill project number */
    $(document).ready(function () {
      $(document).on("change", "#client_id", function () {
        const clientId = $(this).val();

        if (!clientId) {
          // clear fields if nothing selected
          $("#client_representative").val("");
          $("#client_rep_email").val("");
          return;
        }

        const url = getClientUrl.replace(":id", clientId);

        $.ajax({
          url: url,
          type: "GET",
          success: function (res) {
            if (res) {
              $("#client_representative").val(
                res.client_representative || res.name || "",
              );
              $("#client_rep_email").val(
                res.client_rep_email || res.email || "",
              );
            }
          },
          error: function (err) {
            console.error("Error fetching client:", err);
          },
        });
      });

      $(document).on("change", "#project_region", function () {
        const val = $(this).val();

        // 👉 Handle "Other" field toggle
        if (val === "other") {
          $("#other_region_wrap").removeClass("d-none");
        } else {
          $("#other_region_wrap").addClass("d-none");
          $("#project_other_region").val("");
        }

        // 👉 Generate project number via GET
        if (
          val &&
          val !== "other" &&
          typeof projectNumberUrl !== "undefined" &&
          projectNumberUrl
        ) {
          $.ajax({
            url: projectNumberUrl,
            type: "GET",
            data: {
              region: val,
              project_id: typeof projectId !== "undefined" ? projectId : "",
            },
            success: function (res) {
              if (res && res.success && res.value) {
                $("#project_number").val(res.value);
              } else {
                console.warn("Invalid response:", res);
              }
            },
            error: function (xhr) {
              console.error(
                "Error generating project number:",
                xhr.responseText,
              );
            },
          });
        }
      });

      $(document).on(
        "input change",
        "#contract_value, #provisional_sum_total, #assign_profit_margin, #insurance_percentage",
        function () {
          calculateFinancials();
        },
      );

      $('input[name="bank_guarantee_required"]').on("change", function () {
        const val = $(this).val();

        if (val == "1") {
          $("#bank_fields").removeClass("d-none");
        } else {
          $("#bank_fields").addClass("d-none");

          // clear values
          $("#bank_fields").find("input, select").val("");
        }
      });

      $("#practical_completion").on("change", function () {
        if ($(this).val() === "custom") {
          $("#custom_practical_wrap").removeClass("d-none");
        } else {
          $("#custom_practical_wrap").addClass("d-none");
          $("#custom_practical_completion").val("");
        }
      });

      $("#final_completion").on("change", function () {
        if ($(this).val() === "custom") {
          $("#custom_final_wrap").removeClass("d-none");
        } else {
          $("#custom_final_wrap").addClass("d-none");
          $("#custom_final_completion").val("");
        }
      });
    });

    // 👉 Utility
    function toNum(val) {
      return parseFloat(val) || 0;
    }

    // 👉 Main calculation function (single source of truth)
    function calculateFinancials() {
      const contractInc = toNum($("#contract_value").val());
      const provisionalInc = toNum($("#provisional_sum_total").val());
      const margin = toNum($("#assign_profit_margin").val());
      const insurancePercent = toNum($("#insurance_percentage").val());

      const GST_RATE = 10;

      const contractEx = contractInc ? contractInc / (1 + GST_RATE / 100) : 0;
      const provisionalEx = provisionalInc
        ? provisionalInc / (1 + GST_RATE / 100)
        : 0;

      $("#contract_value_gst").val(contractEx.toFixed(2));
      $("#provisional_sum_total_gst").val(provisionalEx.toFixed(2));

      const budgetValue = contractEx * (1 - margin / 100);
      $("#assign_profit_margin_value").val(budgetValue.toFixed(2));

      let insuranceValue = 0;
      if (insurancePercent) {
        insuranceValue =
          budgetValue - (budgetValue * 100) / (100 + insurancePercent);
      }
      $("#insurance_percentage_value").val(insuranceValue.toFixed(2));

      const profitValue = contractEx - budgetValue;
      $("#profit_value").val(profitValue.toFixed(2));
    }

    /* ==============================================================
       SHOW STEP
    ============================================================== */
    function showStep(n) {
      $(".step-panel").addClass("d-none");
      $("#step" + n).removeClass("d-none");
      $("html, body").animate(
        { scrollTop: $(".step-card").offset().top - 20 },
        200,
      );
    }

    /* ==============================================================
       NEXT STEP  — validate → AJAX save → advance
    ============================================================== */
    function nextStep(step) {
      if (!validateStep(step)) return;

      const fd = buildFormData(step);

      $.ajax({
        url: projectStepUrl,
        method: "POST",
        data: fd,
        processData: false,
        contentType: false,
        headers: { "X-CSRF-TOKEN": csrfToken },
        success: function (res) {
          if (!res.success) {
            showToast(res.message || "Something went wrong.", "danger");
            return;
          }
          if (step === 1) projectId = res.project_id;

          completedSteps.add(step);
          markComplete(step);

          if (step < 14) {
            showStep(step + 1);
          } else {
            showToast("Project saved successfully!", "success");
            setTimeout(() => {
              window.location.href = res.redirect;
            }, 900);
          }
        },
        error: function (xhr) {
          const errors = xhr.responseJSON?.errors;
          if (errors) {
            const first = Object.values(errors)[0];
            showToast(Array.isArray(first) ? first[0] : first, "danger");
          } else {
            showToast(xhr.responseJSON?.message || "Server error.", "danger");
          }
        },
      });
    }

    /* ── Build FormData per step ──────────────────────────────── */
    function buildFormData(step) {
      const fd = new FormData();
      fd.append("step", step);
      if (projectId) fd.append("project_id", projectId);

      if (step === 1) {
        fd.append("project_code_id", $("#project_code_id").val().trim());
        fd.append("project_name", $("#project_name").val().trim());
        fd.append("project_region", $("#project_region").val());
        fd.append(
          "project_other_region",
          $("#project_other_region").val().trim(),
        );
        fd.append("project_number", $("#project_number").val().trim());
        fd.append(
          "project_description",
          $("#project_description").val().trim(),
        );
        fd.append("project_address", $("#project_address").val().trim());
        fd.append("project_notes", $("#project_notes").val().trim());
        fd.append(
          "current_step",
          completedSteps.size ? Math.max(...completedSteps) : 0,
        );
      }

      if (step === 2) {
        fd.append("client_id", $("#client_id").val());
        fd.append(
          "client_representative",
          $("#client_representative").val().trim(),
        );
        fd.append("client_rep_email", $("#client_rep_email").val().trim());
        fd.append("superintendent", $("#superintendent").val().trim());
        fd.append("superintendent_rep", $("#superintendent_rep").val().trim());
        fd.append(
          "superintendent_rep_email",
          $("#superintendent_rep_email").val().trim(),
        );
        fd.append(
          "client_phone_number",
          $("#client_phone_number").val().trim(),
        );
        fd.append("client_address", $("#client_address").val().trim());
        fd.append("invoices_sent_to", $("#invoices_sent_to").val().trim());
      }

      if (step === 3) {
        fd.append("construction_manager", $("#construction_manager").val());
        fd.append("project_manager", $("#project_manager").val());
        fd.append("supervisor", $("#supervisor").val());
        fd.append("project_engineer", $("#project_engineer").val());
        fd.append("contract_admin", $("#contract_admin").val());
      }

      if (step === 4) {
        fd.append("contract_number", $("#contract_number").val().trim());
        fd.append("commencement_date", $("#commencement_date").val());
        fd.append("completion_date", $("#completion_date").val());
        fd.append("contract_type", $("#contract_type").val());
        fd.append("payment_term", $("#payment_term").val());
        fd.append(
          "claims_certification_period",
          $("#claims_certification_period").val(),
        );
        fd.append("contract_notes", $("#contract_notes").val().trim());
      }

      if (step === 5) {
        fd.append("lump_sum", $("#lump_sum").is(":checked") ? 1 : 0);
        fd.append(
          "schedule_of_rate",
          $("#schedule_of_rate").is(":checked") ? 1 : 0,
        );

        fd.append("contract_value", $("#contract_value").val());
        fd.append("contract_value_gst", $("#contract_value_gst").val());

        fd.append("provisional_sum_total", $("#provisional_sum_total").val());
        fd.append(
          "provisional_sum_total_gst",
          $("#provisional_sum_total_gst").val(),
        );

        fd.append("assign_profit_margin", $("#assign_profit_margin").val());
        fd.append(
          "assign_profit_margin_value",
          $("#assign_profit_margin_value").val(),
        );

        fd.append("insurance_percentage", $("#insurance_percentage").val());
        fd.append(
          "insurance_percentage_value",
          $("#insurance_percentage_value").val(),
        );

        fd.append("profit_value", $("#profit_value").val());
      }

      if (step === 6) {
        const isYes = $('input[name="bank_guarantee_required"]:checked').val();

        fd.append("bank_guarantee_required", isYes ?? "");

        if (isYes == "1") {
          fd.append("practical_completion", $("#practical_completion").val());
          fd.append(
            "practical_completion_amount",
            $("#practical_completion_amount").val(),
          );
          fd.append(
            "custom_practical_completion",
            $("#custom_practical_completion").val(),
          );

          fd.append("final_completion", $("#final_completion").val());
          fd.append(
            "final_completion_amount",
            $("#final_completion_amount").val(),
          );
          fd.append(
            "custom_final_completion",
            $("#custom_final_completion").val(),
          );
        }
      }

      return fd;
    }

    /* ── Turn step nav button green ───────────────────────────── */
    function markComplete(step) {
      $(`.step-btn[data-step="${step}"]`)
        .removeClass("btn-light btn-secondary")
        .addClass("btn-success");
    }

    /* ── Pre-fill helpers ─────────────────────────────────────── */
    function prefillStep1(d) {
      if (d.project_code_id) $("#project_code_id").val(d.project_code_id);
      if (d.project_name) $("#project_name").val(d.project_name);
      if (d.project_number) $("#project_number").val(d.project_number);
      if (d.project_description)
        $("#project_description").val(d.project_description);
      if (d.project_address) $("#project_address").val(d.project_address);
      if (d.project_notes) $("#project_notes").val(d.project_notes);
      if (d.project_region) {
        $("#project_region").val(d.project_region);
        if (d.project_region === "other") {
          $("#other_region_wrap").removeClass("d-none");
          $("#project_other_region").val(d.project_other_region ?? "");
        }
      }
    }

    function prefillStep2(d) {
      if (d.client_id) $("#client_id").val(d.client_id);
      if (d.client_representative)
        $("#client_representative").val(d.client_representative);
      if (d.client_rep_email) $("#client_rep_email").val(d.client_rep_email);
      if (d.superintendent) $("#superintendent").val(d.superintendent);
      if (d.superintendent_rep)
        $("#superintendent_rep").val(d.superintendent_rep);
      if (d.superintendent_rep_email)
        $("#superintendent_rep_email").val(d.superintendent_rep_email);
      if (d.client_phone_number)
        $("#client_phone_number").val(d.client_phone_number);
      if (d.client_address) $("#client_address").val(d.client_address);
      if (d.invoices_sent_to) $("#invoices_sent_to").val(d.invoices_sent_to);
    }

    function prefillStep3(d) {
      if (d.construction_manager)
        $("#construction_manager").val(d.construction_manager);
      if (d.project_manager) $("#project_manager").val(d.project_manager);
      if (d.supervisor) $("#supervisor").val(d.supervisor);
      if (d.project_engineer) $("#project_engineer").val(d.project_engineer);
      if (d.contract_admin) $("#contract_admin").val(d.contract_admin);
    }

    function prefillStep4(d) {
      if (d.contract_number) $("#contract_number").val(d.contract_number);
      if (d.contract_type) $("#contract_type").val(d.contract_type);
      if (d.payment_term) $("#payment_term").val(d.payment_term);
      if (d.commencement_date) $("#commencement_date").val(d.commencement_date);
      if (d.completion_date) $("#completion_date").val(d.completion_date);
      if (d.claims_certification_period)
        $("#claims_certification_period").val(d.claims_certification_period);
      if (d.contract_notes) $("#contract_notes").val(d.contract_notes);
    }

    function prefillStep5(d) {
      $("#lump_sum").prop("checked", d.lump_sum == 1);
      $("#schedule_of_rate").prop("checked", d.schedule_of_rate == 1);

      $("#contract_value").val(d.contract_value);
      $("#contract_value_gst").val(d.contract_value_gst);

      $("#provisional_sum_total").val(d.provisional_sum_total);
      $("#provisional_sum_total_gst").val(d.provisional_sum_total_gst);

      $("#assign_profit_margin").val(d.assign_profit_margin);
      $("#assign_profit_margin_value").val(d.assign_profit_margin_value);

      $("#insurance_percentage").val(d.insurance_percentage);
      $("#insurance_percentage_value").val(d.insurance_percentage_value);

      $("#profit_value").val(d.profit_value);
    }

    $("#client_phone_number").on("input", function () {
      this.value = this.value.replace(/[^0-9]/g, "");
    });

    /* ==============================================================
       VALIDATION
    ============================================================== */
    function validateStep(step) {
      clearErrors();
      let ok = true;

      if (step === 1) {
        if (!req($("#project_name"), "Project name is required")) ok = false;
        if (!req($("#project_region"), "Region is required")) ok = false;
        if (!req($("#project_number"), "Project number is required"))
          ok = false;
        if ($("#project_region").val() === "other") {
          const other = $("#project_other_region");
          if (!other.val().trim()) {
            showErr(other, "Please specify the region");
            ok = false;
          }
        }
        if (!req($("#project_notes"), "Project Note is required")) ok = false;
      }

      if (step === 2) {
        if (
          !req($("#client_representative"), "Client representative is required")
        )
          ok = false;
        if (!req($("#client_id"), "Client is required")) ok = false;

        const email = $("#client_rep_email");
        if (!email.val().trim()) {
          showErr(email, "Email is required");
          ok = false;
        } else if (!/^\S+@\S+\.\S+$/.test(email.val())) {
          showErr(email, "Enter a valid email");
          ok = false;
        }

        if (!req($("#superintendent"), "Superintendent is required"))
          ok = false;
        if (!req($("#superintendent_rep"), "Superintendent Rep is required"))
          ok = false;
        if (
          !req(
            $("#superintendent_rep_email"),
            "Superintendent Email is required",
          )
        )
          ok = false;

        // ✅ NEW VALIDATIONS

        if (!req($("#client_phone_number"), "Client phone number is required"))
          ok = false;

        if (!req($("#client_address"), "Client address is required"))
          ok = false;

        const invoiceEmail = $("#invoices_sent_to");
        if (!invoiceEmail.val().trim()) {
          showErr(invoiceEmail, "Invoices email is required");
          ok = false;
        } else if (!/^\S+@\S+\.\S+$/.test(invoiceEmail.val())) {
          showErr(invoiceEmail, "Enter a valid email");
          ok = false;
        }
      }

      if (step === 3) {
        if (
          !req($("#construction_manager"), "Construction Manager is required")
        )
          ok = false;
        if (!req($("#project_manager"), "Project Manager is required"))
          ok = false;
        if (!req($("#project_engineer"), "Project Engineer is required"))
          ok = false;
        if (!req($("#supervisor"), "Supervisor is required")) ok = false;
        if (!req($("#contract_admin"), "Contract Adminstrator is required"))
          ok = false;
      }

      if (step === 4) {
        if (!req($("#contract_type"), "Contract Type is required")) ok = false;
        if (!req($("#contract_number"), "Contract number is required"))
          ok = false;
        if (!req($("#payment_term"), "Payment Term is required")) ok = false;
        if (!req($("#payment_term"), "Payment Term is required")) ok = false;
        if (
          !req(
            $("#claims_certification_period"),
            "Claims Certification Period is required",
          )
        )
          ok = false;
        if (!req($("#contract_notes"), "Contract Notes is required"))
          ok = false;
      }

      if (step === 5) {
        if (!req($("#contract_value"), "Contract value is required"))
          ok = false;
        if (!req($("#contract_value_gst"), "Contract GST value is required"))
          ok = false;
        if (
          !req(
            $("#assign_profit_margin_value"),
            "Assign Profit Margin is required",
          )
        )
          ok = false;
        if (
          !req(
            $("#insurance_percentage_value"),
            "Insurance Percentage is required",
          )
        )
          ok = false;
        if (!req($("#profit_value"), "profit Value value is required"))
          ok = false;
        if (!req($("#provisional_sum_total_gst"), "Total Sum is required"))
          ok = false;
        if (!req($("#provisional_sum_total"), "Provisional sum is required"))
          ok = false;
        if (!req($("#assign_profit_margin"), "Profit margin is required"))
          ok = false;
        if (!req($("#insurance_percentage"), "Insurance % is required"))
          ok = false;
      }

      return ok;
    }

    function req($el, msg) {
      if (!$el.val() || !String($el.val()).trim()) {
        showErr($el, msg);
        return false;
      }
      return true;
    }

    function clearErrors() {
      $(".is-invalid").removeClass("is-invalid");
      $(".invalid-feedback").remove();
    }

    function showErr($el, msg) {
      $el.addClass("is-invalid");
      if (!$el.next(".invalid-feedback").length) {
        $el.after(`<div class="invalid-feedback">${msg}</div>`);
      }
    }
  }

  /* ================================================================
     BOOT  — both functions guard themselves with element checks
  ================================================================ */
  document.addEventListener("DOMContentLoaded", function () {
    initProjectTable();
    initMultiStepForm();
  });
})();
