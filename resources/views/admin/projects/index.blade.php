@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between mb-4 bg-secondary px-3 py-2 rounded">
            <h4 class="text-light mb-0 text-uppercase">Projects</h4>

            <div class="d-flex gap-2">

                <button id="btnExport" class="btn btn-success btn-sm">
                    <i class="fa fa-download"></i> Export Data
                </button>

                {{-- <button id="btnClearFilter" class="btn btn-secondary btn-sm">
                    Clear
                </button> --}}
            </div>
        </div>

        <div class="ds-table-card">
            <div style="overflow-x: auto;">
                <table id="projectsTable" class="table table-striped table-bordered display nowrap w-100">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Project Name</th>
                            <th>Project No.</th>
                            <th>Project Code</th>
                            <th>Project Region</th>
                            <th>Project Client</th>
                            <th>Construction Manager</th>
                            <th>Project Manager</th>
                            <th>Supervisor</th>
                            <th>Project Engineer</th>
                            <th>Contract Admin</th>
                            <th>Date Commenced</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
@endsection

<script>
    
    const projectDataUrl = "{{ route('projects.data') }}";
    const projectBaseUrl = "{{ url('projects') }}";
    const csrfToken = "{{ csrf_token() }}";
    
</script>

@push('scripts')
    <script src="{{ asset('js/modules/projects.js') }}"></script>
@endpush
