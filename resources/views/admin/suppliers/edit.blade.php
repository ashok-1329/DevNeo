@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- ── Page Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 bg-secondary rounded px-3 py-2">
            <div>
                <h4 class="mb-1 text-uppercase ls-1 text-light">Edit Supplier</h4>
                <nav class="small text-light">
                    <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('suppliers.index') }}" class="text-light text-decoration-none">Supplier List</a>
                    <span class="mx-1">/</span>
                    <span>Edit Supplier</span>
                </nav>
            </div>
            <a href="{{ route('suppliers.index') }}" class="btn btn-success btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" id="supplierForm" novalidate>
            @csrf
            @method('PUT')

            {{--
                FIX 1: status is `required|in:0,1` in the controller but this form
                does not show a status UI field. Preserve the supplier's current
                status via a hidden input so validation never fails on this field.
            --}}
            <input type="hidden" name="status" value="{{ old('status', $supplier->status) }}">

            {{-- ── Basic Information ── --}}
            <div class="supplier-form-body mb-3">

                {{-- Row 1 ── --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Category <span
                                class="text-danger">*</span></label>
                        <select name="supplier_category"
                            class="form-select form-select-sm @error('supplier_category') is-invalid @enderror">
                            <option value="">Select</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('supplier_category', $supplier->supplier_category) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Business Name <span
                                class="text-danger">*</span></label>
                        <input type="text" name="supplier_name"
                            class="form-control form-control-sm @error('supplier_name') is-invalid @enderror"
                            placeholder="Enter supplier name" value="{{ old('supplier_name', $supplier->supplier_name) }}">
                        @error('supplier_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Email <span
                                class="text-danger">*</span></label>
                        <input type="email" name="supplier_email"
                            class="form-control form-control-sm @error('supplier_email') is-invalid @enderror"
                            placeholder="Enter supplier email"
                            value="{{ old('supplier_email', $supplier->supplier_email) }}">
                        @error('supplier_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Row 2 ── --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Phone <span
                                class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-secondary text-light pt-1"><i class="fa fa-phone"></i></span>
                            <input type="text" name="supplier_phone"
                                class="form-control @error('supplier_phone') is-invalid @enderror"
                                placeholder="Enter phone number"
                                value="{{ old('supplier_phone', $supplier->supplier_phone) }}">
                            @error('supplier_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">ABN <span
                                class="text-danger">*</span></label>
                        <input type="text" name="supplier_abn"
                            class="form-control form-control-sm @error('supplier_abn') is-invalid @enderror"
                            placeholder="Enter ABN number (11 digits)" maxlength="11"
                            value="{{ old('supplier_abn', $supplier->supplier_abn) }}">
                        @error('supplier_abn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Address <span
                                class="text-danger">*</span></label>
                        <textarea name="supplier_address" rows="3"
                            class="form-control form-control-sm @error('supplier_address') is-invalid @enderror"
                            placeholder="Enter supplier address">{{ old('supplier_address', $supplier->supplier_address) }}</textarea>
                        @error('supplier_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Row 3 ── --}}
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Account Email Address <span
                                class="text-danger">*</span></label>
                        <input type="email" name="supplier_bank_email"
                            class="form-control form-control-sm @error('supplier_bank_email') is-invalid @enderror"
                            placeholder="Enter email address"
                            value="{{ old('supplier_bank_email', $supplier->supplier_bank_email) }}">
                        @error('supplier_bank_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        <label class="form-label fw-semibold small text-uppercase">Bank Name <span
                                class="text-danger">*</span></label>
                        <input type="text" name="supplier_bank_name"
                            class="form-control form-control-sm @error('supplier_bank_name') is-invalid @enderror"
                            placeholder="Enter bank name"
                            value="{{ old('supplier_bank_name', $supplier->supplier_bank_name) }}">
                        @error('supplier_bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">BSB No. <span
                                class="text-danger">*</span></label>
                        <input type="text" name="supplier_bsb_no"
                            class="form-control form-control-sm @error('supplier_bsb_no') is-invalid @enderror"
                            placeholder="Enter BSB number (e.g. 062-000)" maxlength="7"
                            value="{{ old('supplier_bsb_no', $supplier->supplier_bsb_no) }}">
                        @error('supplier_bsb_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Account Number <span
                                class="text-danger">*</span></label>
                        <input type="text" name="supplier_account_number"
                            class="form-control form-control-sm @error('supplier_account_number') is-invalid @enderror"
                            placeholder="Enter account number"
                            value="{{ old('supplier_account_number', $supplier->supplier_account_number) }}">
                        <p class="form-text mt-1">
                            Check with bank about correct details – card numbers are not account numbers
                        </p>
                        @error('supplier_account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Row 2 ── --}}
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Account Name <span
                                class="text-danger">*</span></label>
                        <input type="text" name="supplier_account_name"
                            class="form-control form-control-sm @error('supplier_account_name') is-invalid @enderror"
                            placeholder="Enter account name"
                            value="{{ old('supplier_account_name', $supplier->supplier_account_name) }}">
                        @error('supplier_account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Payment Term <span
                                class="text-danger">*</span></label>
                        <select name="payment_terms"
                            class="form-select form-select-sm @error('payment_terms') is-invalid @enderror">
                            <option value="">Select Payment Term</option>
                            @foreach ($paymentTerms as $pt)
                                <option value="{{ $pt }}"
                                    {{ old('payment_terms', $supplier->payment_terms) == $pt ? 'selected' : '' }}>
                                    {{ $pt }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>{{-- /supplier-form-body --}}

            {{-- ── Notes ── --}}
            <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

            {{--
                FIX 2: hidden input carries Quill HTML to the server on submit.
                old() seeds it on validation-failure redirect; otherwise Quill
                content is injected via the inline script below.
            --}}
            <input type="hidden" name="supplier_notes" id="supplierNotesHidden" value="{{ old('supplier_notes') }}">

            <div id="editor" style="height:180px;"></div>

            <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
            <script>
                var quill = new Quill('#editor', {
                    theme: 'snow'
                });

                // FIX 2a: prefer old() after a failed validation redirect,
                // otherwise load the saved supplier notes
                @if (old('supplier_notes'))
                    quill.root.innerHTML = {!! json_encode(old('supplier_notes')) !!};
                @elseif ($supplier->supplier_notes)
                    quill.root.innerHTML = {!! json_encode($supplier->supplier_notes) !!};
                @endif
            </script>

            {{-- ── Submit ── --}}
            <div class="text-center my-4">
                <button type="submit" class="btn btn-success px-5 text-uppercase fw-bold ls-1">
                    Update Supplier
                </button>
            </div>

        </form>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // BSB auto-format
            document.querySelector('[name="supplier_bsb_no"]').addEventListener('input', function() {
                let v = this.value.replace(/\D/g, '');
                if (v.length > 3) v = v.slice(0, 3) + '-' + v.slice(3, 6);
                this.value = v;
            });

            // ABN digits only
            document.querySelector('[name="supplier_abn"]').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });

            // FIX 2b: sync Quill HTML into hidden input right before submit
            document.getElementById('supplierForm').addEventListener('submit', function() {
                document.getElementById('supplierNotesHidden').value = quill.root.innerHTML;
            });
        });
    </script>
@endpush
