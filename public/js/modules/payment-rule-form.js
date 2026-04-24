/**
 * payment-rule-form.js
 * ──────────────────────────────────────────────────────────────────
 * Handles validation for both Add and Edit Payment Rule forms.
 *
 * File-upload behaviour is delegated entirely to DropzoneComponent
 * (dropzone-component.js).  This script only performs field-level
 * validation and listens to the dz:change / dz:clear events emitted
 * by the dropzone wrapper.
 * ──────────────────────────────────────────────────────────────────
 */

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("paymentRuleForm");
  if (!form) return;

  /* ─── Helpers ──────────────────────────────────────────────── */

  function isEditMode() {
    return !!form.querySelector('input[name="_method"]');
  }

  /** Get today as YYYY-MM-DD in local time */
  function todayISO() {
    const d = new Date();
    return [
      d.getFullYear(),
      String(d.getMonth() + 1).padStart(2, "0"),
      String(d.getDate()).padStart(2, "0"),
    ].join("-");
  }

  const today = todayISO();

  /** Parse dd/mm/yyyy → YYYY-MM-DD for comparison */
  function parseDisplayDate(val) {
    if (!val) return "";
    const parts = val.split("/");
    if (parts.length !== 3) return "";
    return `${parts[2]}-${parts[1]}-${parts[0]}`;
  }

  /* ─── Project number ↔ project code sync ───────────────────── */

  const projectSelect = document.getElementById("project_number_select");
  const projectCodeEl = form.elements["project_code"];
  const projectNumEl = form.elements["project_number"];

  if (projectSelect) {
    function syncProjectCode() {
      const opt = projectSelect.options[projectSelect.selectedIndex];
      if (opt && opt.value) {
        if (projectNumEl) projectNumEl.value = opt.dataset.number || "";
        if (projectCodeEl) projectCodeEl.value = opt.dataset.code || "";
      } else {
        if (projectNumEl) projectNumEl.value = "";
        if (projectCodeEl) projectCodeEl.value = "";
      }
    }
    projectSelect.addEventListener("change", syncProjectCode);

    // Restore selection on edit
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

  /* ─── Value (inc. GST) — digits + up-to-2 decimals ────────── */

  const valueEl = form.elements["value_inc_gst"];
  if (valueEl) {
    valueEl.addEventListener("input", function () {
      let v = this.value.replace(/[^0-9.]/g, "");
      const parts = v.split(".");
      if (parts.length > 2) v = parts[0] + "." + parts.slice(1).join("");
      if (parts[1] && parts[1].length > 2)
        v = parts[0] + "." + parts[1].slice(0, 2);
      this.value = v;
    });
  }

  /* ─── Validation rules ─────────────────────────────────────── */

  /*
   * documentRequired:
   *   – Create mode  → required
   *   – Edit mode    → optional (keep existing if blank)
   */
  const documentRequired = !isEditMode();

  const RULES = {
    supplier_name: {
      required: true,
      label: "Supplier Name",
    },
    payment_date: {
      required: true,
      label: "Payment Date",
      isDisplayDate: true,
    },
    frequency_payment_id: {
      required: true,
      label: "Frequency of Payment",
    },
    end_date: {
      required: true,
      label: "End Date",
      isDisplayDate: true,
    },
    value_inc_gst: {
      required: true,
      label: "Value (inc. GST)",
    },
    project_number_select: {
      required: true,
      label: "Project Number",
    },
    // project_code is auto-filled → not validated
    payment_description: {
      required: true,
      label: "Payment Description",
    },
    _document: {
      required: documentRequired,
      label: "Supporting Document",
      isFile: true,
    },
  };

  /* ─── Element resolution ───────────────────────────────────── */

  function getEl(name) {
    if (name === "project_number_select")
      return document.getElementById("project_number_select");
    if (name === "_document")
      return form.querySelector('input[type="file"][name="document"]');
    return form.elements[name] || null;
  }

  /* ─── Per-field validate ───────────────────────────────────── */

  function validate(name, el) {
    if (!el) return null;
    const rule = RULES[name];
    if (!rule) return null;

    const val = (el.value || "").trim();

    // Required check
    if (rule.required) {
      if (rule.isFile) {
        if (!el.files || el.files.length === 0) {
          return `${rule.label} is required.`;
        }
        return null;
      }
      if (!val) return `${rule.label} is required.`;
    }

    // Date validation (display format dd/mm/yyyy)
    if (rule.isDisplayDate && val) {
      const iso = parseDisplayDate(val);
      if (iso && iso < today) {
        return `${rule.label} cannot be in the past.`;
      }
    }

    return null;
  }

  /* ─── Apply / clear invalid state ─────────────────────────── */

  function getWrapper(el) {
    return (
      el.closest(".col-md-4") ||
      el.closest(".col-12") ||
      el.closest(".dz-component") ||
      null
    );
  }

  function applyState(name, el, error) {
    if (!el) return;

    // For file inputs the dz-component handles its own feedback
    if (name === "_document") {
      const dzWrapper = el.closest(".dz-component");
      if (dzWrapper) {
        const fb = dzWrapper.querySelector(".dz-feedback");
        const zone = dzWrapper.querySelector(".dz-drop-zone");
        if (fb) {
          fb.textContent = error || "";
          fb.classList.toggle("dz-feedback-visible", !!error);
        }
        if (zone) zone.classList.toggle("dz-border-danger", !!error);
      }
      return;
    }

    el.classList.toggle("is-invalid", !!error);
    const wrapper = getWrapper(el);
    if (wrapper) {
      const fb = wrapper.querySelector(".invalid-feedback");
      if (fb) fb.textContent = error || "";
    }
  }

  /* ─── Touched tracking ─────────────────────────────────────── */

  const touched = new Set();

  function checkField(name) {
    touched.add(name);
    const el = getEl(name);
    const error = validate(name, el);
    applyState(name, el, error);
    return !error;
  }

  // Attach blur/change/input listeners
  Object.keys(RULES).forEach((name) => {
    const el = getEl(name);
    if (!el) return;
    el.addEventListener("blur", () => checkField(name));
    el.addEventListener("change", () => checkField(name));
    el.addEventListener("input", () => {
      if (touched.has(name)) checkField(name);
    });
  });

  /* ─── Dropzone events (re-validate on file add/remove) ────── */

  const dzWrapper = form.querySelector(".dz-component");
  if (dzWrapper) {
    dzWrapper.addEventListener("dz:change", () => {
      touched.add("_document");
      checkField("_document");
    });
    dzWrapper.addEventListener("dz:clear", () => {
      if (touched.has("_document")) checkField("_document");
    });
  }

  /* ─── Submit ───────────────────────────────────────────────── */

  form.addEventListener("submit", (e) => {
    let allValid = true;

    Object.keys(RULES).forEach((name) => {
      touched.add(name);
      const el = getEl(name);
      const error = validate(name, el);
      applyState(name, el, error);
      if (error) allValid = false;
    });

    if (!allValid) {
      e.preventDefault();
      const firstInvalid =
        form.querySelector(".is-invalid") ||
        form.querySelector(".dz-border-danger");
      if (firstInvalid) {
        firstInvalid.scrollIntoView({ behavior: "smooth", block: "center" });
      }
    }
  });
});
