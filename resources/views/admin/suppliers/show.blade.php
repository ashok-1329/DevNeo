@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- ── Page Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 bg-secondary rounded px-3 py-2">
            <div>
                <h4 class="mb-1 text-uppercase ls-1 text-light">Show Supplier</h4>
                <nav class="small text-light">
                    <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('suppliers.index') }}" class="text-light text-decoration-none">Supplier List</a>
                    <span class="mx-1">/</span>
                    <span>Show Supplier</span>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-success btn-sm">
                    <i class="fa fa-edit me-1"></i> Edit
                </a>
                <a href="{{ route('suppliers.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        {{-- ── Basic Information ── --}}
        <div class="supplier-form-body mb-3">

            {{-- Row 1 ── --}}
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Category</label>
                    <input type="text" class="form-control form-control-sm show-readonly"
                        value="{{ $supplier->category->name ?? '-' }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Business Name</label>
                    <input type="text" class="form-control form-control-sm show-readonly"
                        value="{{ $supplier->supplier_name ?? '-' }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Email</label>
                    <input type="text" class="form-control form-control-sm show-readonly"
                        value="{{ $supplier->supplier_email ?? '-' }}" readonly>
                </div>
            </div>

            {{-- Row 2 ── --}}
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Phone</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-secondary text-light pt-1">
                            <i class="fa fa-phone"></i>
                        </span>
                        <input type="text" class="form-control show-readonly"
                            value="{{ $supplier->supplier_phone ?? '-' }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">ABN</label>
                    <input type="text" class="form-control form-control-sm show-readonly"
                        value="{{ $supplier->supplier_abn ?? '-' }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Address</label>
                    <textarea class="form-control form-control-sm show-readonly" rows="3" readonly>{{ $supplier->supplier_address ?? '-' }}</textarea>
                </div>
            </div>

            {{-- Row 3 ── --}}
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Account Email Address</label>
                    <input type="text" class="form-control form-control-sm show-readonly"
                        value="{{ $supplier->supplier_bank_email ?? '-' }}" readonly>
                </div>
            </div>

        </div>{{-- /supplier-form-body --}}

        {{-- ── Bank Details ── --}}
        <div class="mb-0 bg-secondary rounded px-3 py-2 text-light mb-2">
            <span><i class="fa fa-university me-2"></i>Bank Details</span>
        </div>
        <div class="supplier-form-body mb-3">

            {{-- Row 1 ── --}}
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Bank Name</label>
                    <input type="text" class="form-control form-control-sm show-readonly"
                        value="{{ $supplier->supplier_bank_name ?? '-' }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">BSB No.</label>
                    <input type="text" class="form-control form-control-sm show-readonly"
                        value="{{ $supplier->supplier_bsb_no ?? '-' }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Account Number</label>
                    <input type="text" class="form-control form-control-sm show-readonly"
                        value="{{ $supplier->supplier_account_number ?? '-' }}" readonly>
                    <p class="form-text mt-1">
                        Check with bank about correct details – card numbers are not account numbers
                    </p>
                </div>
            </div>

            {{-- Row 2 ── --}}
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Account Name</label>
                    <input type="text" class="form-control form-control-sm show-readonly"
                        value="{{ $supplier->supplier_account_name ?? '-' }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Payment Term</label>
                    <input type="text" class="form-control form-control-sm show-readonly"
                        value="{{ $supplier->payment_terms ?? '-' }}" readonly>
                </div>
            </div>

        </div>{{-- /supplier-form-body --}}

        {{-- ── Notes ── --}}
        <div class="supplier-form-body mb-4">
            <label class="form-label fw-semibold small text-uppercase">Notes</label>
            <div class="show-notes-display">
                {!! $supplier->supplier_notes ?? '<span class="text-muted">No notes.</span>' !!}
            </div>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        .ls-1 {
            letter-spacing: .05em;
        }

        .supplier-form-body {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1.25rem;
        }

        /* Readonly inputs look like the form but are not editable */
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

        textarea.show-readonly {
            resize: none;
        }

        /* Notes display styled like Quill read-only output */
        .show-notes-display {
            background-color: #f0f2f5;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            min-height: 120px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #212529;
            line-height: 1.6;
        }

        .show-notes-display p {
            margin-bottom: 0.25rem;
        }
    </style>
@endpush
