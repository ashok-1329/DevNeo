@extends('layouts.admin')

@section('content')
<div class="card-container">

    {{-- ── Header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4 pr-form-header rounded px-3 py-2 bg-secondary">
        <div>
            <h4 class="mb-1 text-uppercase ls-1 text-light">Edit Payment Rule</h4>
            <nav class="small text-light">
                <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                    <i class="fa-solid fa-house"></i>
                </a>
                <span class="mx-1">/</span>
                <a href="{{ route('payment-rules.index') }}" class="text-light text-decoration-none">
                    Payment Rules
                </a>
                <span class="mx-1">/</span>
                <span>Edit</span>
            </nav>
        </div>
        <a href="{{ route('payment-rules.index') }}" class="btn btn-success btn-sm">
            <i class="fa fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <form action="{{ route('payment-rules.update', $paymentRule->id) }}" method="POST"
          id="paymentRuleForm" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        <div class="pr-form-body mb-4">

            {{-- ── Row 1: Supplier | Payment Date | Frequency ── --}}
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">
                        Supplier Name <span class="text-danger">*</span>
                    </label>
                    <select name="supplier_name"
                            class="form-select form-select-sm @error('supplier_name') is-invalid @enderror">
                        <option value="">Select</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}"
                                {{ old('supplier_name', $paymentRule->supplier_name) == $s->id ? 'selected' : '' }}>
                                {{ $s->supplier_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        @error('supplier_name') {{ $message }} @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">
                        Payment Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="payment_date"
                           class="form-control form-control-sm @error('payment_date') is-invalid @enderror"
                           value="{{ old('payment_date', $paymentRule->payment_date?->format('Y-m-d')) }}"
                           min="{{ $today }}">
                    <div class="invalid-feedback">
                        @error('payment_date') {{ $message }} @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">
                        Frequency of Payment <span class="text-danger">*</span>
                    </label>
                    <select name="frequency_payment_id"
                            class="form-select form-select-sm @error('frequency_payment_id') is-invalid @enderror">
                        <option value="">Select</option>
                        @foreach ($frequencyPayments as $fp)
                            <option value="{{ $fp->id }}"
                                {{ old('frequency_payment_id', $paymentRule->frequency_payment_id) == $fp->id ? 'selected' : '' }}>
                                {{ $fp->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        @error('frequency_payment_id') {{ $message }} @enderror
                    </div>
                </div>
            </div>

            {{-- ── Row 2: End Date | Value | Project Number ── --}}
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">
                        End Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="end_date"
                           class="form-control form-control-sm @error('end_date') is-invalid @enderror"
                           value="{{ old('end_date', $paymentRule->end_date?->format('Y-m-d')) }}"
                           min="{{ $today }}">
                    <div class="invalid-feedback">
                        @error('end_date') {{ $message }} @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">
                        Value (inc. GST) <span class="text-danger">*</span>
                    </label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-secondary text-light">$</span>
                        <input type="text" name="value_inc_gst"
                               class="form-control @error('value_inc_gst') is-invalid @enderror"
                               placeholder="0.00"
                               value="{{ old('value_inc_gst', $paymentRule->value_inc_gst) }}">
                    </div>
                    <div class="invalid-feedback d-block">
                        @error('value_inc_gst') {{ $message }} @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">
                        Project Number <span class="text-danger">*</span>
                    </label>
                    <select id="project_number_select"
                            class="form-select form-select-sm @error('project_number') is-invalid @enderror">
                        <option value="">Select</option>
                        @foreach ($projects as $proj)
                            <option value="{{ $proj->id }}"
                                    data-number="{{ $proj->project_number }}"
                                    data-code="{{ $proj->project_code_id ?? '' }}">
                                {{ $proj->project_number }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="project_number"
                           value="{{ old('project_number', $paymentRule->project_number) }}">
                    <div class="invalid-feedback">
                        @error('project_number') {{ $message }} @enderror
                    </div>
                </div>
            </div>

            {{-- ── Row 3: Project Code | Payment Description ── --}}
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">
                        Project Code <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="project_code" id="project_code"
                           class="form-control form-control-sm project-code-readonly @error('project_code') is-invalid @enderror"
                           placeholder="Project Code"
                           value="{{ old('project_code', $paymentRule->project_code) }}"
                           readonly tabindex="-1">
                    <div class="invalid-feedback">
                        @error('project_code') {{ $message }} @enderror
                    </div>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold small text-uppercase">
                        Payment Description <span class="text-danger">*</span>
                    </label>
                    <textarea name="payment_description" rows="4"
                              class="form-control form-control-sm @error('payment_description') is-invalid @enderror"
                              placeholder="Enter payment description">{{ old('payment_description', $paymentRule->payment_description) }}</textarea>
                    <div class="invalid-feedback">
                        @error('payment_description') {{ $message }} @enderror
                    </div>
                </div>
            </div>

            {{-- ── Row 4: File Upload ── --}}
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold small text-uppercase">
                        Upload Supporting Documents
                        <span class="text-muted small fw-normal">(leave blank to keep existing)</span>
                    </label>

                    {{-- Show existing document if any --}}
                    @if ($paymentRule->document_path)
                        <div class="existing-doc-preview mb-2">
                            <i class="fa fa-paperclip me-1 text-secondary"></i>
                            <a href="{{ Storage::url($paymentRule->document_path) }}"
                               target="_blank" class="text-primary small">
                                {{ basename($paymentRule->document_path) }}
                            </a>
                            <span class="text-muted small ms-2">(currently attached)</span>
                        </div>
                    @endif

                    <div class="upload-zone-wrapper">
                        <div id="dropZone" class="upload-drop-zone @error('document') border-danger @enderror">
                            <div id="uploadPreview" class="upload-preview-area"></div>
                            <div id="uploadPlaceholder" class="upload-placeholder">
                                <i class="fa fa-cloud-upload-alt fa-2x text-secondary mb-2"></i>
                                <p class="mb-1 text-secondary small fw-semibold">
                                    Drag &amp; drop file here, or
                                    <span class="text-success">click to browse</span>
                                </p>
                                <p class="text-muted" style="font-size:0.75rem">
                                    PDF, Word, JPG, PNG — max 10 MB
                                </p>
                            </div>
                        </div>
                        <input type="file" id="documentInput" name="document"
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                               class="d-none @error('document') is-invalid @enderror">
                        <div class="invalid-feedback d-block">
                            @error('document') {{ $message }} @enderror
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /pr-form-body --}}

        <div class="text-center mb-4">
            <button type="submit" class="btn btn-success px-5 text-uppercase fw-bold ls-1">
                Update Payment Rule
            </button>
        </div>

    </form>
</div>
@endsection

@push('styles')
<style>
    .ls-1 { letter-spacing: .05em; }
    .pr-form-header { background: #6c757d; }
    .pr-form-body {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1.5rem;
    }
    .project-code-readonly { background-color: #f0f2f5; cursor: default; }
    .existing-doc-preview {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        display: inline-block;
    }
    .upload-drop-zone {
        border: 2px dashed #ced4da;
        border-radius: 0.375rem;
        min-height: 160px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: #fafbfc;
        transition: border-color 0.2s, background 0.2s;
        overflow: hidden;
        padding: 1rem;
    }
    .upload-drop-zone:hover, .upload-drop-zone.drag-over {
        border-color: #5a8a3a;
        background: #f0f7ec;
    }
    .upload-drop-zone.border-danger { border-color: #dc3545 !important; }
    .upload-preview-area {
        display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; width: 100%;
    }
    .upload-preview-item {
        display: flex; flex-direction: column; align-items: center; gap: 6px;
    }
    .upload-preview-item img {
        max-height: 120px; max-width: 260px; border-radius: 4px;
        object-fit: contain; box-shadow: 0 2px 8px rgba(0,0,0,.12);
    }
    .upload-preview-name {
        font-size: 0.75rem; color: #555; max-width: 240px;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .upload-preview-area:not(:empty) ~ .upload-placeholder { display: none; }
    .input-group ~ .invalid-feedback { display: block; }
</style>
@endpush

@push('scripts')
    <script src="{{ asset('js/modules/payment-rule-form.js') }}"></script>
@endpush