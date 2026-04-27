@extends('layouts.admin')

@section('content')
    <div class="card-container">

        <div class="d-flex justify-content-between mb-4 bg-secondary px-3 py-2 rounded">
            <h4 class="text-light mb-0">Material Detail</h4>

            <div>
                {{-- <a href="{{ route('materials.edit', $material->id) }}" class="btn btn-warning btn-sm text-white">
                    Edit
                </a> --}}
                <a href="{{ route('materials.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="text-muted">Category</label>
                <div class="form-control bg-light">
                    {{ $material->category?->name ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Item</label>
                <div class="form-control bg-light">
                    {{ $material->item }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Supplier</label>
                <div class="form-control bg-light">
                    {{ $material->supplier?->name ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Unit</label>
                <div class="form-control bg-light">
                    {{ $material->unit?->name ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Rate</label>
                <div class="form-control bg-light">
                    {{ $material->rate }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Is Docket</label>
                <div class="form-control bg-light">
                    {{ $material->is_docket ? 'Yes' : 'No' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Add To Diary</label>
                <div class="form-control bg-light">
                    {{ $material->add_to_diary ? 'Yes' : 'No' }}
                </div>
            </div>

        </div>

        <div class="text-center mt-4">
            <button class="btn btn-danger" id="btnDeleteMaterial" data-id="{{ $material->id }}">
                Delete
            </button>
        </div>

    </div>
@endsection

<script>
    const matBaseUrl = "{{ url('materials') }}";
    const csrfToken = "{{ csrf_token() }}";
</script>

@push('scripts')
    <script src="{{ asset('js/modules/materials.js') }}"></script>
@endpush
