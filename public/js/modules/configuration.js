$(function () {
  /* ─────────────────────────────────────────
     CUSTOM DELETE POPUP (Pure JS/CSS via jQuery)
  ───────────────────────────────────────── */
  // Inject popup HTML + styles once
  if (!$("#custom-delete-modal").length) {
    $("body").append(`
      <div id="custom-delete-modal" style="
        display:none;
        position:fixed;
        inset:0;
        z-index:99999;
        background:rgba(0,0,0,0.75);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        align-items:center;
        justify-content:center;
      ">
        <div style="
          background:#fff;
          border-radius:12px;
          padding:32px 28px 24px;
          max-width:420px;
          width:90%;
          box-shadow:0 8px 32px rgba(0,0,0,0.18);
          text-align:center;
          position:relative;
        ">
          <div style="
            width:64px;
            height:64px;
            border-radius:50%;
            background:#fff3f3;
            display:flex;
            align-items:center;
            justify-content:center;
            margin:0 auto 16px;
          ">
            <svg width="32" height="32" fill="none" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="12" fill="#fee2e2"/>
              <path d="M12 7v6" stroke="#ef4444" stroke-width="2" 
                stroke-linecap="round"/>
              <circle cx="12" cy="17" r="1" fill="#ef4444"/>
            </svg>
          </div>
          <h5 style="
            font-size:1.2rem;
            font-weight:700;
            color:#1a1a2e;
            margin-bottom:8px;
          ">Are you sure?</h5>
          <p style="
            color:#6b7280;
            font-size:0.95rem;
            margin-bottom:24px;
          ">This action <strong>cannot be undone</strong>. 
             The record will be permanently deleted.</p>
          <div style="display:flex;gap:12px;justify-content:center;">
            <button id="delete-cancel-btn" style="
              padding:10px 28px;
              border:1.5px solid #e5e7eb;
              border-radius:8px;
              background:#fff;
              color:#374151;
              font-size:0.95rem;
              font-weight:600;
              cursor:pointer;
              transition:all .2s;
            ">Cancel</button>
            <button id="delete-confirm-btn" style="
              padding:10px 28px;
              border:none;
              border-radius:8px;
              background:#ef4444;
              color:#fff;
              font-size:0.95rem;
              font-weight:600;
              cursor:pointer;
              transition:all .2s;
            ">Yes, Delete it!</button>
          </div>
        </div>
      </div>
    `);
  }

  // Show the custom popup and return a promise-like callback
  function confirmDelete(onConfirm) {
    var $modal = $("#custom-delete-modal");
    $modal.css("display", "flex");

    // Remove old listeners to prevent stacking
    $("#delete-confirm-btn")
      .off("click")
      .on("click", function () {
        $modal.css("display", "none");
        onConfirm();
      });

    $("#delete-cancel-btn")
      .off("click")
      .on("click", function () {
        $modal.css("display", "none");
      });

    // Click outside to close
    $modal.off("click").on("click", function (e) {
      if ($(e.target).is($modal)) {
        $modal.css("display", "none");
      }
    });

    // ESC key to close
    $(document)
      .off("keydown.deleteModal")
      .on("keydown.deleteModal", function (e) {
        if (e.key === "Escape") {
          $modal.css("display", "none");
          $(document).off("keydown.deleteModal");
        }
      });
  }

  /* ─────────────────────────────────────────
     HELPERS
  ───────────────────────────────────────── */
  function showErrors(errors, prefix) {
    prefix = prefix || "";
    $.each(errors, function (field, messages) {
      var $input = $("#" + prefix + field);
      $input.addClass("is-invalid");
      $input.siblings(".invalid-feedback").remove();
      $input.after(
        '<span class="invalid-feedback d-block">' + messages[0] + "</span>",
      );
    });
  }

  function clearErrors($form) {
    $form.find(".is-invalid").removeClass("is-invalid");
    $form.find(".invalid-feedback").remove();
    $form.find(".alert-danger").remove();
  }

  // Always use showToast
  function notify(message, type) {
    type = type || "success";
    showToast(message, type);
  }

  function ajaxRequest(options) {
    options.headers = options.headers || {};
    options.headers["X-CSRF-TOKEN"] = $('meta[name="csrf-token"]').attr(
      "content",
    );
    return $.ajax(options);
  }

  /* ─────────────────────────────────────────
     1. CONTRACT TYPE TABLE
     order: newest first → [[0, "desc"]]
  ───────────────────────────────────────── */
  var contractTable = $("#contract_type_list").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: $("#contract_type_list").data("url"),
      type: "GET",
    },
    columns: [
      { data: "no", orderable: true, searchable: false },
      { data: "value", orderable: true, searchable: true },
      { data: "action", orderable: false, searchable: false },
    ],
    order: [[0, "desc"]], // newest first
    pageLength: 10,
    responsive: true,
    language: { emptyTable: "No Contract Types Found." },
  });

  $(document).on("click", ".add_new_contract_type", function () {
    var $form = $("#contract-type-form");
    clearErrors($form);
    $form[0].reset();
    $("#add-contract-type-modal").modal("show");
  });

  $(document).on("submit", "#contract-type-form", function (e) {
    e.preventDefault();
    var $form = $(this);
    clearErrors($form);
    var $btn = $form
      .find('[type="submit"]')
      .prop("disabled", true)
      .text("Saving...");

    ajaxRequest({
      url: $form.attr("action"),
      method: "POST",
      data: $form.serialize(),
    })
      .done(function (res) {
        if (res.status) {
          notify(res.message);
          $("#add-contract-type-modal").modal("hide");
          contractTable.ajax.reload(null, false);
        }
      })
      .fail(function (xhr) {
        if (xhr.status === 422)
          showErrors(xhr.responseJSON.errors, "contract_");
        else notify("An error occurred. Please try again.", "error");
      })
      .always(function () {
        $btn.prop("disabled", false).text("Submit");
      });
  });

  $(document).on("click", ".btn-edit-contract", function () {
    var id = $(this).data("id");
    var rowData = contractTable.row($(this).closest("tr")).data();

    $("#edit_contract_type_id").val(id);
    $("#edit_contract_type_value").val(rowData ? rowData.value : "");

    var $form = $("#edit-contract-type-form");
    clearErrors($form);
    $form.attr("data-update-url", $(this).data("fetch-url"));
    $("#edit-contract-type-modal").modal("show");
  });

  $(document).on("submit", "#edit-contract-type-form", function (e) {
    e.preventDefault();
    var $form = $(this);
    clearErrors($form);
    var url = $form.attr("data-update-url");
    var $btn = $form
      .find('[type="submit"]')
      .prop("disabled", true)
      .text("Updating...");

    ajaxRequest({
      url: url,
      method: "POST",
      data: $form.serialize() + "&_method=PUT",
    })
      .done(function (res) {
        if (res.status) {
          notify(res.message);
          $("#edit-contract-type-modal").modal("hide");
          contractTable.ajax.reload(null, false);
        }
      })
      .fail(function (xhr) {
        if (xhr.status === 422)
          showErrors(xhr.responseJSON.errors, "edit_contract_");
        else notify("An error occurred. Please try again.", "error");
      })
      .always(function () {
        $btn.prop("disabled", false).text("Update");
      });
  });

  $(document).on("click", ".btn-delete-contract", function () {
    var url = $(this).data("url");
    confirmDelete(function () {
      ajaxRequest({ url: url, method: "POST", data: { _method: "DELETE" } })
        .done(function (res) {
          if (res.status) {
            notify(res.message);
            contractTable.ajax.reload(null, false);
          } else {
            notify(res.message || "Delete failed.", "error");
          }
        })
        .fail(function () {
          notify("Delete failed. Please try again.", "error");
        });
    });
  });

  /* ─────────────────────────────────────────
     2. PAYMENT TERM TABLE
  ───────────────────────────────────────── */
  var paymentTermTable = $("#payment_term_list").DataTable({
    processing: true,
    serverSide: true,
    ajax: { url: $("#payment_term_list").data("url"), type: "GET" },
    columns: [
      { data: "no", orderable: true, searchable: false },
      { data: "name", orderable: true, searchable: true },
      { data: "days", orderable: true, searchable: true },
      { data: "is_active", orderable: true, searchable: false },
      { data: "action", orderable: false, searchable: false },
    ],
    order: [[0, "desc"]], // newest first
    pageLength: 10,
    responsive: true,
    language: { emptyTable: "No Payment Terms Found." },
  });

  $(document).on("click", ".add_new_payment_term", function () {
    var $form = $("#payment-term-form");
    clearErrors($form);
    $form[0].reset();
    $("#add-payment-term-modal").modal("show");
  });

  $(document).on("submit", "#payment-term-form", function (e) {
    e.preventDefault();
    var $form = $(this);
    clearErrors($form);
    var $btn = $form
      .find('[type="submit"]')
      .prop("disabled", true)
      .text("Saving...");

    ajaxRequest({
      url: $form.attr("action"),
      method: "POST",
      data: $form.serialize(),
    })
      .done(function (res) {
        if (res.status) {
          notify(res.message);
          $("#add-payment-term-modal").modal("hide");
          paymentTermTable.ajax.reload(null, false);
        }
      })
      .fail(function (xhr) {
        if (xhr.status === 422) showErrors(xhr.responseJSON.errors, "pt_");
        else notify("An error occurred.", "error");
      })
      .always(function () {
        $btn.prop("disabled", false).text("Submit");
      });
  });

  $(document).on("click", ".btn-edit-payment-term", function () {
    var rowData = paymentTermTable.row($(this).closest("tr")).data();

    $("#edit_pt_name").val(rowData ? rowData.name : "");
    $("#edit_pt_days").val(rowData ? rowData.days : "");

    var isActive =
      rowData &&
      rowData.is_active &&
      rowData.is_active.indexOf("Active") > -1 &&
      rowData.is_active.indexOf("Inactive") === -1;
    $("#edit_pt_is_active").prop("checked", isActive);

    var $form = $("#edit-payment-term-form");
    clearErrors($form);
    $form.attr("data-update-url", $(this).data("url"));
    $("#edit-payment-term-modal").modal("show");
  });

  $(document).on("submit", "#edit-payment-term-form", function (e) {
    e.preventDefault();
    var $form = $(this);
    clearErrors($form);
    var $btn = $form
      .find('[type="submit"]')
      .prop("disabled", true)
      .text("Updating...");

    ajaxRequest({
      url: $form.attr("data-update-url"),
      method: "POST",
      data: $form.serialize() + "&_method=PUT",
    })
      .done(function (res) {
        if (res.status) {
          notify(res.message);
          $("#edit-payment-term-modal").modal("hide");
          paymentTermTable.ajax.reload(null, false);
        }
      })
      .fail(function (xhr) {
        if (xhr.status === 422) showErrors(xhr.responseJSON.errors, "edit_pt_");
        else notify("An error occurred.", "error");
      })
      .always(function () {
        $btn.prop("disabled", false).text("Update");
      });
  });

  $(document).on("click", ".btn-delete-payment-term", function () {
    var url = $(this).data("url");
    confirmDelete(function () {
      ajaxRequest({ url: url, method: "POST", data: { _method: "DELETE" } })
        .done(function (res) {
          if (res.status) {
            notify(res.message);
            paymentTermTable.ajax.reload(null, false);
          } else {
            notify(res.message || "Delete failed.", "error");
          }
        })
        .fail(function () {
          notify("Delete failed.", "error");
        });
    });
  });

  /* ─────────────────────────────────────────
     3. CODE CATEGORY TABLE
  ───────────────────────────────────────── */
  var codeCategoryTable = $("#code_category_list").DataTable({
    processing: true,
    serverSide: true,
    ajax: { url: $("#code_category_list").data("url"), type: "GET" },
    columns: [
      { data: "no", orderable: true, searchable: false },
      { data: "name", orderable: true, searchable: true },
      { data: "code_name", orderable: true, searchable: true },
      { data: "assign_margin", orderable: true, searchable: true },
      { data: "status", orderable: true, searchable: false },
      { data: "action", orderable: false, searchable: false },
    ],
    order: [[0, "desc"]], // newest first
    pageLength: 10,
    responsive: true,
    language: { emptyTable: "No Code Categories Found." },
  });

  $(document).on("click", ".add_code_category", function () {
    var $form = $("#add-code-category-form");
    clearErrors($form);
    $form[0].reset();
    $("#add-code-category-modal").modal("show");
  });

  $(document).on("submit", "#add-code-category-form", function (e) {
    e.preventDefault();
    var $form = $(this);
    clearErrors($form);
    var $btn = $form
      .find('[type="submit"]')
      .prop("disabled", true)
      .text("Saving...");

    ajaxRequest({
      url: $form.attr("action"),
      method: "POST",
      data: $form.serialize(),
    })
      .done(function (res) {
        if (res.status) {
          notify(res.message);
          $("#add-code-category-modal").modal("hide");
          codeCategoryTable.ajax.reload(null, false);
        }
      })
      .fail(function (xhr) {
        if (xhr.status === 422) showErrors(xhr.responseJSON.errors, "cc_");
        else notify("An error occurred.", "error");
      })
      .always(function () {
        $btn.prop("disabled", false).text("Submit");
      });
  });

  $(document).on("click", ".btn-edit-code-category", function () {
    var rowData = codeCategoryTable.row($(this).closest("tr")).data();

    if (rowData) {
      $("#edit_cc_name").val(rowData.name);
      $("#edit_cc_code_name").val(rowData.code_name);
      var margin = rowData.assign_margin
        ? rowData.assign_margin.replace("%", "").trim()
        : "";
      $("#edit_cc_assign_margin").val(margin === "—" ? "" : margin);
      var isActive =
        rowData.status &&
        rowData.status.indexOf("Active") > -1 &&
        rowData.status.indexOf("Inactive") === -1;
      $("#edit_cc_status").val(isActive ? "1" : "0");
    }

    var $form = $("#edit-code-category-form");
    clearErrors($form);
    $form.attr("data-update-url", $(this).data("url"));
    $("#edit-code-category-modal").modal("show");
  });

  $(document).on("submit", "#edit-code-category-form", function (e) {
    e.preventDefault();
    var $form = $(this);
    clearErrors($form);
    var $btn = $form
      .find('[type="submit"]')
      .prop("disabled", true)
      .text("Updating...");

    ajaxRequest({
      url: $form.attr("data-update-url"),
      method: "POST",
      data: $form.serialize() + "&_method=PUT",
    })
      .done(function (res) {
        if (res.status) {
          notify(res.message);
          $("#edit-code-category-modal").modal("hide");
          codeCategoryTable.ajax.reload(null, false);
        }
      })
      .fail(function (xhr) {
        if (xhr.status === 422) showErrors(xhr.responseJSON.errors, "edit_cc_");
        else notify("An error occurred.", "error");
      })
      .always(function () {
        $btn.prop("disabled", false).text("Update");
      });
  });

  $(document).on("click", ".btn-delete-code-category", function () {
    var url = $(this).data("url");
    confirmDelete(function () {
      ajaxRequest({ url: url, method: "POST", data: { _method: "DELETE" } })
        .done(function (res) {
          if (res.status) {
            notify(res.message);
            codeCategoryTable.ajax.reload(null, false);
          } else {
            notify(res.message || "Delete failed.", "error");
          }
        })
        .fail(function () {
          notify("Delete failed.", "error");
        });
    });
  });

  /* ─────────────────────────────────────────
     4. PLANT TYPE TABLE
  ───────────────────────────────────────── */
  var plantTypeTable = $("#plant_type_list").DataTable({
    processing: true,
    serverSide: true,
    ajax: { url: $("#plant_type_list").data("url"), type: "GET" },
    columns: [
      { data: "no", orderable: true, searchable: false },
      { data: "name", orderable: true, searchable: true },
      { data: "status", orderable: true, searchable: false },
      { data: "action", orderable: false, searchable: false },
    ],
    order: [[0, "desc"]], // newest first
    pageLength: 10,
    responsive: true,
    language: { emptyTable: "No Plant Types Found." },
  });

  $(document).on("click", ".add_plant_type", function () {
    var $form = $("#plant-type-form");
    clearErrors($form);
    $form[0].reset();
    $("#plant-type-modal").modal("show");
  });

  $(document).on("submit", "#plant-type-form", function (e) {
    e.preventDefault();
    var $form = $(this);
    clearErrors($form);
    var $btn = $form
      .find('[type="submit"]')
      .prop("disabled", true)
      .text("Saving...");

    ajaxRequest({
      url: $form.attr("action"),
      method: "POST",
      data: $form.serialize(),
    })
      .done(function (res) {
        if (res.status) {
          notify(res.message);
          $("#plant-type-modal").modal("hide");
          plantTypeTable.ajax.reload(null, false);
        }
      })
      .fail(function (xhr) {
        if (xhr.status === 422) showErrors(xhr.responseJSON.errors, "plt_");
        else notify("An error occurred.", "error");
      })
      .always(function () {
        $btn.prop("disabled", false).text("Submit");
      });
  });

  $(document).on("click", ".btn-edit-plant-type", function () {
    var rowData = plantTypeTable.row($(this).closest("tr")).data();

    if (rowData) {
      $("#edit_plt_name").val(rowData.name);
      var isActive =
        rowData.status &&
        rowData.status.indexOf("Active") > -1 &&
        rowData.status.indexOf("Inactive") === -1;
      $("#edit_plt_status").val(isActive ? "1" : "0");
    }

    var $form = $("#edit-plant-type-form");
    clearErrors($form);
    $form.attr("data-update-url", $(this).data("url"));
    $("#edit-plant-type-modal").modal("show");
  });

  $(document).on("submit", "#edit-plant-type-form", function (e) {
    e.preventDefault();
    var $form = $(this);
    clearErrors($form);
    var $btn = $form
      .find('[type="submit"]')
      .prop("disabled", true)
      .text("Updating...");

    ajaxRequest({
      url: $form.attr("data-update-url"),
      method: "POST",
      data: $form.serialize() + "&_method=PUT",
    })
      .done(function (res) {
        if (res.status) {
          notify(res.message);
          $("#edit-plant-type-modal").modal("hide");
          plantTypeTable.ajax.reload(null, false);
          plantCapacityTable.ajax.reload(null, false);
        }
      })
      .fail(function (xhr) {
        if (xhr.status === 422)
          showErrors(xhr.responseJSON.errors, "edit_plt_");
        else notify("An error occurred.", "error");
      })
      .always(function () {
        $btn.prop("disabled", false).text("Update");
      });
  });

  $(document).on("click", ".btn-delete-plant-type", function () {
    var url = $(this).data("url");
    confirmDelete(function () {
      ajaxRequest({ url: url, method: "POST", data: { _method: "DELETE" } })
        .done(function (res) {
          if (res.status) {
            notify(res.message);
            plantTypeTable.ajax.reload(null, false);
          } else {
            notify(res.message || "Delete failed.", "error");
          }
        })
        .fail(function (xhr) {
          var msg =
            xhr.responseJSON && xhr.responseJSON.message
              ? xhr.responseJSON.message
              : "Delete failed.";
          notify(msg, "error");
        });
    });
  });

  /* ─────────────────────────────────────────
     5. PLANT CAPACITY TABLE
  ───────────────────────────────────────── */
  var plantCapacityTable = $("#plant_capacity_list").DataTable({
    processing: true,
    serverSide: true,
    ajax: { url: $("#plant_capacity_list").data("url"), type: "GET" },
    columns: [
      { data: "no", orderable: true, searchable: false },
      { data: "name", orderable: true, searchable: true },
      { data: "plant_type", orderable: false, searchable: true },
      { data: "status", orderable: true, searchable: false },
      { data: "action", orderable: false, searchable: false },
    ],
    order: [[0, "desc"]], // newest first
    pageLength: 10,
    responsive: true,
    language: { emptyTable: "No Plant Capacities Found." },
  });

  $(document).on("click", ".add_plant_capacity", function () {
    var $form = $("#plant-capacity-form");
    clearErrors($form);
    $form[0].reset();
    $("#plant-capacity-modal").modal("show");
  });

  $(document).on("submit", "#plant-capacity-form", function (e) {
    e.preventDefault();
    var $form = $(this);
    clearErrors($form);
    var $btn = $form
      .find('[type="submit"]')
      .prop("disabled", true)
      .text("Saving...");

    ajaxRequest({
      url: $form.attr("action"),
      method: "POST",
      data: $form.serialize(),
    })
      .done(function (res) {
        if (res.status) {
          notify(res.message);
          $("#plant-capacity-modal").modal("hide");
          plantCapacityTable.ajax.reload(null, false);
        }
      })
      .fail(function (xhr) {
        if (xhr.status === 422) showErrors(xhr.responseJSON.errors, "pc_");
        else notify("An error occurred.", "error");
      })
      .always(function () {
        $btn.prop("disabled", false).text("Submit");
      });
  });

  $(document).on("click", ".btn-edit-plant-capacity", function () {
    var id = $(this).data("id");
    var url = $(this).data("url");
    var rowData = plantCapacityTable.row($(this).closest("tr")).data();

    if (rowData) {
      $("#edit_pc_name").val(rowData.name);
      var isActive =
        rowData.status &&
        rowData.status.indexOf("Active") > -1 &&
        rowData.status.indexOf("Inactive") === -1;
      $("#edit_pc_status").val(isActive ? "1" : "0");
    }

    $.get(url.replace("/update", "") + "/" + id + "/edit-data", function (res) {
      if (res.data) {
        $("#edit_pc_plant_type_id").val(res.data.plant_type_id);
      }
    }).fail(function () {
      var plantTypeName = rowData ? rowData.plant_type : "";
      $("#edit_pc_plant_type_id option")
        .filter(function () {
          return $(this).text().trim() === plantTypeName;
        })
        .prop("selected", true);
    });

    var $form = $("#edit-plant-capacity-form");
    clearErrors($form);
    $form.attr("data-update-url", url);
    $("#edit-plant-capacity-modal").modal("show");
  });

  $(document).on("submit", "#edit-plant-capacity-form", function (e) {
    e.preventDefault();
    var $form = $(this);
    clearErrors($form);
    var $btn = $form
      .find('[type="submit"]')
      .prop("disabled", true)
      .text("Updating...");

    ajaxRequest({
      url: $form.attr("data-update-url"),
      method: "POST",
      data: $form.serialize() + "&_method=PUT",
    })
      .done(function (res) {
        if (res.status) {
          notify(res.message);
          $("#edit-plant-capacity-modal").modal("hide");
          plantCapacityTable.ajax.reload(null, false);
        }
      })
      .fail(function (xhr) {
        if (xhr.status === 422) showErrors(xhr.responseJSON.errors, "edit_pc_");
        else notify("An error occurred.", "error");
      })
      .always(function () {
        $btn.prop("disabled", false).text("Update");
      });
  });

  $(document).on("click", ".btn-delete-plant-capacity", function () {
    var url = $(this).data("url");
    confirmDelete(function () {
      ajaxRequest({ url: url, method: "POST", data: { _method: "DELETE" } })
        .done(function (res) {
          if (res.status) {
            notify(res.message);
            plantCapacityTable.ajax.reload(null, false);
          } else {
            notify(res.message || "Delete failed.", "error");
          }
        })
        .fail(function () {
          notify("Delete failed.", "error");
        });
    });
  });

  /* ─────────────────────────────────────────
     6. PROJECT REGION TABLE
  ───────────────────────────────────────── */
  var projectRegionTable = $("#project_region_list").DataTable({
    processing: true,
    serverSide: true,
    ajax: { url: $("#project_region_list").data("url"), type: "GET" },
    columns: [
      { data: "no", orderable: true, searchable: false },
      { data: "name", orderable: true, searchable: true },
      { data: "status", orderable: true, searchable: false },
      { data: "action", orderable: false, searchable: false },
    ],
    order: [[0, "desc"]], // newest first
    pageLength: 10,
    responsive: true,
    language: { emptyTable: "No Project Regions Found." },
  });

  $(document).on("click", ".add_project_region", function () {
    var $form = $("#project-region-form");
    clearErrors($form);
    $form[0].reset();
    $("#add-project-region-modal").modal("show");
  });

  $(document).on("submit", "#project-region-form", function (e) {
    e.preventDefault();
    var $form = $(this);
    clearErrors($form);
    var $btn = $form
      .find('[type="submit"]')
      .prop("disabled", true)
      .text("Saving...");

    ajaxRequest({
      url: $form.attr("action"),
      method: "POST",
      data: $form.serialize(),
    })
      .done(function (res) {
        if (res.status) {
          notify(res.message);
          $("#add-project-region-modal").modal("hide");
          projectRegionTable.ajax.reload(null, false);
        }
      })
      .fail(function (xhr) {
        if (xhr.status === 422) showErrors(xhr.responseJSON.errors, "pr_");
        else notify("An error occurred.", "error");
      })
      .always(function () {
        $btn.prop("disabled", false).text("Submit");
      });
  });

  $(document).on("click", ".btn-edit-project-region", function () {
    var rowData = projectRegionTable.row($(this).closest("tr")).data();

    if (rowData) {
      $("#edit_pr_name").val(rowData.name);
      var isActive =
        rowData.status &&
        rowData.status.indexOf("Active") > -1 &&
        rowData.status.indexOf("Inactive") === -1;
      $("#edit_pr_status").val(isActive ? "1" : "0");
    }

    var $form = $("#edit-project-region-form");
    clearErrors($form);
    $form.attr("data-update-url", $(this).data("url"));
    $("#edit-project-region-modal").modal("show");
  });

  $(document).on("submit", "#edit-project-region-form", function (e) {
    e.preventDefault();
    var $form = $(this);
    clearErrors($form);
    var $btn = $form
      .find('[type="submit"]')
      .prop("disabled", true)
      .text("Updating...");

    ajaxRequest({
      url: $form.attr("data-update-url"),
      method: "POST",
      data: $form.serialize() + "&_method=PUT",
    })
      .done(function (res) {
        if (res.status) {
          notify(res.message);
          $("#edit-project-region-modal").modal("hide");
          projectRegionTable.ajax.reload(null, false);
        }
      })
      .fail(function (xhr) {
        if (xhr.status === 422) showErrors(xhr.responseJSON.errors, "edit_pr_");
        else notify("An error occurred.", "error");
      })
      .always(function () {
        $btn.prop("disabled", false).text("Update");
      });
  });

  $(document).on("click", ".btn-delete-project-region", function () {
    var url = $(this).data("url");
    confirmDelete(function () {
      ajaxRequest({ url: url, method: "POST", data: { _method: "DELETE" } })
        .done(function (res) {
          if (res.status) {
            notify(res.message);
            projectRegionTable.ajax.reload(null, false);
          } else {
            notify(res.message || "Delete failed.", "error");
          }
        })
        .fail(function () {
          notify("Delete failed.", "error");
        });
    });
  });

  /* ─────────────────────────────────────────
     TAB PERSISTENCE
  ───────────────────────────────────────── */
  var activeTab = localStorage.getItem("configActiveTab");
  if (activeTab) {
    $('a[href="' + activeTab + '"]').tab("show");
  }
  $('a[data-bs-toggle="tab"]').on("shown.bs.tab", function (e) {
    localStorage.setItem("configActiveTab", $(e.target).attr("href"));
  });
});
