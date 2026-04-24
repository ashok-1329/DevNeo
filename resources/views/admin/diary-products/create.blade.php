@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- ── Page Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 bg-secondary rounded px-3 py-2">
            <div>
                <h4 class="mb-1 text-uppercase ls-1 text-light">Add Dairy Product</h4>
                <nav class="small text-light">
                    <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('diary-products.index') }}" class="text-light text-decoration-none">Dairy Product
                        List</a>
                    <span class="mx-1">/</span>
                    <span>Add Product</span>
                </nav>
            </div>
            <a href="{{ route('diary-products.index') }}" class="btn btn-success btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <form action="{{ route('diary-products.store') }}" method="POST" id="diaryProductForm" novalidate>
            @csrf

            <div class="supplier-form-body mb-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-uppercase">
                            Category <span class="text-danger">*</span>
                        </label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">Select Category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-uppercase">
                            Product Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            placeholder="Enter product name" value="{{ old('name') }}">
                        <div class="invalid-feedback">
                            @error('name')
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

@push('scripts')
    <script src="{{ asset('js/modules/diary-products.js') }}"></script>
@endpush
