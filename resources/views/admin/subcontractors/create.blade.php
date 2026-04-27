@extends('layouts.admin')

@section('content')
<div class="card-container">

    {{-- ── Page Header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4 bg-secondary rounded px-3 py-2">
        <div>
            <h4 class="mb-1 text-uppercase ls-1 text-light">Add Diary Subcontractor</h4>
            <nav class="small text-light">
                <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                    <i class="fa-solid fa-house"></i>
                </a>
                <span class="mx-1">/</span>
                <a href="{{ route('subcontractors.index') }}" class="text-light text-decoration-none">
                    Diary Subcontractors
                </a>
                <span class="mx-1">/</span>
                <span>Add</span>
            </nav>
        </div>
        <a href="{{ route('subcontractors.index') }}" class="btn btn-success btn-sm">
            <i class="fa fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <form action="{{ route('subcontractors.store') }}" method="POST"
          id="dsForm" novalidate>
        @csrf

        <div class="ds-form-body mb-4">

            {{-- ── Row 1: Business Name | Representative Name ── --}}
            <div class="row g-3 mb-3">

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-uppercase">
                        Business Name <span class="text-danger">*</span>
                    </label>
                    <select name="business_name" id="businessNameSelect"
                        class="form-select @error('business_name') is-invalid @enderror">
                        <option value="">Select Business</option>
                        @foreach ($businessNames as $bn)
                            <option value="{{ $bn->id }}"
                                data-rep="{{ $bn->rep_name ?? '' }}"
                                data-other="{{ strtolower($bn->name) === 'other' ? '1' : '0' }}"
                                {{ old('business_name') == $bn->id ? 'selected' : '' }}>
                                {{ $bn->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        @error('business_name') {{ $message }} @enderror
                    </div>

                    {{-- Other business name free-text ── hidden unless "Other" selected --}}
                    <div id="businessNameOtherWrap" class="mt-2 {{ old('business_name_other') ? '' : 'd-none' }}">
                        <label class="form-label fw-semibold small text-uppercase">
                            Specify Business Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="business_name_other" id="businessNameOther"
                            class="form-control @error('business_name_other') is-invalid @enderror"
                            placeholder="Enter business name"
                            value="{{ old('business_name_other') }}">
                        <div class="invalid-feedback">
                            @error('business_name_other') {{ $message }} @enderror
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-uppercase">
                        Representative Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="rep_name" id="repName"
                        class="form-control @error('rep_name') is-invalid @enderror"
                        placeholder="Representative Name"
                        value="{{ old('rep_name') }}">
                    <div class="invalid-feedback">
                        @error('rep_name') {{ $message }} @enderror
                    </div>
                </div>

            </div>

            {{-- ── Row 2: Asset ID | Type of Work ── --}}
            <div class="row g-3 mb-4">

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-uppercase">
                        Subcontractor Asset ID
                    </label>
                    <input type="text" name="subcontractor_asset_id"
                        class="form-control @error('subcontractor_asset_id') is-invalid @enderror"
                        placeholder="Subcontractor Asset Id"
                        value="{{ old('subcontractor_asset_id') }}">
                    <div class="invalid-feedback">
                        @error('subcontractor_asset_id') {{ $message }} @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-uppercase">
                        Type of Work <span class="text-danger">*</span>
                    </label>
                    <select name="type_of_work" id="typeOfWorkSelect"
                        class="form-select @error('type_of_work') is-invalid @enderror">
                        <option value="">Select Type of Work</option>
                        @foreach ($workTypes as $wt)
                            <option value="{{ $wt->id }}"
                                data-other="{{ strtolower($wt->name) === 'other' ? '1' : '0' }}"
                                {{ old('type_of_work') == $wt->id ? 'selected' : '' }}>
                                {{ $wt->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        @error('type_of_work') {{ $message }} @enderror
                    </div>

                    {{-- Other work type free-text ── hidden unless "Other" selected --}}
                    <div id="typeOfWorkOtherWrap" class="mt-2 {{ old('type_of_work_other') ? '' : 'd-none' }}">
                        <label class="form-label fw-semibold small text-uppercase">
                            Specify Work Type <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="type_of_work_other" id="typeOfWorkOther"
                            class="form-control @error('type_of_work_other') is-invalid @enderror"
                            placeholder="Enter type of work"
                            value="{{ old('type_of_work_other') }}">
                        <div class="invalid-feedback">
                            @error('type_of_work_other') {{ $message }} @enderror
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── Row 3: Are Dockets Required? ── --}}
            <div class="row g-3">
                <div class="col-12">
                    <div class="ds-docket-row">
                        <span class="ds-docket-label">
                            Are Dockets Required?
                            <span class="text-danger">*</span>
                            <i class="fa fa-check-circle text-success ms-1 small"></i>
                        </span>

                        <div class="ds-docket-options">
                            <label class="ds-radio-label">
                                <input type="radio" name="is_docket" value="1"
                                    class="ds-radio-input"
                                    {{ old('is_docket') === '1' ? 'checked' : '' }}>
                                <span class="ds-radio-box ds-radio-yes">
                                    <i class="fa fa-check"></i>
                                </span>
                                <span class="ds-radio-text">YES</span>
                            </label>

                            <label class="ds-radio-label">
                                <input type="radio" name="is_docket" value="0"
                                    class="ds-radio-input"
                                    {{ old('is_docket') === '0' ? 'checked' : '' }}>
                                <span class="ds-radio-box ds-radio-no">
                                    <i class="fa fa-times"></i>
                                </span>
                                <span class="ds-radio-text">NO</span>
                            </label>
                        </div>

                        <div class="ds-docket-feedback" id="docketFeedback">
                            @error('is_docket') {{ $message }} @enderror
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /ds-form-body --}}

        <div class="text-center mb-4">
            <button type="submit" class="btn btn-success px-5 text-uppercase fw-bold ls-1">
                Add to Register
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/modules/subcontractors.js') }}"></script>
@endpush