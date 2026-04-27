@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- ── Page Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 bg-secondary rounded px-3 py-2">
            <div>
                <h4 class="mb-1 text-uppercase ls-1 text-light">View Diary Subcontractor</h4>
                <nav class="small text-light">
                    <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('subcontractors.index') }}" class="text-light text-decoration-none">
                        Diary Subcontractors
                    </a>
                    <span class="mx-1">/</span>
                    <span>View</span>
                </nav>
            </div>
            <div class="d-flex gap-2">
                {{-- <a href="{{ route('subcontractors.edit', $diarySubcontractor->id) }}"
               class="btn btn-success btn-sm">
                <i class="fa fa-edit me-1"></i> Edit
            </a> --}}
                <a href="{{ route('subcontractors.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="ds-form-body mb-4">

            {{-- ── Row 1: Business Name | Representative ── --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-uppercase">Business Name</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $diarySubcontractor->business_name_label }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-uppercase">Representative Name</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $diarySubcontractor->rep_name ?? '-' }}" readonly>
                </div>
            </div>

            {{-- ── Row 2: Asset ID | Type of Work ── --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-uppercase">Subcontractor Asset ID</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $diarySubcontractor->subcontractor_asset_id ?? '-' }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-uppercase">Type of Work</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $diarySubcontractor->work_type_label }}" readonly>
                </div>
            </div>

            {{-- ── Row 3: Dockets Required | Status ── --}}
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="ds-docket-row">
                        <span class="ds-docket-label">
                            Are Dockets Required?
                            <i class="fa fa-check-circle text-success ms-1 small"></i>
                        </span>
                        <div class="ds-docket-options">
                            <label class="ds-radio-label">
                                <input type="radio" disabled name="is_docket" value="1" class="ds-radio-input"
                                    {{ old('is_docket', $diarySubcontractor->is_docket) == '1' ? 'checked' : '' }}>
                                <span class="ds-radio-box ds-radio-yes">
                                    <i class="fa fa-check"></i>
                                </span>
                                <span class="ds-radio-text">YES</span>
                            </label>

                            <label class="ds-radio-label">
                                <input type="radio" disabled name="is_docket" value="0" class="ds-radio-input"
                                    {{ old('is_docket', $diarySubcontractor->is_docket) == '0' ? 'checked' : '' }}>
                                <span class="ds-radio-box ds-radio-no">
                                    <i class="fa fa-times"></i>
                                </span>
                                <span class="ds-radio-text">NO</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-uppercase">Status</label>
                    <div class="mt-1">
                        @if ($diarySubcontractor->status == 1)
                            <span class="badge bg-success px-3 py-2">Active</span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>{{-- /ds-form-body --}}

    </div>
@endsection
