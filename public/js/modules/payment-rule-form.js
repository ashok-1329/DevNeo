document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("paymentRuleForm");
  if (!form) return;

  // ── Date constraints: no past dates ────────────────────────────────────────
  const today = new Date().toISOString().split("T")[0];

  const paymentDateEl = form.elements["payment_date"];
  const endDateEl     = form.elements["end_date"];

  if (paymentDateEl) {
    paymentDateEl.setAttribute("min", today);
    // Pre-fill with today only when creating (empty value)
    if (!paymentDateEl.value) paymentDateEl.value = today;
  }

  if (endDateEl) {
    endDateEl.setAttribute("min", today);
  }

  // ── Project number → auto-fill project code ────────────────────────────────
  const projectSelect  = document.getElementById("project_number_select");
  const projectCodeEl  = form.elements["project_code"];
  const projectNumEl   = form.elements["project_number"];

  if (projectSelect) {
    function syncProjectCode() {
      const opt = projectSelect.options[projectSelect.selectedIndex];
      if (opt && opt.value) {
        if (projectNumEl)  projectNumEl.value  = opt.dataset.number || "";
        if (projectCodeEl) projectCodeEl.value = opt.dataset.code   || "";
      } else {
        if (projectNumEl)  projectNumEl.value  = "";
        if (projectCodeEl) projectCodeEl.value = "";
      }
    }

    projectSelect.addEventListener("change", syncProjectCode);

    // On edit: restore the selected option that matches the stored project_number
    const storedNumber = projectNumEl ? projectNumEl.value : "";
    if (storedNumber) {
      for (let i = 0; i < projectSelect.options.length; i++) {
        if (projectSelect.options[i].dataset.number === storedNumber) {
          projectSelect.selectedIndex = i;
          syncProjectCode();
          break;
        }
      }
    }
  }

  // ── Value (inc. GST) — digits + decimals only ──────────────────────────────
  const valueEl = form.elements["value_inc_gst"];
  if (valueEl) {
    valueEl.addEventListener("input", function () {
      // Allow digits, single decimal point, two decimal places
      let v = this.value.replace(/[^0-9.]/g, "");
      const parts = v.split(".");
      if (parts.length > 2) v = parts[0] + "." + parts.slice(1).join("");
      if (parts[1] && parts[1].length > 2) v = parts[0] + "." + parts[1].slice(0, 2);
      this.value = v;
    });
  }

  // ── File upload — drag & drop preview ──────────────────────────────────────
  const dropZone   = document.getElementById("dropZone");
  const fileInput  = document.getElementById("documentInput");
  const previewWrap = document.getElementById("uploadPreview");

  function renderPreview(file) {
    if (!previewWrap) return;
    previewWrap.innerHTML = "";

    if (file.type.startsWith("image/")) {
      const reader = new FileReader();
      reader.onload = (e) => {
        previewWrap.innerHTML = `
          <div class="upload-preview-item">
            <img src="${e.target.result}" alt="preview" style="width: 360px">
            <br>
            <span class="upload-preview-name">${file.name}</span>
          </div>`;
      };
      reader.readAsDataURL(file);
    } else {
      const iconMap = {
        "application/pdf":  "fa-file-pdf text-danger",
        "application/msword": "fa-file-word text-primary",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document": "fa-file-word text-primary",
      };
      const icon = iconMap[file.type] || "fa-file text-secondary";
      previewWrap.innerHTML = `
        <div class="upload-preview-item upload-file-item">
          <i class="fa ${icon} fa-3x mb-2"></i>
          <span class="upload-preview-name">${file.name}</span>
        </div>`;
    }
  }

  if (fileInput) {
    fileInput.addEventListener("change", function () {
      if (this.files[0]) renderPreview(this.files[0]);
    });
  }

  if (dropZone && fileInput) {
    dropZone.addEventListener("click",      () => fileInput.click());
    dropZone.addEventListener("dragover",   (e) => { e.preventDefault(); dropZone.classList.add("drag-over"); });
    dropZone.addEventListener("dragleave",  ()  => dropZone.classList.remove("drag-over"));
    dropZone.addEventListener("drop", (e) => {
      e.preventDefault();
      dropZone.classList.remove("drag-over");
      const file = e.dataTransfer.files[0];
      if (file) {
        // Transfer to file input via DataTransfer
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
        renderPreview(file);
      }
    });
  }

  // ── Validation rules ────────────────────────────────────────────────────────
  const RULES = {
    supplier_name:        { required: true,  label: "Supplier Name" },
    payment_date:         { required: true,  label: "Payment Date",  isDate: true },
    frequency_payment_id: { required: true,  label: "Frequency of Payment" },
    end_date:             { required: true,  label: "End Date",      isDate: true },
    value_inc_gst:        { required: true,  label: "Value (inc. GST)" },
    project_number_select:{ required: true,  label: "Project Number" },
    project_code:         { required: false },   // auto-filled, not user-validated
    payment_description:  { required: true,  label: "Payment Description" },
    documentInput:        { required: !isEditMode(), label: "Supporting Document", isFile: true },
  };

  function isEditMode() {
    // Edit forms contain a hidden _method=PUT input
    return !!form.querySelector('input[name="_method"]');
  }

  const touched = new Set();

  function validate(name, el) {
    const r = RULES[name];
    if (!r) return null;
    const val = el.value ? el.value.trim() : "";

    if (r.required && !val) return `${r.label} is required.`;

    if (r.isDate && val) {
      if (val < today) return `${r.label} cannot be in the past.`;
    }

    if (r.isFile && el.files && el.files.length === 0 && !isEditMode()) {
      return `${r.label} is required.`;
    }

    return null;
  }

  function getEl(name) {
    if (name === "project_number_select") return document.getElementById("project_number_select");
    if (name === "documentInput")         return document.getElementById("documentInput");
    return form.elements[name] || null;
  }

  function applyState(name, el, error) {
    if (!el) return;
    const wrapper = el.closest(".col-md-4") || el.closest(".col-12") || el.closest(".upload-zone-wrapper");
    const fb = wrapper ? wrapper.querySelector(".invalid-feedback") : null;
    el.classList.toggle("is-invalid", !!error);
    el.classList.toggle("is-valid",   !error && touched.has(name));
    if (fb) fb.textContent = error || "";
  }

  function checkField(name) {
    touched.add(name);
    const el = getEl(name);
    const error = validate(name, el);
    applyState(name, el, error);
    return !error;
  }

  Object.keys(RULES).forEach((name) => {
    const el = getEl(name);
    if (!el) return;
    el.addEventListener("blur",   () => checkField(name));
    el.addEventListener("change", () => checkField(name));
    el.addEventListener("input",  () => { if (touched.has(name)) checkField(name); });
  });

  // ── Submit ─────────────────────────────────────────────────────────────────
  form.addEventListener("submit", (e) => {
    let allValid = true;
    Object.keys(RULES).forEach((name) => {
      touched.add(name);
      const el = getEl(name);
      if (!el) return;
      const error = validate(name, el);
      applyState(name, el, error);
      if (error) allValid = false;
    });

    if (!allValid) {
      e.preventDefault();
      const firstInvalid = form.querySelector(".is-invalid");
      if (firstInvalid) firstInvalid.scrollIntoView({ behavior: "smooth", block: "center" });
    }
  });
});