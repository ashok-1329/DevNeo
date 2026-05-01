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
      prefillStep6(existingProject);
      prefillStep7(existingProject);
      prefillStep8(existingProject);
      prefillStep9(existingProject);
      prefillStep10(existingProject);
      prefillStep11(existingProject);
      prefillStep12(existingProject);
      prefillStep13(existingProject);

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
    $("#step13_next").on("click", () => nextStep(13));
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

      $(
        "#custom_practical_completion, #custom_final_completion, #custom_cash_practical_completion, #custom_cash_final_completion",
      ).on("input", function () {
        let val = parseFloat($(this).val());

        if (val >= 100) {
          $(this).val(0);
        }
      });

      $(document).on("input", "input[name^='setupcode']", function () {
        let val = parseFloat($(this).val());
        if (isNaN(val)) return;
        if (val >= 100) {
          $(this).val(0);
        }
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
          $("#bank_fields").find("input, select").val("");
        }
      });

      // custom toggles
      $("#practical_completion").on("change", function () {
        $("#custom_practical_wrap").toggleClass(
          "d-none",
          $(this).val() !== "custom",
        );
        calculateCompletionAmounts();
      });

      $("#final_completion").on("change", function () {
        $("#custom_final_wrap").toggleClass(
          "d-none",
          $(this).val() !== "custom",
        );
        calculateCompletionAmounts();
      });

      // live updates
      $("#custom_practical_completion, #custom_final_completion").on(
        "input",
        calculateCompletionAmounts,
      );

      // 🔥 IMPORTANT: also recalc if contract value changes (step 5 dependency)
      $("#contract_value_gst").on("input change", calculateCompletionAmounts);

      // toggle
      $('input[name="cash_retentions_required"]').on("change", function () {
        const val = $(this).val();

        if (val == "1") {
          $("#cash_fields").removeClass("d-none");
        } else {
          $("#cash_fields").addClass("d-none");
          $("#cash_fields").find("input, select").val("");
        }
      });

      // custom toggle
      $("#cash_practical_completion").on("change", function () {
        $("#custom_cash_practical_wrap").toggleClass(
          "d-none",
          $(this).val() !== "custom",
        );
        calculateCashRetention;
      });

      $("#cash_final_completion").on("change", function () {
        $("#custom_cash_final_wrap").toggleClass(
          "d-none",
          $(this).val() !== "custom",
        );
        calculateCashRetention;
      });

      // live input
      $("#custom_cash_practical_completion, #custom_cash_final_completion").on(
        "input",
        calculateCashRetention,
      );

      // 🔥 dependent on step 5
      $("#contract_value_gst").on("input change", calculateCashRetention);

      $(document).on("change", ".resource-checkbox", function () {
        const el = $(this);

        $.ajax({
          url: toggleResourceUrl,
          method: "POST",
          headers: { "X-CSRF-TOKEN": csrfToken },
          data: {
            project_id: projectId,
            record_id: el.data("id"),
            type: el.data("type"),
            checked: el.is(":checked") ? 1 : 0,
          },
          error: function () {
            showToast("Something went wrong", "danger");
          },
        });
      });
    });

    // 👉 Utility
    function toNum(val) {
      return parseFloat(val) || 0;
    }

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

    function showStep(n) {
      $(".step-panel").addClass("d-none");
      $("#step" + n).removeClass("d-none");
      $("html, body").animate(
        { scrollTop: $(".step-card").offset().top - 20 },
        200,
      );
    }

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

      if (step === 7) {
        const isYes = $('input[name="cash_retentions_required"]:checked').val();

        fd.append("cash_retentions_required", isYes ?? "");

        if (isYes === "1") {
          fd.append(
            "cash_practical_completion",
            $("#cash_practical_completion").val(),
          );
          fd.append(
            "custom_cash_practical_completion",
            $("#custom_cash_practical_completion").val(),
          );
          fd.append(
            "cash_practical_completion_amount",
            $("#cash_practical_completion_amount").val(),
          );

          fd.append("cash_final_completion", $("#cash_final_completion").val());
          fd.append(
            "custom_cash_final_completion",
            $("#custom_cash_final_completion").val(),
          );
          fd.append(
            "cash_final_completion_amount",
            $("#cash_final_completion_amount").val(),
          );
        }
      }

      if (step === 8) {
        const fileInput = document.querySelector(
          'input[name="pricing_schedule"]',
        );

        if (fileInput && fileInput.files.length > 0) {
          fd.append("pricing_schedule", fileInput.files[0]);
        }

        // assign margins
        let margins = {};
        $(".assign-margin").each(function () {
          const id = $(this).data("id");
          margins[id] = $(this).val();
        });

        fd.append("assign_margin", JSON.stringify(margins));
      }

      if (step === 9) {
        let margins = {};
        let descriptions = {};

        $("input[name^='setupcode']").each(function () {
          const id = $(this).attr("name").match(/\d+/)[0];
          margins[id] = $(this).val();
        });
        $("input[name^='setupname']").each(function () {
          const id = $(this).attr("name").match(/\d+/)[0];
          descriptions[id] = $(this).val();
        });

        fd.append("assign_margin", JSON.stringify(margins));
        fd.append("desc_name", JSON.stringify(descriptions));
      }

      if (step === 10) {
        let codes = {};
        let descriptions = {};

        $(".code-select").each(function () {
          const id = $(this).data("id");
          codes[id] = $(this).val();
        });

        $("input[name^='item']").each(function () {
          const id = $(this).attr("name").match(/\d+/)[0];
          descriptions[id] = $(this).val();
        });
        fd.append("codes", JSON.stringify(codes));
        fd.append("descriptions", JSON.stringify(descriptions));
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

    function prefillStep6(d) {
      if (!d) return;

      if (
        d.bank_guarantee_required !== null &&
        d.bank_guarantee_required !== undefined
      ) {
        $(
          'input[name="bank_guarantee_required"][value="' +
            d.bank_guarantee_required +
            '"]',
        )
          .prop("checked", true)
          .trigger("change");
      }

      if (d.bank_guarantee_required) {
        $("#bank_fields").removeClass("d-none");
      }

      if (d.practical_completion) {
        $("#practical_completion")
          .val(d.practical_completion)
          .trigger("change");
      }

      if (d.custom_practical_completion) {
        $("#custom_practical_completion").val(d.custom_practical_completion);
        $("#custom_practical_wrap").removeClass("d-none"); // 🔥 force show
      }

      if (d.practical_completion_amount) {
        $("#practical_completion_amount").val(d.practical_completion_amount);
      }

      if (d.final_completion) {
        $("#final_completion").val(d.final_completion).trigger("change");
      }

      if (d.custom_final_completion) {
        $("#custom_final_completion").val(d.custom_final_completion);
        $("#custom_final_wrap").removeClass("d-none");
      }

      if (d.final_completion_amount) {
        $("#final_completion_amount").val(d.final_completion_amount);
      }

      calculateCompletionAmounts();
    }

    function prefillStep7(d) {
      if (!d) return;

      if (
        d.cash_retentions_required !== null &&
        d.cash_retentions_required !== undefined
      ) {
        $(
          'input[name="cash_retentions_required"][value="' +
            d.cash_retentions_required +
            '"]',
        )
          .prop("checked", true)
          .trigger("change");
      }
      if (d.cash_retentions_required) {
        $("#cash_fields").removeClass("d-none");
      }

      if (d.cash_practical_completion) {
        $("#cash_practical_completion")
          .val(d.cash_practical_completion)
          .trigger("change");
      }

      if (d.custom_cash_practical_completion) {
        $("#custom_cash_practical_completion").val(
          d.custom_cash_practical_completion,
        );
        $("#custom_cash_practical_wrap").removeClass("d-none");
      }

      if (d.cash_practical_completion_amount) {
        $("#cash_practical_completion_amount").val(
          d.cash_practical_completion_amount,
        );
      }

      // FINAL
      if (d.cash_final_completion) {
        $("#cash_final_completion")
          .val(d.cash_final_completion)
          .trigger("change");
      }

      if (d.custom_cash_final_completion) {
        $("#custom_cash_final_completion").val(d.custom_cash_final_completion);
        $("#custom_cash_final_wrap").removeClass("d-none");
      }

      if (d.cash_final_completion_amount) {
        $("#cash_final_completion_amount").val(d.cash_final_completion_amount);
      }

      // ensure amounts are correct after load
      calculateCashRetention;
    }

    function prefillStep8(d) {}

    function prefillStep9(d) {
      if (!d || !d.assign_codes) return;

      const tbody = $("#step9 tbody");
      tbody.empty();

      d.assign_codes.forEach((row) => {
        // find margin for this row's code
        let assign = (d.assign_codes || []).find(
          (c) => c.code_name == row.code_name,
        );

        let margin = assign ? assign.assign_margin : "";

        tbody.append(`
      <tr>
        <td>${row.code_name || ""}</td>

        <td>
          <input type="text"
            name="setupname[${row.id}]"
            class="form-control desc"
            data-id="${row.id}"
            value="${row.name || ""}">
        </td>
          
        <td>
            <input type="number"
              name="setupcode[${row.id}]"
              class="form-control margin"
              data-code="${row.assign_margin || ""}"
              value="${margin}"
              min="0" max="100">
        </td>
      </tr>
    `);
      });
    }

    function prefillStep10(d) {
      if (!d || !d.pricing_schedules) return;

      const table = $("#pricing_table tbody");
      table.empty();

      d.pricing_schedules.forEach((row, index) => {
        table.append(`
      <tr>
        <td>${index + 1 || ""}</td>

        <td>
          <input type="text"
            name="item[${row.id}]"
            class="form-control"
            data-id="${row.id}"
            value="${row.item || ""}">
        </td>

        <td>${row.quantity || ""}</td>
        <td>${row.unit || ""}</td>
        <td>${row.rate || ""}</td>
        <td>${row.amount || ""}</td>

        <td>
          <select class="form-select code-select"
            data-id="${row.id}">
            <option value="">Select</option>
            ${assignCodes
              .map(
                (code) => `
              <option value="${code.id}"
                ${row.code_id == code.id ? "selected" : ""}>
                ${code.code_id}
              </option>
            `,
              )
              .join("")}
          </select>
        </td>
      </tr>
    `);
      });
    }

    function prefillStep11(d) {
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
            data: "id",
            render: (id) => `
          <div class="form-check d-flex justify-content-center">
            <input type="checkbox"
              class="form-check-input resource-checkbox"
              data-id="${id}"
              data-type="material">
          </div>
        `,
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

        order: [[0, "desc"]],

        drawCallback: function () {
          const data = d.ProjectMaterialManage || [];

          $(".resource-checkbox").each(function () {
            const el = $(this);
            const id = el.data("id");

            const record = data.find((r) => r.record_id == id);

            if (record) {
              const periods = record.periods || [];

              if (periods.length) {
                const last = periods[periods.length - 1];
                const active = last.to === null && last.status === "active";

                el.prop("checked", active);
              } else {
                el.prop("checked", false);
              }
            } else {
              el.prop("checked", false);
            }
          });
        },
      });
    }

    function prefillStep12(d) {
      const tableEl = document.getElementById("plantTableProject");
      if (!tableEl || typeof $.fn.DataTable === "undefined") return;

      const table = $("#plantTableProject").DataTable({
        processing: true,
        destroy: true,

        ajax: { url: plantDataUrl, dataSrc: "" },

        columns: [
          { data: "plant_code", defaultContent: "-" }, // Asset ID

          {
            data: "plant_type",
            defaultContent: "-",
            render: (d) => (d ? d : "-"), // you can map labels if needed
          },

          { data: "plant_capacity", defaultContent: "-" },

          { data: "supplier", defaultContent: "-" },

          {
            data: "plant_name",
            defaultContent: "-", // Plant Description
          },

          { data: "unit", defaultContent: "-" },

          {
            data: "rate",
            defaultContent: "-",
            render: (d) => (d ? `$${parseFloat(d).toLocaleString()}` : "-"),
          },

          {
            data: "is_docket",
            defaultContent: "-",
            className: "text-center",
            render: (d) =>
              d == 1
                ? '<span class="badge bg-success">Yes</span>'
                : '<span class="badge bg-secondary">No</span>',
          },
          {
            data: "id",
            orderable: false,
            searchable: false,
            className: "text-center",
            render: (id) => `
          <div class="form-check d-flex justify-content-center">
            <input type="checkbox" class="form-check-input btn-assign resource-checkbox" data-id="${id}" data-type="plant">
          </div>
        `,
          },
          {
            data: "id",
            orderable: false,
            searchable: false,
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
        drawCallback: function () {
          const data = d.ProjectManagePlant || [];

          $("#plantTableProject .resource-checkbox").each(function () {
            const el = $(this);
            const id = el.data("id");

            const record = data.find((r) => r.plant_id == id);

            if (record) {
              el.prop("checked", isActive(record.periods));
            } else {
              el.prop("checked", false);
            }
          });
        },

        language: {
          search: "",
          searchPlaceholder: "Search plant…",
          lengthMenu: "Show _MENU_ entries",
          emptyTable: "No plant records found.",
        },

        initComplete: function () {
          $("#plantTableProject_filter input")
            .addClass("form-control")
            .css("width", "260px");
        },
      });

      // ── DELETE ─────────────────────────────
      $("#plantTableProject").on("click", ".btn-delete", function () {
        const id = $(this).data("id");

        if (!confirm("Delete this plant record?")) return;

        $.ajax({
          url: `${plantBaseUrl}/${id}`,
          type: "DELETE",
          data: { _token: csrfToken },
          success: () => {
            table.ajax.reload(null, false);
            showToast("Deleted successfully", "success");
          },
          error: () => showToast("Error occurred", "danger"),
        });
      });

      $("#plantTableProject").on("change", ".btn-assign", function () {
        const id = $(this).data("id");
        const checked = $(this).is(":checked");

        $.ajax({
          url: `${plantBaseUrl}/${id}/assign`,
          type: "POST",
          data: {
            _token: csrfToken,
            assigned: checked ? 1 : 0,
          },
        });
      });
    }

    function prefillStep13(d) {
      const tableEl = document.getElementById("labourTableProject");
      if (!tableEl || typeof $.fn.DataTable === "undefined") return;

      const typeBadge = {
        Internal: '<span class="badge bg-success">Internal</span>',
        External: '<span class="badge bg-secondary">External</span>',
      };

      const table = $("#labourTableProject").DataTable({
        processing: true,
        destroy: true, // ✅ important (like safe reload)

        ajax: { url: labourDataUrl, dataSrc: "" },

        columns: [
          { data: "name" },
          { data: "employment_type" },
          { data: "position" },
          { data: "employer" },
          { data: "region" },
          { data: "rate" },
          {
            data: "id",
            orderable: false,
            searchable: false,
            className: "text-center",
            render: (id) => `
          <div class="form-check d-flex justify-content-center">
            <input type="checkbox" class="form-check-input btn-assign resource-checkbox" data-id="${id}" data-type="labour">
          </div>
        `,
          },

          // Action column
          {
            data: "id",
            orderable: false,
            searchable: false,
            className: "text-center",
            render: (id) => `
          <a href="${labourBaseUrl}/${id}" class="btn btn-sm btn-secondary">
            <i class="fa fa-eye"></i>
          </a>

          <a href="${labourBaseUrl}/${id}/edit" class="btn btn-sm btn-success ms-1">
            <i class="fa fa-edit"></i>
          </a>

          <button class="btn btn-sm btn-danger ms-1 btn-delete" data-id="${id}">
            <i class="fa fa-trash"></i>
          </button>
        `,
          },
        ],

        order: [[0, "desc"]],
        drawCallback: function () {
          const data = d.ProjectManageLabour || [];

          $("#labourTableProject .resource-checkbox").each(function () {
            const el = $(this);
            const id = el.data("id");

            const record = data.find((r) => r.labour_id == id);

            if (record) {
              el.prop("checked", isActive(record.periods));
            } else {
              el.prop("checked", false);
            }
          });
        },
        language: {
          search: "",
          searchPlaceholder: "Search labour…",
          lengthMenu: "Show _MENU_ entries",
          processing:
            '<div class="spinner-border spinner-border-sm text-secondary"></div>',
          emptyTable: "No labour records found.",
          zeroRecords: "No matching records found.",
          info: "Showing _START_ to _END_ of _TOTAL_ records",
          infoEmpty: "No records available",
          infoFiltered: "(filtered from _MAX_ total)",
        },

        initComplete: function () {
          $("#labourTableProject_filter input")
            .addClass("form-control")
            .css("width", "260px");
        },
      });

      // ── DELETE ─────────────────────────────────────────────
      $("#labourTableProject").on("click", ".btn-delete", function () {
        const id = $(this).data("id");

        if (!confirm("Are you sure you want to delete this labour record?"))
          return;

        $.ajax({
          url: `${labourBaseUrl}/${id}`,
          type: "DELETE",
          data: { _token: csrfToken },
          success: () => {
            table.ajax.reload(null, false);
            showToast("Labour deleted successfully.", "success");
          },
          error: () => showToast("Failed to delete labour.", "danger"),
        });
      });
    }

    $("#client_phone_number").on("input", function () {
      this.value = this.value.replace(/[^0-9]/g, "");
    });

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

      if (step === 6) {
        const val = $('input[name="bank_guarantee_required"]:checked').val();

        if (!val) {
          alert("Please select Yes or No");
          ok = false;
        }

        if (val == "1") {
          if (!req($("#practical_completion"), "Required")) ok = false;
          if (!req($("#final_completion"), "Required")) ok = false;

          if (
            $("#practical_completion").val() === "custom" &&
            !req($("#custom_practical_completion"), "Required")
          )
            ok = false;

          if (
            $("#final_completion").val() === "custom" &&
            !req($("#custom_final_completion"), "Required")
          )
            ok = false;
        }
      }

      if (step === 7) {
        let ok = true;

        const val = $('input[name="cash_retentions_required"]:checked').val();

        if (!val) {
          showToast("Please select Yes or No", "warning");
          return false;
        }

        if (val === "1") {
          if (!req($("#cash_practical_completion"), "Required")) ok = false;
          if (!req($("#cash_final_completion"), "Required")) ok = false;

          // Practical custom
          if ($("#cash_practical_completion").val() === "custom") {
            const v = parseFloat($("#custom_cash_practical_completion").val());
            if (!v && v !== 0) {
              showErr($("#custom_cash_practical_completion"), "Required");
              ok = false;
            } else if (v < 0 || v > 100) {
              showErr(
                $("#custom_cash_practical_completion"),
                "Must be between 0–100",
              );
              ok = false;
            }
          }

          // Final custom
          if ($("#cash_final_completion").val() === "custom") {
            const v = parseFloat($("#custom_cash_final_completion").val());
            if (!v && v !== 0) {
              showErr($("#custom_cash_final_completion"), "Required");
              ok = false;
            } else if (v < 0 || v > 100) {
              showErr(
                $("#custom_cash_final_completion"),
                "Must be between 0–100",
              );
              ok = false;
            }
          }
        }

        return ok;
      }

      if (step === 8) {
        const file = $('input[name="pricing_schedule"]').val();

        if (!file && !existingProject?.pricing_file) {
          showToast("Please upload pricing schedule", "warning");
          return false;
        }

        let valid = true;

        $(".assign-margin").each(function () {
          const val = parseFloat($(this).val());

          if (isNaN(val) || val < 0 || val > 100) {
            showErr($(this), "0–100 only");
            valid = false;
          }
        });

        return valid;
      }

      if (step === 9) {
        let valid = true;

        $("#step9 tbody tr").each(function () {
          const marginInput = $(this).find(".margin");
          const descInput = $(this).find(".desc");

          const margin = parseFloat(marginInput.val());
          const desc = descInput.val().trim();

          // margin validation
          if (isNaN(margin) || margin < 0 || margin > 100) {
            showErr(marginInput, "0–100 only");
            valid = false;
          }

          // description validation (optional but recommended)
          if (!desc) {
            showErr(descInput, "Required");
            valid = false;
          }
        });

        return valid;
      }

      if (step === 10) {
        let valid = true;

        $(".code-select").each(function () {
          if (!$(this).val()) {
            showErr($(this), "Required");
            valid = false;
          }
        });

        return valid;
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

  function isActive(periods) {
    if (!periods || !periods.length) return false;
    const last = periods[periods.length - 1];
    return last && last.to === null && last.status === "active";
  }

  function getContractValueExGST() {
    return parseFloat($("#contract_value").val()) || 0;
  }

  function calculateCompletionAmounts() {
    const contractValue = getContractValueExGST();

    let practicalPercent = $("#practical_completion").val();

    if (practicalPercent === "custom") {
      practicalPercent = $("#custom_practical_completion").val();
    }

    practicalPercent = parseFloat(practicalPercent) || 0;

    const practicalAmount = (contractValue * practicalPercent) / 100;
    $("#practical_completion_amount").val(practicalAmount.toFixed(2));

    let finalPercent = $("#final_completion").val();

    if (finalPercent === "custom") {
      finalPercent = $("#custom_final_completion").val();
    }

    finalPercent = parseFloat(finalPercent) || 0;

    const finalAmount = (contractValue * finalPercent) / 100;
    $("#final_completion_amount").val(finalAmount.toFixed(2));
  }

  function calculateCashRetention() {
    const contractValue = parseFloat($("#contract_value").val()) || 0;
    let practical = $("#cash_practical_completion").val();
    if (practical === "custom") {
      practical = $("#custom_cash_practical_completion").val();
    }

    let final = $("#cash_final_completion").val();
    if (final === "custom") {
      final = $("#custom_cash_final_completion").val();
    }

    practical = parseFloat(practical) || 0;
    final = parseFloat(final) || 0;

    $("#cash_practical_completion_amount").val(
      ((contractValue * practical) / 100).toFixed(2),
    );
    $("#cash_final_completion_amount").val(
      ((contractValue * final) / 100).toFixed(2),
    );
  }

  /* ================================================================
     BOOT  — both functions guard themselves with element checks
  ================================================================ */
  document.addEventListener("DOMContentLoaded", function () {
    initProjectTable();
    initMultiStepForm();
  });
})();
