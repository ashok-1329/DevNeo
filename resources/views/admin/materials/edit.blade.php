@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between mb-4 bg-secondary px-3 py-2 rounded">
            <h4 class="text-light mb-0">Edit Material</h4>

            <div>
                {{-- <a href="{{ route('materials.show', $material->id) }}" class="btn btn-info btn-sm text-white">
                    View
                </a> --}}
                <a href="{{ route('materials.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <form action="{{ route('materials.update', $material->id) }}" method="POST" id="matForm">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">

                {{-- PROJECT --}}
                <div class="col-md-6">
                    <label class="form-label">Project *</label>
                    <input type="text" name="project_id" id="projectSelect"
                        class="form-control @error('project_id') is-invalid @enderror"
                        value="{{ old('project_id', $material->project_id) }}">
                    <div class="invalid-feedback">
                        @error('project_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                {{-- CATEGORY --}}
                <div class="col-md-6">
                    <label class="form-label">Category *</label>
                    <select name="category_id" id="categorySelect"
                        class="form-select @error('category_id') is-invalid @enderror">

                        <option value="">Select Category</option>

                        @foreach ($productCategories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $material->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="invalid-feedback">
                        @error('category_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

            </div>

            <div class="row g-3 mb-3">

                {{-- ITEM --}}
                <div class="col-md-6">
                    <label class="form-label">Item *</label>
                    <input type="text" name="item" id="itemInput"
                        class="form-control @error('item') is-invalid @enderror" value="{{ old('item', $material->item) }}">
                    <div class="invalid-feedback">
                        @error('item')
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
                                {{ old('supplier', $material->supplier) == $sup->id ? 'selected' : '' }}>
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
                    <select name="unit_id" id="unitSelect" class="form-select @error('unit_id') is-invalid @enderror">

                        <option value="">Select Unit</option>

                        @foreach ($materialUnits as $unit)
                            <option value="{{ $unit->id }}"
                                {{ old('unit_id', $material->unit_id) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="invalid-feedback">
                        @error('unit_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                {{-- RATE --}}
                <div class="col-md-6">
                    <label class="form-label">Rate *</label>
                    <input type="number" name="rate" id="rateInput"
                        class="form-control @error('rate') is-invalid @enderror" step="0.01"
                        value="{{ old('rate', $material->rate) }}">
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
                    {{ old('is_docket', $material->is_docket) == '1' ? 'checked' : '' }}> Yes

                <input type="radio" name="is_docket" value="0"
                    {{ old('is_docket', $material->is_docket) == '0' ? 'checked' : '' }}> No

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
                    {{ old('add_to_diary', $material->add_to_diary) == '1' ? 'checked' : '' }}> Yes

                <input type="radio" name="add_to_diary" value="0"
                    {{ old('add_to_diary', $material->add_to_diary) == '0' ? 'checked' : '' }}> No

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
    <script src="{{ asset('js/modules/materials.js') }}"></script>
@endpush
