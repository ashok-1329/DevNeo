@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between mb-4 bg-secondary px-3 py-2 rounded">
            <h4 class="text-light mb-0 text-uppercase">Dockets</h4>

            <div class="d-flex gap-2">
                <a href="{{ route('dockets.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus me-1"></i> Add Docket
                </a>

                <button id="btnExport" class="btn btn-success btn-sm">
                    <i class="fa fa-download me-1"></i> Export
                </button>
            </div>
        </div>

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="ds-table-card">
            <table id="docketsTable" class="table table-striped table-bordered display nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Docket No</th>
                        <th>Supplier</th>
                        <th>Cost Code</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
@endsection

<script>
    const docketDataUrl = "{{ route('dockets.data') }}";
    const docketBaseUrl = "{{ url('dockets') }}";
    const csrfToken = "{{ csrf_token() }}";
</script>

@push('scripts')
    <script src="{{ asset('js/modules/dockets.js') }}"></script>
@endpush
