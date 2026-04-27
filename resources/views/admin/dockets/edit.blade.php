@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between mb-4 bg-secondary px-3 py-2 rounded">
            <h4 class="text-light mb-0 text-uppercase">Edit Docket</h4>

            <div>
                <a href="{{ route('dockets.show', $docket->id) }}" class="btn btn-info btn-sm text-white">
                    <i class="fa fa-eye me-1"></i> View
                </a>

                <a href="{{ route('dockets.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <form action="{{ route('dockets.update', $docket->id) }}" method="POST" id="docketForm"
            enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">

                {{-- DOCKET NUMBER --}}
                <div class="col-md-6">
                    <label class="form-label">Docket Number *</label>
                    <input type="text" name="docket_number" id="docketNumber"
                        class="form-control @error('docket_number') is-invalid @enderror"
                        value="{{ old('docket_number', $docket->docket_number) }}">

                    <div class="invalid-feedback">
                        @error('docket_number')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                {{-- DOCKET DATE --}}
                <div class="col-md-6">
                    <label class="form-label">Docket Date *</label>
                    <input type="date" name="docket_date" id="docketDate"
                        class="form-control @error('docket_date') is-invalid @enderror"
                        value="{{ old('docket_date', $docket->docket_date) }}">

                    <div class="invalid-feedback">
                        @error('docket_date')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

            </div>

            <div class="row g-3 mb-3">

                {{-- SUPPLIER --}}
                <div class="col-md-6">
                    <label class="form-label">Supplier *</label>
                    <select name="supplier" id="supplierSelect" class="form-select @error('supplier') is-invalid @enderror">

                        <option value="">Select Supplier</option>

                        @foreach ($suppliers as $sup)
                            <option value="{{ $sup->id }}"
                                {{ old('supplier', $docket->supplier) == $sup->id ? 'selected' : '' }}>
                                {{ $sup->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="invalid-feedback">
                        @error('supplier')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                {{-- SUBMITTED DATE --}}
                <div class="col-md-6">
                    <label class="form-label">Submitted Date *</label>
                    <input type="date" name="submitted_date" id="submittedDate"
                        class="form-control @error('submitted_date') is-invalid @enderror"
                        value="{{ old('submitted_date', $docket->submitted_date) }}">

                    <div class="invalid-feedback">
                        @error('submitted_date')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

            </div>

            <div class="row g-3 mb-3">

                {{-- COST CODE --}}
                <div class="col-md-6">
                    <label class="form-label">Cost Code *</label>
                    <input type="text" name="job_code" id="jobCode"
                        class="form-control @error('job_code') is-invalid @enderror"
                        value="{{ old('job_code', $docket->job_code) }}">

                    <div class="invalid-feedback">
                        @error('job_code')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                {{-- SUB CONTRACTOR --}}
                <div class="col-md-6">
                    <label class="form-label">Cartage Provider</label>
                    <select name="sub_contractor" id="subContractorSelect"
                        class="form-select @error('sub_contractor') is-invalid @enderror">

                        <option value="">Select Provider</option>

                        @foreach ($subcontractors as $sc)
                            <option value="{{ $sc->id }}"
                                {{ old('sub_contractor', $docket->sub_contractor) == $sc->id ? 'selected' : '' }}>
                                {{ $sc->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="invalid-feedback">
                        @error('sub_contractor')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

            </div>

            <div class="row g-3 mb-3">

                {{-- CATEGORY --}}
                <div class="col-md-6">
                    <label class="form-label">Category *</label>
                    <input type="text" name="category" id="categorySelect"
                        class="form-control @error('category') is-invalid @enderror"
                        value="{{ old('category', $docket->category) }}">

                    <div class="invalid-feedback">
                        @error('category')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                {{-- FILE --}}
                <div class="col-md-6">
                    <label class="form-label">Upload File</label>
                    <input type="file" name="docket_file" id="docketFile"
                        class="form-control @error('docket_file') is-invalid @enderror">

                    @if ($docket->docket_file)
                        <small class="text-muted d-block mt-1">
                            Current file:
                            <a href="{{ asset('storage/' . $docket->docket_file) }}" target="_blank">
                                View File
                            </a>
                        </small>
                    @endif

                    <div class="invalid-feedback">
                        @error('docket_file')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

            </div>

            {{-- NOTES --}}
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3">
{{ old('notes', $docket->notes) }}
            </textarea>
            </div>

            <div class="text-center">
                <button class="btn btn-success px-5">Update</button>
            </div>

        </form>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/modules/dockets.js') }}"></script>
@endpush
