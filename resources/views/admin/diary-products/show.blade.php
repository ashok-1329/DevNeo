@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- ── Page Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 bg-secondary rounded px-3 py-2">
            <div>
                <h4 class="mb-1 text-uppercase ls-1 text-light">Dairy Product Details</h4>
                <nav class="small text-light">
                    <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('diary-products.index') }}" class="text-light text-decoration-none">Dairy Product
                        List</a>
                    <span class="mx-1">/</span>
                    <span>View Product</span>
                </nav>
            </div>
            <div class="d-flex gap-2">
                {{-- <a href="{{ route('diary-products.edit', $product->id) }}" class="btn btn-success btn-sm">
                    <i class="fa fa-edit me-1"></i> Edit
                </a> --}}
                <a href="{{ route('diary-products.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="supplier-form-body mb-3">
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-uppercase">Product Name</label>
                    <input type="text" class="form-control show-readonly" value="{{ $product->name ?? '-' }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-uppercase">Category</label>
                    <input type="text" class="form-control show-readonly" value="{{ $product->category->name ?? '-' }}"
                        readonly>
                </div>

                {{-- <div class="col-md-6">
                    <label class="form-label fw-semibold small text-uppercase">Status</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $product->status == 1 ? 'Active' : 'Inactive' }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-uppercase">Created</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $product->created_at->format('d M Y') }}" readonly>
                </div> --}}

            </div>
        </div>

    </div>
@endsection
