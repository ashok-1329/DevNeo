@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between mb-4 bg-secondary px-3 py-2 rounded">
            <h4 class="text-light mb-0">Edit Plant</h4>

            <div>
                <a href="{{ route('plant.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <form action="{{ route('plant.update', $plant->id) }}" method="POST" id="plantForm">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">

                {{-- PROJECT --}}
                <div class="col-md-6">
                    <label class="form-label">Project *</label>
                    <select name="project_id" id="projectSelect"
                        class="form-select @error('project_id') is-invalid @enderror">

                        <option value="">Select Project</option>

                        @foreach ($projects as $p)
                            <option value="{{ $p->id }}"
                                {{ old('project_id', $plant->project_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->project_name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="invalid-feedback">
                        @error('project_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                {{-- PLANT TYPE --}}
                <div class="col-md-6">
                    <label class="form-label">Plant Type *</label>
                    <select name="plant_type" id="plantTypeSelect"
                        class="form-select @error('plant_type') is-invalid @enderror">

                        <option value="">Select Type</option>
                        <option value="1" {{ old('plant_type', $plant->plant_type) == 1 ? 'selected' : '' }}>Owned
                        </option>
                        <option value="2" {{ old('plant_type', $plant->plant_type) == 2 ? 'selected' : '' }}>Hired
                        </option>

                    </select>

                    <div class="invalid-feedback">
                        @error('plant_type')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

            </div>

            <div class="row g-3 mb-3">

                {{-- PLANT NAME --}}
                <div class="col-md-6">
                    <label class="form-label">Plant Description *</label>
                    <input type="text" name="plant_name" id="plantNameInput"
                        class="form-control @error('plant_name') is-invalid @enderror"
                        value="{{ old('plant_name', $plant->plant_name) }}">

                    <div class="invalid-feedback">
                        @error('plant_name')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                {{-- PLANT CODE --}}
                <div class="col-md-6">
                    <label class="form-label">Asset ID *</label>
                    <input type="text" name="plant_code" id="plantCodeInput"
                        class="form-control @error('plant_code') is-invalid @enderror"
                        value="{{ old('plant_code', $plant->plant_code) }}">

                    <div class="invalid-feedback">
                        @error('plant_code')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

            </div>

            <div class="row g-3 mb-3">

                {{-- CAPACITY --}}
                <div class="col-md-6">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="plant_capacity" id="capacityInput"
                        class="form-control @error('plant_capacity') is-invalid @enderror"
                        value="{{ old('plant_capacity', $plant->plant_capacity) }}">

                    <div class="invalid-feedback">
                        @error('plant_capacity')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                {{-- SUPPLIER --}}
                <div class="col-md-6">
                    <label class="form-label">Supplier *</label>
                    <select name="supplier" id="supplierSelect" class="form-select @error('supplier') is-invalid @enderror">

                        <option value="">Select Supplier</option>

                        @foreach ($suppliers as $sup)
                            <option value="{{ $sup->id }}"
                                {{ old('supplier', $plant->supplier) == $sup->id ? 'selected' : '' }}>
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

            </div>

            <div class="row g-3 mb-3">

                {{-- UNIT --}}
                <div class="col-md-6">
                    <label class="form-label">Unit *</label>
                    <input type="text" name="unit" id="unitInput"
                        class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', $plant->unit) }}">

                    <div class="invalid-feedback">
                        @error('unit')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                {{-- RATE --}}
                <div class="col-md-6">
                    <label class="form-label">Rate *</label>
                    <input type="number" name="rate" id="rateInput"
                        class="form-control @error('rate') is-invalid @enderror" step="0.01"
                        value="{{ old('rate', $plant->rate) }}">

                    <div class="invalid-feedback">
                        @error('rate')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

            </div>

            {{-- IS DOCKET --}}
            <div class="mb-3">
                <label class="form-label">Is Docket Required *</label><br>

                <input type="radio" name="is_docket" value="1"
                    {{ old('is_docket', $plant->is_docket) == 1 ? 'checked' : '' }}> Yes

                <input type="radio" name="is_docket" value="0"
                    {{ old('is_docket', $plant->is_docket) == 0 ? 'checked' : '' }}> No

                <div id="docketFeedback" class="text-danger">
                    @error('is_docket')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            {{-- ADD TO DIARY --}}
            <div class="mb-3">
                <label class="form-label">Add To Diary *</label><br>

                <input type="radio" name="add_to_diary" value="1"
                    {{ old('add_to_diary', $plant->add_to_diary) == 1 ? 'checked' : '' }}> Yes

                <input type="radio" name="add_to_diary" value="0"
                    {{ old('add_to_diary', $plant->add_to_diary) == 0 ? 'checked' : '' }}> No

                <div id="diaryFeedback" class="text-danger">
                    @error('add_to_diary')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="text-center">
                <button class="btn btn-success">Update</button>
            </div>

        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/modules/plants.js') }}"></script>
@endpush
