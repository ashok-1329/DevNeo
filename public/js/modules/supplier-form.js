// ─── Supplier Form Validation (create & edit) ─────────────────────────────────

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("supplierForm");
  if (!form) return;

  const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const PHONE = /^[0-9+\-().\s]+$/;
  const ABN = /^\d{11}$/;
  const BSB = /^\d{3}-\d{3}$/;

  const RULES = {
    supplier_category: { required: true, label: "Category" },
    supplier_name: { required: true, maxLen: 255, label: "Business Name" },
    supplier_email: { required: true, email: true, label: "Email" },
    supplier_phone: {
      required: true,
      regex: PHONE,
      regexMsg: "Only digits, +, -, (, ) and spaces allowed",
      label: "Phone",
    },
    supplier_abn: {
      required: true,
      regex: ABN,
      regexMsg: "ABN must be exactly 11 digits",
      label: "ABN",
    },
    supplier_address: { required: true, maxLen: 500, label: "Address" },
    supplier_bank_email: {
      required: true,
      email: true,
      label: "Account Email Address",
    },
    supplier_bank_name: { required: true, maxLen: 255, label: "Bank Name" },
    supplier_bsb_no: {
      required: true,
      regex: BSB,
      regexMsg: "BSB must be in format 000-000",
      label: "BSB No.",
    },
    supplier_account_number: {
      required: true,
      maxLen: 50,
      label: "Account Number",
    },
    supplier_account_name: {
      required: true,
      maxLen: 255,
      label: "Account Name",
    },
    payment_term_id: { required: true, label: "Payment Term" },
  };

  const touched = new Set();

  function validate(name, value) {
    const r = RULES[name];
    if (!r) return null;
    const v = value.trim();
    if (r.required && !v) return `${r.label} is required.`;
    if (v && r.email && !EMAIL.test(v)) return "Enter a valid email address.";
    if (v && r.regex && !r.regex.test(v)) return r.regexMsg;
    if (v && r.maxLen && v.length > r.maxLen)
      return `${r.label} must not exceed ${r.maxLen} characters.`;
    return null;
  }

  function applyState(el, error) {
    // Walk up to find the nearest .col-md-4 wrapper for the feedback element
    const col = el.closest(".col-md-4") || el.closest(".col-12");
    const fb = col ? col.querySelector(".invalid-feedback") : null;
    el.classList.toggle("is-invalid", !!error);
    el.classList.toggle("is-valid", !error && touched.has(el.name));
    if (fb) fb.textContent = error || "";
  }

  function checkField(el) {
    touched.add(el.name);
    const error = validate(el.name, el.value);
    applyState(el, error);
    return !error;
  }

  Object.keys(RULES).forEach((name) => {
    const el = form.elements[name];
    if (!el) return;
    el.addEventListener("blur", () => checkField(el));
    el.addEventListener("change", () => checkField(el));
    el.addEventListener("input", () => {
      if (touched.has(name)) checkField(el);
    });
  });

  // ── Payment Term: auto-fill days display ──────────────────────────────────
  const termSelect = form.elements["payment_term_id"];
  const daysDisplay = document.getElementById("paymentTermDaysDisplay");

  function syncDaysDisplay() {
    if (!daysDisplay || !termSelect) return;
    const selected = termSelect.options[termSelect.selectedIndex];
    if (!selected || !selected.value) {
      daysDisplay.value = "";
      return;
    }
    const days = selected.getAttribute("data-days");
    daysDisplay.value =
      days !== null && days !== "" && days !== "null" ? `${days} days` : "N/A";
  }

  if (termSelect) {
    termSelect.addEventListener("change", syncDaysDisplay);
    // Run immediately so edit page shows current value on load
    syncDaysDisplay();
  }

  // ── BSB auto-format: enforce DDD-DDD as you type ──────────────────────────
  const bsbEl = form.elements["supplier_bsb_no"];
  if (bsbEl) {
    bsbEl.addEventListener("input", function () {
      const cursorPos = this.selectionStart;
      const raw = this.value.replace(/\D/g, "").slice(0, 6);
      const formatted =
        raw.length > 3 ? `${raw.slice(0, 3)}-${raw.slice(3)}` : raw;
      this.value = formatted;
      // Restore sensible cursor position
      const newPos = cursorPos <= 3 ? cursorPos : cursorPos + 1;
      try {
        this.setSelectionRange(newPos, newPos);
      } catch (_) {}
    });

    // Also handle paste
    bsbEl.addEventListener("paste", function (e) {
      e.preventDefault();
      const pasted = (e.clipboardData || window.clipboardData).getData("text");
      const raw = pasted.replace(/\D/g, "").slice(0, 6);
      this.value = raw.length > 3 ? `${raw.slice(0, 3)}-${raw.slice(3)}` : raw;
      if (touched.has("supplier_bsb_no")) checkField(this);
    });
  }

  // ── ABN: digits only, max 11 ──────────────────────────────────────────────
  const abnEl = form.elements["supplier_abn"];
  if (abnEl) {
    abnEl.addEventListener("input", function () {
      this.value = this.value.replace(/\D/g, "").slice(0, 11);
    });
  }

  // ── Form Submit ───────────────────────────────────────────────────────────
  form.addEventListener("submit", (e) => {
    // Push Quill HTML into hidden input before validation
    if (window.quill) {
      const notesHidden = document.getElementById("supplierNotesHidden");
      if (notesHidden) notesHidden.value = quill.root.innerHTML;
    }

    let allValid = true;
    Object.keys(RULES).forEach((name) => {
      const el = form.elements[name];
      if (!el) return;
      touched.add(name);
      const error = validate(name, el.value);
      applyState(el, error);
      if (error) allValid = false;
    });

    if (!allValid) {
      e.preventDefault();
      const firstInvalid = form.querySelector(".is-invalid");
      if (firstInvalid)
        firstInvalid.scrollIntoView({ behavior: "smooth", block: "center" });
    }
  });
});
