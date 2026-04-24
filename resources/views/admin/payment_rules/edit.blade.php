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
                            class="form-select @error('supplier_name') is-invalid @enderror">
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
                        <input type="text" name="payment_date" id="payment_date"
                            class="form-control datepicker @error('payment_date') is-invalid @enderror"
                            placeholder="dd/mm/yyyy"
                            value="{{ old('payment_date', formatToUserDate($paymentRule->payment_date)) }}">
                        <div class="invalid-feedback">
                            @error('payment_date') {{ $message }} @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">
                            Frequency of Payment <span class="text-danger">*</span>
                        </label>
                        <select name="frequency_payment_id"
                            class="form-select @error('frequency_payment_id') is-invalid @enderror">
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
                        <input type="text" name="end_date" id="end_date"
                            class="form-control datepicker @error('end_date') is-invalid @enderror"
                            placeholder="dd/mm/yyyy"
                            value="{{ old('end_date', formatToUserDate($paymentRule->end_date)) }}">
                        <div class="invalid-feedback">
                            @error('end_date') {{ $message }} @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">
                            Value (inc. GST) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group ">
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
                            class="form-select @error('project_number') is-invalid @enderror">
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
                            class="form-control project-code-readonly @error('project_code') is-invalid @enderror"
                            placeholder="Auto-filled"
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
                            class="form-control @error('payment_description') is-invalid @enderror"
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
                            {{-- <span class="text-muted small fw-normal">(leave blank to keep existing)</span> --}}
                        </label>

                        {{--
                            Pass `existing` so the component renders the current attachment badge.
                            In edit mode the document field is optional (not required).
                        --}}
                        <x-dropzone
                            name="document"
                            type="document"
                            id="documentInput"
                            :existing="$paymentRule->document_path ?? null"
                            :required="false" />
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