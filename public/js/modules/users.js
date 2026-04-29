function initDzComponent(holderId, onUploaded) {
  const holder = document.getElementById(holderId);
  if (!holder) return;

  const inputId = holder.dataset.inputId;
  const zone = document.getElementById("dz_zone_" + inputId);
  const inputEl = document.getElementById(inputId);
  const previewEl = document.getElementById("dz_preview_" + inputId);

  if (!zone || !inputEl) return;

  /* Clone nodes to wipe stale listeners (safe for modal reuse) */
  const newZone = zone.cloneNode(true);
  zone.parentNode.replaceChild(newZone, zone);
  const newInput = inputEl.cloneNode(false);
  inputEl.parentNode.replaceChild(newInput, inputEl);

  const zEl = document.getElementById("dz_zone_" + inputId);
  const iEl = document.getElementById(inputId);
  const pEl = document.getElementById("dz_preview_" + inputId);

  zEl.addEventListener("click", function (e) {
    if (!e.target.closest(".dz-remove-btn, .dz-file-remove")) iEl.click();
  });
  zEl.addEventListener("keydown", function (e) {
    if (e.key === "Enter" || e.key === " ") iEl.click();
  });
  zEl.addEventListener("dragover", function (e) {
    e.preventDefault();
    zEl.classList.add("dz-drag-over");
  });
  zEl.addEventListener("dragleave", function () {
    zEl.classList.remove("dz-drag-over");
  });
  zEl.addEventListener("drop", function (e) {
    e.preventDefault();
    zEl.classList.remove("dz-drag-over");
    if (e.dataTransfer.files.length)
      handleDzFile(e.dataTransfer.files[0], holderId, pEl, zEl, onUploaded);
  });
  iEl.addEventListener("change", function () {
    if (this.files.length)
      handleDzFile(this.files[0], holderId, pEl, zEl, onUploaded);
  });
}

function handleDzFile(file, holderId, pEl, zEl, onUploaded) {
  pEl.innerHTML = "";
  zEl.classList.add("dz-has-file");

  if (file.type.startsWith("image/")) {
    const reader = new FileReader();
    reader.onload = function (e) {
      pEl.innerHTML = `
        <div class="dz-preview-item">
          <div class="dz-img-wrapper">
            <img src="${e.target.result}" alt="preview">
            <div class="dz-img-overlay">
              <button type="button" class="dz-remove-btn" onclick="clearDzById('${holderId}')">
                <i class="fa fa-times me-1"></i> Remove
              </button>
            </div>
          </div>
          <span class="dz-preview-name">${file.name}</span>
        </div>`;
    };
    reader.readAsDataURL(file);
  } else {
    const ext = file.name.split(".").pop().toLowerCase();
    let ic = "fa-file text-secondary";
    if (ext === "pdf") ic = "fa-file-pdf text-danger";
    else if (["doc", "docx"].includes(ext)) ic = "fa-file-word text-primary";

    pEl.innerHTML = `
      <div class="dz-preview-item">
        <div class="dz-file-item">
          <div class="dz-file-icon-wrap">
            <i class="fa ${ic}"></i>
            <button type="button" class="btn btn-danger btn-sm rounded-circle dz-file-remove"
              onclick="clearDzById('${holderId}')"
              style="width:22px;height:22px;padding:0;font-size:10px;position:absolute;top:-8px;right:-8px;">
              <i class="fa fa-times"></i>
            </button>
          </div>
          <span class="dz-preview-name">${file.name}</span>
        </div>
      </div>`;
  }

  uploadFileToServer(file, onUploaded);
}

function clearDzById(holderId) {
  const holder = document.getElementById(holderId);
  if (!holder) return;
  const inputId = holder.dataset.inputId;
  const iEl = document.getElementById(inputId);
  const pEl = document.getElementById("dz_preview_" + inputId);
  const zEl = document.getElementById("dz_zone_" + inputId);
  if (iEl) iEl.value = "";
  if (pEl) pEl.innerHTML = "";
  if (zEl) zEl.classList.remove("dz-has-file");
}

function showExistingDzFile(holderId, filePath) {
  const holder = document.getElementById(holderId);
  if (!holder || !filePath) return;
  const inputId = holder.dataset.inputId;
  const pEl = document.getElementById("dz_preview_" + inputId);
  const zEl = document.getElementById("dz_zone_" + inputId);
  if (!pEl || !zEl) return;

  const fileName = filePath.split("/").pop();
  const ext = fileName.split(".").pop().toLowerCase();
  const isImg = ["jpg", "jpeg", "png", "gif", "webp"].includes(ext);
  const base = typeof storageUrl !== "undefined" ? storageUrl : "/storage";

  zEl.classList.add("dz-has-file");

  if (isImg) {
    pEl.innerHTML = `
      <div class="dz-preview-item">
        <div class="dz-img-wrapper">
          <img src="${base}/${filePath}" alt="existing">
          <div class="dz-img-overlay">
            <button type="button" class="dz-remove-btn" onclick="clearDzById('${holderId}')">
              <i class="fa fa-times me-1"></i> Remove
            </button>
          </div>
        </div>
        <span class="dz-preview-name">${fileName}</span>
      </div>`;
  } else {
    let ic = "fa-file text-secondary";
    if (ext === "pdf") ic = "fa-file-pdf text-danger";
    else if (["doc", "docx"].includes(ext)) ic = "fa-file-word text-primary";

    pEl.innerHTML = `
      <div class="dz-preview-item">
        <div class="dz-file-item">
          <div class="dz-file-icon-wrap">
            <i class="fa ${ic}"></i>
            <button type="button" class="btn btn-danger btn-sm rounded-circle dz-file-remove"
              onclick="clearDzById('${holderId}')"
              style="width:22px;height:22px;padding:0;font-size:10px;position:absolute;top:-8px;right:-8px;">
              <i class="fa fa-times"></i>
            </button>
          </div>
          <span class="dz-preview-name">${fileName}</span>
        </div>
      </div>`;
  }
}

function uploadFileToServer(file, callback) {
  const fd = new FormData();
  fd.append("file", file);
  $.ajax({
    url: typeof fileUploadUrl !== "undefined" ? fileUploadUrl : "/users/upload",
    method: "POST",
    data: fd,
    processData: false,
    contentType: false,
    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
    success: function (res) {
      if (res.success && callback) callback(res.path);
    },
    error: function () {
      showToast("File upload failed", "error");
    },
  });
}

/* ── Toast ──────────────────────────────────────────────────── */
window.showToast = function (message, type = "success") {
  const color = type === "error" ? "danger" : "success";
  const icon = type === "error" ? "fa-times-circle" : "fa-check-circle";
  const id = "toast_" + Date.now();
  $("body").append(`
    <div id="${id}" class="toast align-items-center text-white bg-${color} border-0 position-fixed top-0 end-0 m-3"
         style="z-index:99999" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body"><i class="fa ${icon} me-2"></i>${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>`);
  new bootstrap.Toast(document.getElementById(id), { delay: 3000 }).show();
  setTimeout(() => $("#" + id).remove(), 3600);
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
   DOCUMENT READY
================================================================ */
$(document).ready(function () {
  /* ── DataTable ────────────────────────────────────────────── */
  if ($("#usersTable").length) {
    const dt = $("#usersTable").DataTable({
      processing: true,
      ajax: { url: usersDataUrl, dataSrc: "" },
      columns: [
        {
          data: null,
          orderable: false,
          render: (d, t, r, meta) =>
            meta.row + meta.settings._iDisplayStart + 1,
        },
        {
          data: null,
          render: (d) =>
            escHtml((d.first_name ?? "") + " " + (d.last_name ?? "")),
        },
        { data: "email", render: (d) => escHtml(d) },
        {
          data: "role",
          render: (d) =>
            d
              ? `<span class="badge bg-secondary text-light">${escHtml(d.name)}</span>`
              : "-",
        },
        {
          data: "active_status",
          render: (d) => {
            if (d === 1)
              return '<span class="badge bg-success text-light">Active</span>';
            if (d === 0)
              return '<span class="badge bg-warning text-dark text-light">Inactive</span>';
          },
        },
        {
          data: "id",
          orderable: false,
          render: function (data, type, row) {
            const id = row.id;
            if (!id) return "-";
            return `
              <div class="d-flex gap-1 flex-nowrap">
                <a href="${userEditUrl}/${id}/edit" class="py-2 action-btn btn btn-sm btn-success" title="Edit">
                  <i class="fa fa-pen"></i>
                </a>
                <a href="${userShowUrl}/${id}" class="py-2 action-btn btn btn-sm btn-secondary ms-1" title="View">
                  <i class="fa fa-eye"></i>
                </a>
              </div>`;
          },
        },
      ],
      order: [[0, "desc"]],
    });

    /* ── Delete user ── */
    $(document).on("click", ".btn-del-user", function () {
      const id = $(this).data("id");
      if (!confirm("Are you sure you want to delete this user?")) return;
      $.ajax({
        url: `${userDeleteUrl}/${id}`,
        method: "DELETE",
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: (res) => {
          if (res.success) {
            dt.ajax.reload();
            showToast("User deleted");
          } else {
            showToast(res.message || "Delete failed", "error");
          }
        },
        error: () => showToast("Server error", "error"),
      });
    });
  }

  /* ── Bail out if no step form on this page ── */
  if (!$("#step1").length) return;

  /* ── State ───────────────────────────────────────────────── */
  let userId = null;
  let contractFile = "";
  let certFile = "";
  let editCertId = null;
  let completedSteps = new Set(
    typeof completedStepsData !== "undefined" ? completedStepsData : [],
  );

  // Sync button states on page load based on completedSteps
  $(".step-btn").each(function () {
    const step = parseInt($(this).data("step"));

    if (step === 1) {
      $(this).prop("disabled", false);
      return;
    }

    if (completedSteps.has(step - 1)) {
      $(this).prop("disabled", false);
    } else {
      $(this).prop("disabled", true);
    }
  });

  /* ── Pre-fill Step 1 ─────────────────────────────────────── */
  if (typeof existingUser !== "undefined" && existingUser) {
    userId = existingUser.id;
    $("#first_name").val(existingUser.first_name ?? "");
    $("#last_name").val(existingUser.last_name ?? "");
    $("#email")
      .val(existingUser.email ?? "")
      .prop("readonly", true);
    $("#start_date").val(existingUser.start_date ?? "");
    $("#end_date").val(existingUser.finish_date ?? "");
    completedSteps.add(1);
    markComplete(1);
  }

  /* ── Pre-fill Step 2 – certs ─────────────────────────────── */
  if (
    typeof existingCerts !== "undefined" &&
    existingCerts &&
    existingCerts.length
  ) {
    renderCertTable(existingCerts);
    completedSteps.add(2);
    markComplete(2);
  }

  /* ── Pre-fill Step 3 – contract ──────────────────────────── */
  if (typeof existingContract !== "undefined" && existingContract) {
    prefillContract(existingContract);
    completedSteps.add(3);
    markComplete(3);
  }

  /* ── Pre-fill Step 4 – role ──────────────────────────────── */
  if (typeof existingRole !== "undefined" && existingRole) {
    setTimeout(() => {
      initSelect2();
      $("#roleSelect").val(existingRole).trigger("change");
    }, 300);
    completedSteps.add(4);
    markComplete(4);
  }

  /* ── Init contract dropzone ──────────────────────────────── */
  setTimeout(() => {
    initDzComponent("dz_holder_contractFile", (path) => {
      contractFile = path;
    });
    if (contractFile)
      showExistingDzFile("dz_holder_contractFile", contractFile);
  }, 150);

  /* ── Flatpickr ───────────────────────────────────────────── */
  flatpickr(".datepicker", { dateFormat: "d/m/Y", allowInput: true });

  /* ── Step nav buttons ────────────────────────────────────── */
  syncStepButtons();

  // Click handler - guard disabled steps
  $(".step-btn").on("click", function () {
    if ($(this).prop("disabled")) return;
    const stepNumber = parseInt($(this).data("step"));
    showStep(stepNumber);
  });

  /* ── Next / Prev bindings ────────────────────────────────── */
  $("#first_step").on("click", () => nextStep(1));
  $("#second_step").on("click", () => nextStep(2));
  $("#third_step").on("click", () => nextStep(3));
  $("#last_step").on("click", () => nextStep(4));

  $("#second_prev").on("click", () => showStep(1));
  $("#third_prev").on("click", () => showStep(2));
  $("#last_prev").on("click", () => showStep(3));

  /* ============================================================
     SHOW STEP
  ============================================================ */
  function showStep(n) {
    $(".step").addClass("d-none");
    $("#step" + n).removeClass("d-none");
    if (n === 4) setTimeout(initSelect2, 200);
  }

  function nextStep(step) {
    if (!validateStep(step)) return;

    const fd = new FormData();
    fd.append("step", step);

    if (step === 1) {
      fd.append("first_name", $("#first_name").val().trim());
      fd.append("last_name", $("#last_name").val().trim());
      fd.append("email", $("#email").val().trim());
      fd.append("start_date", $("#start_date").val());
      fd.append("end_date", $("#end_date").val());
      if (userId) fd.append("user_id", userId);
    }

    if (step === 2) {
      fd.append("user_id", userId);
      /* certs already saved individually via modal — just advance */
    }

    if (step === 3) {
      fd.append("user_id", userId);
      fd.append("employment_type", $("#employment_type").val());
      fd.append("hourly_rate", $("#hourly_rate").val());
      fd.append("payment_frequency", $("#payment_frequency").val()); // ✅ only once
      fd.append("timesheet_required", $("#timesheet_required").val());
      fd.append("notes", $("#notes").val());
      fd.append("contract_file", contractFile);
      const staffType = $("[name='staff_type']:checked").val();
      if (staffType) {
        fd.append("staff_type", staffType);
      }
    }
    console.log(fd);
    if (step === 4) {
      fd.append("user_id", userId);
      fd.append("role_id", $("#roleSelect").val());
    }

    $.ajax({
      url: userStepUrl,
      method: "POST",
      data: fd,
      processData: false,
      contentType: false,
      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
      success: function (res) {
        if (!res.success) {
          showToast(res.message || "Something went wrong", "error");
          return;
        }
        if (step === 1) {
          userId = res.user_id;
          $("#email").prop("readonly", true);
        }
        completedSteps.add(step);
        markComplete(step);
        if (step < 4) {
          showStep(step + 1);
        } else {
          showToast("User saved successfully!");
          setTimeout(() => {
            window.location.href = res.redirect;
          }, 900);
        }
      },
      error: function (xhr) {
        const msg = xhr.responseJSON?.message || "Server error occurred";
        showToast(msg, "error");
      },
    });
  }

  /* ── Mark step button complete ─────────────────────────── */
  function markComplete(step) {
    $(`.step-btn[data-step="${step}"]`)
      .removeClass("btn-light btn-secondary")
      .addClass("btn-success");

    // Unlock next step button
    const $nextBtn = $(`.step-btn[data-step="${step + 1}"]`);
    if ($nextBtn.length) {
      $nextBtn.prop("disabled", false);
    }
  }

  /* ── Select2 for role ───────────────────────────────────── */
  function initSelect2() {
    if ($.fn.select2 && !$("#roleSelect").data("select2")) {
      $("#roleSelect").select2({
        placeholder: "Select Access Level",
        width: "100%",
      });
    }
  }

  function syncStepButtons() {
    $(".step-btn").each(function () {
      const step = parseInt($(this).data("step"));

      // Step 1 is always enabled
      if (step === 1) {
        $(this).prop("disabled", false);
        return;
      }

      // Enable only if previous step is completed
      if (completedSteps.has(step - 1)) {
        $(this).prop("disabled", false);
      } else {
        $(this).prop("disabled", true);
      }
    });
  }

  /* ── Pre-fill contract fields ───────────────────────────── */
  function prefillContract(c) {
    if (!c) return;

    $("#employment_type").val(c.employment_type ?? "");
    $("#hourly_rate").val(c.hourly_rate ?? "");
    $("#payment_frequency").val(c.payment_frequency ?? "");
    $("#timesheet_required").val(c.timesheet_required ?? "");
    $("#notes").val(c.notes ?? "");

    contractFile = c.contract_file ?? "";

    // ✅ Staff type (RADIO - single value)
    const staffType = c.staff_type ?? "";
    if (staffType) {
      $("[name='staff_type']").prop("checked", false);
      $(`[name='staff_type'][value='${staffType}']`).prop("checked", true);
    }

    // Show existing contract file in dropzone
    if (contractFile) {
      setTimeout(
        () => showExistingDzFile("dz_holder_contractFile", contractFile),
        200,
      );
    }
  }

  /* ============================================================
     CERT TABLE
  ============================================================ */
  function renderCertTable(certs) {
    const tbody = $("#certTable tbody");
    tbody.empty();
    if (!certs || !certs.length) {
      tbody.html(`<tr id="certTableEmpty">
        <td colspan="5" class="text-center text-muted py-3">
          <i class="fa fa-inbox me-1"></i> No certificates added yet
        </td></tr>`);
      return;
    }
    certs.forEach((c, i) => appendCertRow(c, i + 1));
  }

  function appendCertRow(cert, num) {
    $("#certTableEmpty").remove();
    const n = num ?? $("#certTable tbody tr:not(#certTableEmpty)").length + 1;
    const base = typeof storageUrl !== "undefined" ? storageUrl : "/storage";
    const fileHtml = cert.file
      ? `<a href="${base}/${cert.file}" target="_blank" class="btn btn-sm btn-outline-secondary">
           <i class="fa fa-paperclip me-1"></i>View
         </a>`
      : `<span class="text-muted small">—</span>`;

    $("#certTable tbody").append(`
      <tr id="cert-row-${cert.id}">
        <td>${n}</td>
        <td>${escHtml(cert.title ?? cert.cert_title ?? "")}</td>
        <td>${escHtml(cert.expiry_date ?? "")}</td>
        <td>${fileHtml}</td>
        <td>
          <button class="btn btn-outline-primary btn-sm me-1 btn-edit-cert" data-id="${cert.id}" title="Edit">
            <i class="fa fa-pen"></i>
          </button>
          <button class="btn btn-outline-danger btn-sm btn-del-cert" data-id="${cert.id}" title="Delete">
            <i class="fa fa-trash"></i>
          </button>
        </td>
      </tr>`);
  }

  function renumberCerts() {
    $("#certTable tbody tr:not(#certTableEmpty)").each(function (i) {
      $(this)
        .find("td:first")
        .text(i + 1);
    });
  }

  /* ── Add Cert ────────────────────────────────────────────── */
  $(document).on("click", "#addCertBtn", function () {
    editCertId = null;
    certFile = "";
    $("#certModalLabel").text("Add Certificate");
    $("#save_cert").html('<i class="fa fa-save me-1"></i> Save Certificate');
    $("#certTitle").val("").trigger("change");
    $("#certExpiry").val("");
    $("#otherTitleDiv").addClass("d-none");
    $("#otherTitleInput").val("");
    clearDzById("dz_holder_certFile");
    $("#certModal").modal("show");
  });

  /* Re-init dropzone + flatpickr each modal open */
  $(document).on("shown.bs.modal", "#certModal", function () {
    certFile = "";
    initDzComponent("dz_holder_certFile", (path) => {
      certFile = path;
    });
    if (!$("#certExpiry")[0]._flatpickr) {
      flatpickr("#certExpiry", { dateFormat: "d/m/Y", allowInput: true });
    }
  });

  /* ── Save / Update Cert ─────────────────────────────────── */
  $(document).on("click", "#save_cert", function () {
    saveCert();
  });

  function saveCert() {
    if (!userId) {
      showToast("Please complete Step 1 first", "error");
      return;
    }

    const titleId = $("#certTitle").val();
    const customTitle = $("#otherTitleInput").val().trim();
    const expiry = $("#certExpiry").val();
    const file = $("#certFile").val();

    clearErrors();
    let ok = true;
    if (!titleId) {
      showErr("#certTitle", "Select a certificate title");
      ok = false;
    }
    if (titleId === "other" && !customTitle) {
      showErr("#otherTitleInput", "Enter a custom title");
      ok = false;
    }
    // if (!expiry) {
    //   showErr("#certExpiry", "Enter expiry date");
    //   ok = false;
    // }
    if (!file) {
      showErr("#certFile", "Upload certificate.");
      ok = false;
    }
    if (!ok) return;

    const isEdit = editCertId !== null;
    const url = isEdit ? certUpdateUrl.replace(":id", editCertId) : userStepUrl;

    $.ajax({
      url,
      method: "POST",
      data: {
        _token: $('meta[name="csrf-token"]').attr("content"),
        step: isEdit ? undefined : "cert",
        user_id: userId,
        title_id: titleId,
        custom_title: customTitle,
        expiry_date: expiry,
        file: certFile || "",
      },
      success: function (res) {
        if (!res.success) {
          showToast(res.message || "Error saving certificate", "error");
          return;
        }

        if (isEdit) {
          const c = res.cert;
          const row = $(`#cert-row-${editCertId}`);
          row.find("td:eq(1)").text(c.title ?? customTitle ?? "");
          row.find("td:eq(2)").text(c.expiry_date ?? expiry);
          if (c.file) {
            const base =
              typeof storageUrl !== "undefined" ? storageUrl : "/storage";
            row.find("td:eq(3)").html(
              `<a href="${base}/${c.file}" target="_blank" class="btn btn-sm btn-outline-secondary">
                 <i class="fa fa-paperclip me-1"></i>View
               </a>`,
            );
          }
          showToast("Certificate updated");
        } else {
          const data = res.cert ?? res.data;
          Array.isArray(data)
            ? data.forEach((c) => appendCertRow(c))
            : appendCertRow(data);
          showToast("Certificate added");
        }

        completedSteps.add(2);
        markComplete(2);
        $("#certModal").modal("hide");
      },
      error: () => showToast("Server error", "error"),
    });
  }

  /* ── Edit Cert ───────────────────────────────────────────── */
  $(document).on("click", ".btn-edit-cert", function () {
    const id = $(this).data("id");
    editCertId = id;
    certFile = "";

    $.get(certGetUrl.replace(":id", id), function (res) {
      if (!res.success) {
        showToast("Could not load certificate", "error");
        return;
      }
      const c = res.cert;
      $("#certModalLabel").text("Edit Certificate");
      $("#save_cert").html(
        '<i class="fa fa-save me-1"></i> Update Certificate',
      );
      $("#certTitle")
        .val(c.title_id ?? "")
        .trigger("change");
      if (!c.title_id || c.title_id === "other") {
        $("#otherTitleDiv").removeClass("d-none");
        $("#otherTitleInput").val(c.custom_title ?? "");
      }
      $("#certExpiry").val(c.expiry_date ?? "");
      certFile = c.file ?? "";
      clearDzById("dz_holder_certFile");
      if (certFile)
        setTimeout(
          () => showExistingDzFile("dz_holder_certFile", certFile),
          100,
        );
      $("#certModal").modal("show");
    });
  });

  /* ── Delete Cert ─────────────────────────────────────────── */
  $(document).on("click", ".btn-del-cert", function () {
    const id = $(this).data("id");
    if (!confirm("Delete this certificate?")) return;

    $.ajax({
      url: certDeleteUrl.replace(":id", id),
      method: "DELETE",
      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
      success: function (res) {
        if (res.success) {
          $(`#cert-row-${id}`).remove();
          renumberCerts();
          if (!$("#certTable tbody tr:not(#certTableEmpty)").length)
            renderCertTable([]);
          showToast("Certificate deleted");
        } else {
          showToast(res.message || "Delete failed", "error");
        }
      },
      error: () => showToast("Server error", "error"),
    });
  });

  /* ── Other title toggle ──────────────────────────────────── */
  $(document).on("change", "#certTitle", function () {
    if ($(this).val() === "other") {
      $("#otherTitleDiv").removeClass("d-none");
    } else {
      $("#otherTitleDiv").addClass("d-none");
      $("#otherTitleInput").val("");
    }
  });

  /* ============================================================
     VALIDATION
  ============================================================ */
  function validateStep(step) {
    clearErrors();
    let ok = true;

    if (step === 1) {
      const fn = $("[name=first_name]"),
        ln = $("[name=last_name]"),
        sd = $("[name=start_date]"),
        em = $("[name=email]");
      if (!fn.val().trim()) {
        showErr(fn, "First name is required");
        ok = false;
      }
      if (!ln.val().trim()) {
        showErr(ln, "Last name is required");
        ok = false;
      }
      if (!em.val().trim()) {
        showErr(em, "Email is required");
        ok = false;
      }
      if (!sd.val().trim()) {
        showErr(sd, "Start Date is required");
        ok = false;
      } else if (!/^\S+@\S+\.\S+$/.test(em.val())) {
        showErr(em, "Enter a valid email");
        ok = false;
      }
    }

    if (step === 3) {
      if (!$("#employment_type").val()) {
        showErr("#employment_type", "Employment type is required");
        ok = false;
      }
      if (!$("#hourly_rate").val()) {
        showErr("#hourly_rate", "Hourly rate is required");
        ok = false;
      }
      if (!$("#payment_frequency").val()) {
        showErr("#payment_frequency", "Payment frequency is required");
        ok = false;
      }
      if (!$("#timesheet_required").val()) {
        showErr("#timesheet_required", "This field is required");
        ok = false;
      }
      if (!contractFile || !contractFile.toLowerCase().endsWith(".pdf")) {
        showErr(
          "#contractFile",
          !contractFile
            ? "Please upload a contract file"
            : "Only PDF files are allowed",
        );

        showToast(
          !contractFile
            ? "Please upload a contract file"
            : "Only PDF files are allowed",
          "error",
        );

        ok = false;
      }
    }

    if (step === 4) {
      if (!$("#roleSelect").val()) {
        showToast("Please select an access level", "error");
        ok = false;
      }
    }

    return ok;
  }

  function clearErrors() {
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();
  }

  function showErr(target, msg) {
    const el = typeof target === "string" ? $(target) : target;
    el.addClass("is-invalid");
    if (!el.next(".invalid-feedback").length) {
      el.after(`<div class="invalid-feedback">${msg}</div>`);
    }
  }
});
