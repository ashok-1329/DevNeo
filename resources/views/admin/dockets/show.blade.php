@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between mb-4 bg-secondary px-3 py-2 rounded">
            <h4 class="text-light mb-0 text-uppercase">Docket Detail</h4>

            <div>
                <a href="{{ route('dockets.edit', $docket->id) }}" class="btn btn-warning btn-sm text-white">
                    <i class="fa fa-pencil me-1"></i> Edit
                </a>

                <a href="{{ route('dockets.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="text-muted">Docket Number</label>
                <div class="form-control bg-light">{{ $docket->docket_number }}</div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Docket Date</label>
                <div class="form-control bg-light">{{ $docket->docket_date }}</div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Supplier</label>
                <div class="form-control bg-light">
                    {{ $docket->supplierRelation?->name ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Submitted Date</label>
                <div class="form-control bg-light">{{ $docket->submitted_date }}</div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Cost Code</label>
                <div class="form-control bg-light">{{ $docket->job_code }}</div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Cartage Provider</label>
                <div class="form-control bg-light">
                    {{ $docket->subcontractor?->name ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Category</label>
                <div class="form-control bg-light">{{ $docket->category }}</div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">File</label>
                <div class="form-control bg-light">
                    @if ($docket->docket_file)
                        <a href="{{ asset('storage/' . $docket->docket_file) }}" target="_blank">
                            View File
                        </a>
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="col-12">
                <label class="text-muted">Notes</label>
                <div class="form-control bg-light" style="min-height: 80px;">
                    {{ $docket->notes ?: '-' }}
                </div>
            </div>

        </div>

        <div class="text-center mt-4">
            <button class="btn btn-danger" id="btnDeleteDocket" data-id="{{ $docket->id }}">
                Delete
            </button>
        </div>

    </div>
@endsection

<script>
    const docketBaseUrl = "{{ url('dockets') }}";
    const csrfToken = "{{ csrf_token() }}";
</script>

@push('scripts')
    <script src="{{ asset('js/modules/dockets.js') }}"></script>
@endpush
