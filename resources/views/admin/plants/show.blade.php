@extends('layouts.admin')

@section('content')
    <div class="card-container">

        <div class="d-flex justify-content-between mb-4 bg-secondary px-3 py-2 rounded">
            <h4 class="text-light mb-0">Plant Detail</h4>

            <div>
                {{-- Optional Edit Button --}}
                {{-- <a href="{{ route('plant.edit', $plant->id) }}" class="btn btn-warning btn-sm text-white">
                Edit
            </a> --}}

                <a href="{{ route('plant.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="text-muted">Project</label>
                <div class="form-control bg-light">
                    {{ $plant->project?->project_name ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Plant Type</label>
                <div class="form-control bg-light">
                    {{ $plant->plant_type == 1 ? 'Owned' : ($plant->plant_type == 2 ? 'Hired' : '-') }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Plant Description</label>
                <div class="form-control bg-light">
                    {{ $plant->plant_name }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Asset ID</label>
                <div class="form-control bg-light">
                    {{ $plant->plant_code }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Capacity</label>
                <div class="form-control bg-light">
                    {{ $plant->plant_capacity ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Supplier</label>
                <div class="form-control bg-light">
                    {{ $plant->supplierCategory?->name ?? ($plant->supplier ?? '-') }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Unit</label>
                <div class="form-control bg-light">
                    {{ $plant->unit }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Rate</label>
                <div class="form-control bg-light">
                    {{ number_format($plant->rate, 2) }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Is Docket</label>
                <div class="form-control bg-light">
                    {{ $plant->is_docket ? 'Yes' : 'No' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Add To Diary</label>
                <div class="form-control bg-light">
                    {{ $plant->add_to_diary ? 'Yes' : 'No' }}
                </div>
            </div>

            {{-- OPTIONAL EXTRA FIELDS --}}
            <div class="col-md-6">
                <label class="text-muted">Registration Number</label>
                <div class="form-control bg-light">
                    {{ $plant->registration_number ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Registration Expiry</label>
                <div class="form-control bg-light">
                    {{ $plant->registration_expiry_date ?? '-' }}
                </div>
            </div>

        </div>

        <div class="text-center mt-4">
            <button class="btn btn-danger" id="btnDeletePlant" data-id="{{ $plant->id }}">
                Delete
            </button>
        </div>

    </div>
@endsection

<script>
    const plantBaseUrl = "{{ url('plants') }}";
    const csrfToken = "{{ csrf_token() }}";
</script>

@push('scripts')
    <script src="{{ asset('js/modules/plants.js') }}"></script>
@endpush
