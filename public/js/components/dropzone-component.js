class DropzoneComponent {
  /** @param {HTMLElement} wrapper – the `.dz-component` div */
  constructor(wrapper) {
    this.wrapper     = wrapper;
    this.type        = wrapper.dataset.type;          // 'image' | 'document'
    this.inputId     = wrapper.dataset.inputId;
    this.input       = document.getElementById(this.inputId);
    this.zone        = wrapper.querySelector('.dz-drop-zone');
    this.previewArea = wrapper.querySelector('.dz-preview-area');
    this.placeholder = wrapper.querySelector('.dz-placeholder');
    this.feedback    = wrapper.querySelector('.dz-feedback');

    this._mimeMap = {
      image: [
        'image/jpeg', 'image/jpg', 'image/png',
        'image/gif',  'image/webp',
      ],
      document: [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg', 'image/jpg', 'image/png',
      ],
    };

    if (!this.input || !this.zone) return;
    this._bindEvents();
  }

  getFile() {
    return this.input.files && this.input.files[0] ? this.input.files[0] : null;
  }

  clear() {
    this._clearPreview();
    this._dispatch('dz:clear', {});
  }

  _bindEvents() {
    // Click → open file dialog
    this.zone.addEventListener('click', (e) => {
      // Prevent double-trigger if remove button clicked
      if (e.target.closest('.dz-remove-btn')) return;
      this.input.click();
    });

    // Keyboard accessibility
    this.zone.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        this.input.click();
      }
    });

    // Drag over
    this.zone.addEventListener('dragover', (e) => {
      e.preventDefault();
      this.zone.classList.add('dz-drag-over');
    });

    // Drag leave
    this.zone.addEventListener('dragleave', (e) => {
      if (!this.zone.contains(e.relatedTarget)) {
        this.zone.classList.remove('dz-drag-over');
      }
    });

    // Drop
    this.zone.addEventListener('drop', (e) => {
      e.preventDefault();
      this.zone.classList.remove('dz-drag-over');
      const file = e.dataTransfer && e.dataTransfer.files[0];
      if (file) this._handleFile(file);
    });

    // Change on hidden input
    this.input.addEventListener('change', () => {
      if (this.input.files && this.input.files[0]) {
        this._handleFile(this.input.files[0]);
      }
    });
  }

  /* ─── File handling ──────────────────────────────────────── */

  _handleFile(file) {
    const allowed = this._mimeMap[this.type] || this._mimeMap.document;

    // Validate MIME
    if (!allowed.includes(file.type)) {
      this._showError(
        this.type === 'image'
          ? 'Please upload an image file (JPG, PNG, GIF, or WEBP).'
          : 'Please upload a PDF, Word document, or image file.'
      );
      return;
    }

    // Validate size (10 MB)
    if (file.size > 10 * 1024 * 1024) {
      this._showError('File exceeds the 10 MB limit.');
      return;
    }

    this._clearError();

    // Transfer file into the real input via DataTransfer
    const dt = new DataTransfer();
    dt.items.add(file);
    this.input.files = dt.files;

    this._renderPreview(file);
    this._dispatch('dz:change', { file });
  }

  /* ─── Preview rendering ──────────────────────────────────── */

  _renderPreview(file) {
    this.previewArea.innerHTML = '';

    if (file.type.startsWith('image/')) {
      this._renderImagePreview(file);
    } else {
      this._renderFilePreview(file);
    }

    this.zone.classList.add('dz-has-file');
  }

  /** Image preview with hover overlay + remove button */
  _renderImagePreview(file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      const item = document.createElement('div');
      item.className = 'dz-preview-item';
      item.innerHTML = `
        <div class="dz-img-wrapper">
          <img src="${e.target.result}" alt="preview">
          <div class="dz-img-overlay">
            <button type="button" class="dz-remove-btn" title="Remove file">
              REMOVE
            </button>
          </div>
        </div>
        <span class="dz-preview-name" title="${this._escHtml(file.name)}">${this._escHtml(file.name)}</span>
      `;
      this.previewArea.appendChild(item);
      this._bindRemoveBtn(item);
    };
    reader.readAsDataURL(file);
  }

  /** Document / file icon preview with remove button */
  _renderFilePreview(file) {
    const iconClass = {
      'application/pdf': 'fa-file-pdf text-danger',
      'application/msword': 'fa-file-word text-primary',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'fa-file-word text-primary',
    }[file.type] || 'fa-file text-secondary';

    const item = document.createElement('div');
    item.className = 'dz-file-item';
    item.innerHTML = `
      <div class="dz-file-icon-wrap">
        <i class="fa ${iconClass}"></i>
        <button type="button" class="dz-remove-btn dz-file-remove" title="Remove file">
          <i class="fa fa-times"></i>
        </button>
      </div>
      <span class="dz-preview-name" title="${this._escHtml(file.name)}">${this._escHtml(file.name)}</span>
    `;
    this.previewArea.appendChild(item);
    this._bindRemoveBtn(item);
  }

  /* ─── Remove ─────────────────────────────────────────────── */

  _bindRemoveBtn(item) {
    const btn = item.querySelector('.dz-remove-btn');
    if (!btn) return;
    btn.addEventListener('click', (e) => {
      e.stopPropagation();   // don't re-open file dialog
      this._clearPreview();
      this._dispatch('dz:clear', {});
    });
  }

  _clearPreview() {
    this.previewArea.innerHTML = '';
    this.input.value           = '';
    this.zone.classList.remove('dz-has-file');
    this._clearError();
  }

  /* ─── Error display ──────────────────────────────────────── */

  _showError(msg) {
    if (!this.feedback) return;
    this.feedback.textContent = msg;
    this.feedback.classList.add('dz-feedback-visible');
    this.zone.classList.add('dz-border-danger');
  }

  _clearError() {
    if (!this.feedback) return;
    this.feedback.textContent = '';
    this.feedback.classList.remove('dz-feedback-visible');
    this.zone.classList.remove('dz-border-danger');
  }

  /* ─── Helpers ────────────────────────────────────────────── */

  _dispatch(name, detail) {
    this.wrapper.dispatchEvent(new CustomEvent(name, { detail, bubbles: true }));
  }

  _escHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  /* ─── Static factory ──────────────────────────────────────── */

  /**
   * Initialize all `.dz-component` elements in the document.
   * Called automatically on DOMContentLoaded.
   */
  static initAll() {
    document.querySelectorAll('.dz-component').forEach((el) => {
      if (!el._dzInstance) {
        el._dzInstance = new DropzoneComponent(el);
      }
    });
  }

  /**
   * Initialize (or re-initialize) a single element by selector / element.
   * Useful for dynamically inserted components.
   * @param {string|HTMLElement} target
   * @returns {DropzoneComponent|null}
   */
  static init(target) {
    const el = typeof target === 'string' ? document.querySelector(target) : target;
    if (!el) return null;
    if (el._dzInstance) return el._dzInstance;
    el._dzInstance = new DropzoneComponent(el);
    return el._dzInstance;
  }
}

/* Auto-boot */
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => DropzoneComponent.initAll());
} else {
  DropzoneComponent.initAll();
}

/* Expose globally for external scripts */
window.DropzoneComponent = DropzoneComponent;