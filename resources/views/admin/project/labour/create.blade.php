@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- ── Page Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 bg-secondary rounded px-3 py-2">
            <div>
                <h4 class="mb-1 text-uppercase ls-1 text-light">Add Labour</h4>
                <nav class="small text-light">
                    <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('admin.project.labour.index') }}" class="text-light text-decoration-none">Labour
                        List</a>
                    <span class="mx-1">/</span>
                    <span>Add Labour</span>
                </nav>
            </div>
            <a href="{{ route('admin.project.labour.index') }}" class="btn btn-success btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <form action="{{ route('admin.project.labour.store') }}" method="POST" id="labourForm" novalidate>
            @csrf

            {{-- ── Row 1: Name / Region / Employment Type ── --}}
            <div class="supplier-form-body mb-3">
                <div class="row g-3">

                    {{-- Name --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">
                            Name <span class="text-danger">*</span>
                        </label>
                        <input type="hidden" name="user_id" id="labourUserId">
                        <input type="text" name="name" id="labourName"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Type to search…"
                            value="{{ old('name') }}" autocomplete="off">
                        {{-- Autocomplete dropdown --}}
                        <ul id="labourSuggestions" class="list-group position-absolute shadow-sm"
                            style="z-index:1050; display:none; min-width:100%;">
                        </ul>
                        <div class="invalid-feedback">
                            @error('name')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    {{-- Region --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">
                            Region <span class="text-danger">*</span>
                        </label>
                        <select name="region" id="regionSelect" class="form-select @error('region') is-invalid @enderror">
                            <option value="">Select Region</option>
                            @foreach ($project_regions as $region)
                                <option value="{{ $region->id }}" {{ old('region') == $region->id ? 'selected' : '' }}>
                                    {{ $region->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="region_name" id="regionNameInput" class="form-control mt-2"
                            placeholder="Enter new region name" style="display:none;" value="{{ old('region_name') }}">
                        <div class="invalid-feedback">
                            @error('region')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    {{-- Employment Type --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">
                            Employment Type <span class="text-danger">*</span>
                        </label>
                        <select name="employment_type" id="employmentType"
                            class="form-select @error('employment_type') is-invalid @enderror">
                            <option value="">Select Type</option>
                            @foreach ($employment_types as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('employment_type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            @error('employment_type')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Row 2: Position / Employer / Rate ── --}}
            <div class="supplier-form-body mb-3">
                <div class="row g-3">

                    {{-- Title / Position --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">
                            Title / Position <span class="text-danger">*</span>
                        </label>
                        <select name="employer_position" id="employerPosition"
                            class="form-select @error('employer_position') is-invalid @enderror">
                            <option value="">Select Title</option>
                            @foreach ($labour_positions as $position)
                                <option value="{{ $position->id }}"
                                    {{ old('employer_position') == $position->id ? 'selected' : '' }}>
                                    {{ $position->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            @error('employer_position')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    {{-- Employer --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">
                            Employer <span class="text-danger">*</span>
                        </label>
                        <select name="employer_supplier" id="employerSupplier"
                            class="form-select @error('employer_supplier') is-invalid @enderror">
                            <option value="">Select Employer</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('employer_supplier') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            @error('employer_supplier')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    {{-- Rate --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">
                            Rate <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-secondary text-light">$</span>
                            <input type="number" name="employer_rate" id="employerRate"
                                class="form-control @error('employer_rate') is-invalid @enderror" placeholder="0.00"
                                step="0.01" min="0" value="{{ old('employer_rate') }}">
                        </div>
                        {{-- <p class="form-text small mt-1 text-muted">
                            <i class="fa fa-info-circle me-1"></i>
                            Auto-filled when a known labour is selected.
                        </p> --}}
                        <div class="invalid-feedback d-block">
                            @error('employer_rate')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                </div>
            </div>

            <div class="text-center my-4">
                <button type="submit" class="btn btn-success px-5 text-uppercase fw-bold ls-1">
                    Add to Register
                </button>
            </div>

        </form>

    </div>
@endsection

<script>
    const labourAutocompleteUrl = "{{ route('admin.project.labour.autocomplete') }}";
    const labourRateUrl = "{{ route('admin.project.labour.rate') }}";
    const newRegionId = 6;
</script>

@push('scripts')
    <script src="{{ asset('js/modules/labour.js') }}"></script>
@endpush
