@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- ── Page Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 bg-secondary rounded px-3 py-2">
            <div>
                <h4 class="mb-1 text-uppercase ls-1 text-light">Labour Details</h4>
                <nav class="small text-light">
                    <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('admin.project.labour.index') }}" class="text-light text-decoration-none">Labour List</a>
                    <span class="mx-1">/</span>
                    <span>View Labour</span>
                </nav>
            </div>
            <div class="d-flex gap-2">
                {{-- <a href="{{ route('admin.project.labour.edit', $labour->id) }}" class="btn btn-success btn-sm">
                    <i class="fa fa-edit me-1"></i> Edit
                </a> --}}
                <a href="{{ route('admin.project.labour.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        {{-- ── Row 1: Name / Region / Employment Type ── --}}
        <div class="supplier-form-body mb-3">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Name</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $labour->name ?? '-' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Region</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $labour->region->name ?? '-' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Employment Type</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $labour->employmentType->name ?? '-' }}" readonly>
                </div>

            </div>
        </div>

        {{-- ── Row 2: Position / Employer / Rate ── --}}
        <div class="supplier-form-body mb-3">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Title / Position</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $labour->position->name ?? '-' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Employer</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $labour->employerSupplier->name ?? '-' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Rate</label>
                    <div class="input-group">
                        <span class="input-group-text bg-secondary text-light">$</span>
                        <input type="text" class="form-control show-readonly"
                            value="{{ number_format((float) $labour->rate, 2) }}" readonly>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Meta ── --}}
        <div class="supplier-form-body mb-3">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Labour Type</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $labour->labour_type == 1 ? 'Internal' : 'External' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Added On</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $labour->created_at?->format('d M Y') ?? '-' }}" readonly>
                </div>

            </div>
        </div>

    </div>
@endsection