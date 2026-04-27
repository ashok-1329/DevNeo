@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- ── Page Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 bg-secondary rounded px-3 py-2">
            <div>
                <h4 class="mb-1 text-uppercase ls-1 text-light">Diary Subcontractors</h4>
                <nav class="small text-light">
                    <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <span class="mx-1">/</span>
                    <span>Diary Subcontractors</span>
                </nav>
            </div>
            <div>
                <a href="{{ route('subcontractors.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus me-1"></i> Add New Subcontractor
                </a>
                <button id="btnExport" class="btn btn-success btn-sm">
                    <i class="fa fa-download me-1"></i> Export CSV
                </button>
                <button id="btnClearFilter" class="btn btn-success btn-sm">
                    <i class="fa fa-times me-1"></i> Clear
                </button>
            </div>
        </div>

        {{-- ── Filter Bar ── --}}
        {{-- <div class="ds-filter-bar mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold small text-uppercase mb-1">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-secondary text-light">
                            <i class="fa fa-search"></i>
                        </span>
                        <input type="text" id="globalSearch" class="form-control"
                            placeholder="Search by name, rep, work type, asset ID…">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-uppercase mb-1">Dockets</label>
                    <select id="filterDocket" class="form-select">
                        <option value="">All</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-uppercase mb-1">Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div> --}}

        {{-- ── Table ── --}}
        <div class="ds-table-card">
            <table id="diarySubcontractorsTable" class="table table-striped table-bordered display nowrap w-100">
                <thead>
                    <tr>
                        <th>Business Name</th>
                        <th>Representative</th>
                        <th>Asset ID</th>
                        <th>Type of Work</th>
                        <th>Dockets Required</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 130px">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
@endsection

<script>
    const dsDataUrl = "{{ route('subcontractors.data') }}";
    const dsBaseUrl = "{{ url('subcontractors') }}";
    const csrfToken = "{{ csrf_token() }}";
</script>

@push('scripts')
    <script src="{{ asset('js/modules/subcontractors.js') }}"></script>
@endpush
