@extends('layouts.admin')

@section('content')
    <div class="card-container">

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
                <a href="{{ route('payment-rules.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="pr-form-body mb-4">

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Supplier Name</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $paymentRule->supplier->supplier_name ?? '-' }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Payment Date</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $paymentRule->payment_date?->format('d/m/Y') ?? '-' }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Frequency of Payment</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $paymentRule->frequencyPayment->name ?? '-' }}" readonly>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">End Date</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $paymentRule->end_date?->format('d/m/Y') ?? '-' }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Value (inc. GST)</label>
                    <div class="input-group ">
                        <span class="input-group-text bg-secondary text-light">$</span>
                        <input type="text" class="form-control show-readonly"
                            value="{{ $paymentRule->value_inc_gst ?? '-' }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Project Number</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $paymentRule->project_number ?? '-' }}" readonly>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Project Code</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $paymentRule->project_code ?? '-' }}" readonly>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold small text-uppercase">Payment Description</label>
                    <textarea class="form-control show-readonly" rows="4" readonly>{{ $paymentRule->payment_description ?? '-' }}</textarea>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-12">
                    <label class="form-label fw-semibold small text-uppercase">Supporting Document</label>

                    @if ($paymentRule->document_path)
                        @php
                            $ext = strtolower(pathinfo($paymentRule->document_path, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            $docUrl = Storage::url($paymentRule->document_path);
                            $docName = basename($paymentRule->document_path);
                        @endphp

                        <div class="show-doc-box">
                            @if ($isImage)
                                <div class="show-img-wrapper">
                                    <img src="{{ $docUrl }}" alt="{{ $docName }}" class="show-doc-img">
                                    <div class="show-img-overlay">
                                        <a href="{{ $docUrl }}" target="_blank" class="show-overlay-btn"
                                            title="Open full size">
                                            <i class="fa fa-expand-alt"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <span class="show-doc-name">{{ $docName }}</span>
                                </div>
                            @else
                                <div class="show-file-icon-wrap">
                                    <i
                                        class="fa fa-file-{{ $ext === 'pdf' ? 'pdf text-danger' : 'word text-primary' }} fa-3x"></i>
                                </div>
                                <div class="mt-2">
                                    <span class="show-doc-name">{{ $docName }}</span>
                                </div>
                            @endif

                            <div class="mt-3">
                                <a href="{{ $docUrl }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa fa-download me-1"></i> Download
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

        </div>

    </div>
@endsection
