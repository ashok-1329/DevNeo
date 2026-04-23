@extends('layouts.admin')

@section('content')
<div class="card-container">

    {{-- ── Header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4 pr-form-header rounded px-3 py-2 bg-secondary">
        <div>
            <h4 class="mb-1 text-uppercase ls-1 text-light">View Payment Rule</h4>
            <nav class="small text-light">
                <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                    <i class="fa-solid fa-house"></i>
                </a>
                <span class="mx-1">/</span>
                <a href="{{ route('payment-rules.index') }}" class="text-light text-decoration-none">
                    Payment Rules
                </a>
                <span class="mx-1">/</span>
                <span>View</span>
            </nav>
        </div>
        <div class="d-flex gap-2">
            {{-- <a href="{{ route('payment-rules.edit', $paymentRule->id) }}"
               class="btn btn-success btn-sm">
                <i class="fa fa-edit me-1"></i> Edit
            </a> --}}
            <a href="{{ route('payment-rules.index') }}" class="btn btn-success btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="pr-form-body mb-4">

        {{-- ── Row 1 ── --}}
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-uppercase">Supplier Name</label>
                <input type="text" class="form-control form-control-sm show-readonly"
                       value="{{ $paymentRule->supplier->supplier_name ?? '-' }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-uppercase">Payment Date</label>
                <input type="text" class="form-control form-control-sm show-readonly"
                       value="{{ $paymentRule->payment_date?->format('d-m-Y') ?? '-' }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-uppercase">Frequency of Payment</label>
                <input type="text" class="form-control form-control-sm show-readonly"
                       value="{{ $paymentRule->frequencyPayment->name ?? '-' }}" readonly>
            </div>
        </div>

        {{-- ── Row 2 ── --}}
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-uppercase">End Date</label>
                <input type="text" class="form-control form-control-sm show-readonly"
                       value="{{ $paymentRule->end_date?->format('d-m-Y') ?? '-' }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-uppercase">Value (inc. GST)</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-secondary text-light">$</span>
                    <input type="text" class="form-control show-readonly"
                           value="{{ $paymentRule->value_inc_gst ?? '-' }}" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-uppercase">Project Number</label>
                <input type="text" class="form-control form-control-sm show-readonly"
                       value="{{ $paymentRule->project_number ?? '-' }}" readonly>
            </div>
        </div>

        {{-- ── Row 3 ── --}}
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-uppercase">Project Code</label>
                <input type="text" class="form-control form-control-sm show-readonly"
                       value="{{ $paymentRule->project_code ?? '-' }}" readonly>
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold small text-uppercase">Payment Description</label>
                <textarea class="form-control form-control-sm show-readonly" rows="4"
                          readonly>{{ $paymentRule->payment_description ?? '-' }}</textarea>
            </div>
        </div>

        {{-- ── Supporting Document ── --}}
        <div class="row g-3 mb-3">
            <div class="col-12">
                <label class="form-label fw-semibold small text-uppercase">Supporting Document</label>
                @if ($paymentRule->document_path)
                    @php
                        $ext = strtolower(pathinfo($paymentRule->document_path, PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                    @endphp
                    <div class="doc-preview-box">
                        @if ($isImage)
                            <img src="{{ Storage::url($paymentRule->document_path) }}"
                                 alt="Document preview"
                                 class="doc-preview-img" style="width: 360px;">
                        @else
                            <i class="fa fa-file-{{ $ext === 'pdf' ? 'pdf text-danger' : 'word text-primary' }} fa-3x mb-2"></i>
                        @endif
                        <div class="mt-2">
                            <a href="{{ Storage::url($paymentRule->document_path) }}"
                               target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="fa fa-download me-1"></i>
                                {{ basename($paymentRule->document_path) }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="show-readonly p-2 rounded border text-muted small">
                        No document attached.
                    </div>
                @endif
            </div>
        </div>

        {{-- ── Meta ── --}}
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-uppercase">Status</label>
                <div class="mt-1">
                    @if ($paymentRule->status)
                        <span class="badge bg-success px-3 py-2">Active</span>
                    @else
                        <span class="badge bg-secondary px-3 py-2">Inactive</span>
                    @endif
                </div>
            </div>
        </div>

    </div>{{-- /pr-form-body --}}

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
    .show-readonly {
        background-color: #f0f2f5 !important;
        cursor: default !important;
        color: #212529 !important;
        border-color: #dee2e6 !important;
        box-shadow: none !important;
    }
    .show-readonly:focus {
        outline: none !important;
        box-shadow: none !important;
        border-color: #dee2e6 !important;
    }
    textarea.show-readonly { resize: none; }
    .doc-preview-box {
        background: #f0f2f5;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1.25rem;
        text-align: center;
        display: inline-block;
        min-width: 220px;
    }
    .doc-preview-img {
        max-height: 200px;
        max-width: 200px;
        border-radius: 4px;
        object-fit: contain;
        box-shadow: 0 2px 8px rgba(0,0,0,.12);
    }
</style>
@endpush