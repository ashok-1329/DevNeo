@extends('layouts.admin')

@section('content')
    <div class="card-container">

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

            <input type="hidden" name="status" value="{{ old('status', $supplier->status) }}">

            <div class="supplier-form-body mb-3">

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Category <span
                                class="text-danger">*</span></label>
                        <select name="supplier_category"
                            class="form-select @error('supplier_category') is-invalid @enderror">
                            <option value="">Select</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('supplier_category', $supplier->supplier_category) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            @error('supplier_category')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Business Name <span
                                class="text-danger">*</span></label>
                        <input type="text" name="supplier_name"
                            class="form-control @error('supplier_name') is-invalid @enderror"
                            placeholder="Enter supplier name" value="{{ old('supplier_name', $supplier->supplier_name) }}">
                        <div class="invalid-feedback">
                            @error('supplier_name')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Email <span
                                class="text-danger">*</span></label>
                        <input type="email" name="supplier_email"
                            class="form-control @error('supplier_email') is-invalid @enderror"
                            placeholder="Enter supplier email"
                            value="{{ old('supplier_email', $supplier->supplier_email) }}">
                        <div class="invalid-feedback">
                            @error('supplier_email')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Phone <span
                                class="text-danger">*</span></label>
                        <div class="input-group ">
                            <span class="input-group-text bg-secondary text-light pt-1"><i class="fa fa-phone"></i></span>
                            <input type="number" name="supplier_phone"
                                class="form-control @error('supplier_phone') is-invalid @enderror"
                                placeholder="Enter phone number"
                                value="{{ old('supplier_phone', $supplier->supplier_phone) }}">
                        </div>
                        <div class="invalid-feedback d-block">
                            @error('supplier_phone')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">ABN <span
                                class="text-danger">*</span></label>
                        <input type="text" name="supplier_abn"
                            class="form-control @error('supplier_abn') is-invalid @enderror"
                            placeholder="Enter ABN number (11 digits)" maxlength="11"
                            value="{{ old('supplier_abn', $supplier->supplier_abn) }}">
                        <div class="invalid-feedback">
                            @error('supplier_abn')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Address <span
                                class="text-danger">*</span></label>
                        <textarea name="supplier_address" rows="3"
                            class="form-control @error('supplier_address') is-invalid @enderror"
                            placeholder="Enter supplier address">{{ old('supplier_address', $supplier->supplier_address) }}</textarea>
                        <div class="invalid-feedback">
                            @error('supplier_address')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Account Email Address <span
                                class="text-danger">*</span></label>
                        <input type="email" name="supplier_bank_email"
                            class="form-control @error('supplier_bank_email') is-invalid @enderror"
                            placeholder="Enter email address"
                            value="{{ old('supplier_bank_email', $supplier->supplier_bank_email) }}">
                        <div class="invalid-feedback">
                            @error('supplier_bank_email')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            <div class="mb-0 bg-secondary rounded px-3 py-2 text-light mb-2">
                <span><i class="fa fa-university me-2"></i>Bank Details</span>
            </div>
            <div class="supplier-form-body mb-3">

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Bank Name <span
                                class="text-danger">*</span></label>
                        <input type="text" name="supplier_bank_name"
                            class="form-control @error('supplier_bank_name') is-invalid @enderror"
                            placeholder="Enter bank name"
                            value="{{ old('supplier_bank_name', $supplier->supplier_bank_name) }}">
                        <div class="invalid-feedback">
                            @error('supplier_bank_name')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">BSB No. <span
                                class="text-danger">*</span></label>
                        <input type="text" name="supplier_bsb_no"
                            class="form-control @error('supplier_bsb_no') is-invalid @enderror"
                            placeholder="000-000" maxlength="7"
                            value="{{ old('supplier_bsb_no', $supplier->supplier_bsb_no) }}">
                        <div class="invalid-feedback">
                            @error('supplier_bsb_no')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Account Number <span
                                class="text-danger">*</span></label>
                        <input type="text" name="supplier_account_number"
                            class="form-control @error('supplier_account_number') is-invalid @enderror"
                            placeholder="Enter account number"
                            value="{{ old('supplier_account_number', $supplier->supplier_account_number) }}">
                        <p class="form-text mt-1">Check with bank about correct details – card numbers are not account
                            numbers</p>
                        <div class="invalid-feedback">
                            @error('supplier_account_number')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Account Name <span
                                class="text-danger">*</span></label>
                        <input type="text" name="supplier_account_name"
                            class="form-control @error('supplier_account_name') is-invalid @enderror"
                            placeholder="Enter account name"
                            value="{{ old('supplier_account_name', $supplier->supplier_account_name) }}">
                        <div class="invalid-feedback">
                            @error('supplier_account_name')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Payment Term <span
                                class="text-danger">*</span></label>
                        <select name="payment_term_id"
                            class="form-select @error('payment_term_id') is-invalid @enderror">
                            <option value="">Select Payment Term</option>
                            @foreach ($paymentTerms as $pt)
                                {{-- data-days is used by JS to populate the read-only days field --}}
                                <option value="{{ $pt->id }}" data-days="{{ $pt->days ?? '' }}"
                                    {{ old('payment_term_id', $supplier->payment_term_id) == $pt->id ? 'selected' : '' }}>
                                    {{ $pt->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            @error('payment_term_id')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    {{-- <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Payment Days</label>

                        <input type="text" id="paymentTermDaysDisplay" class="form-control"
                            placeholder="Auto-filled on selection" readonly tabindex="-1"
                            value="{{ $supplier->payment_term_days ? $supplier->payment_term_days . ' days' : '' }}">
                    </div> --}}
                </div>

            </div>
            <div>
                <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
                <label class="form-label fw-semibold small text-uppercase">Notes</label>
                <input type="hidden" name="supplier_notes" id="supplierNotesHidden"
                    value="{{ old('supplier_notes') }}">
                <div id="editor" style="height:180px;"></div>
                <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
            </div>
            <script>
                var quill = new Quill('#editor', {
                    theme: 'snow'
                });
                @if (old('supplier_notes'))
                    quill.root.innerHTML = {!! json_encode(old('supplier_notes')) !!};
                @elseif ($supplier->supplier_notes)
                    quill.root.innerHTML = {!! json_encode($supplier->supplier_notes) !!};
                @endif
            </script>

            <div class="text-center my-4">
                <button type="submit" class="btn btn-success px-5 text-uppercase fw-bold ls-1">
                    Update Supplier
                </button>
            </div>

        </form>
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

        .input-group~.invalid-feedback {
            display: block;
        }

        #paymentTermDaysDisplay {
            background-color: #f0f2f5;
            cursor: default;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/modules/supplier-form.js') }}"></script>
@endpush
