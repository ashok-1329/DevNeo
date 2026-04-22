@extends('layouts.admin')

@section('content')
    <div class="card-container">

        <div class="supplier-page-header mb-4">
            <h4 class="mb-0 text-uppercase fw-bold ls-1">Supplier Register</h4>
        </div>

        <div class="card p-3 mb-3 bg-secondary">
            <div class="row g-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label fw-semibold small mb-1 text-light">CATEGORY:</label>
                    <select id="filterCategory" class="form-select form-select-sm" style="min-width:160px">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label class="form-label fw-semibold small mb-1 text-light">PAYMENT TERM</label>
                    <select id="filterPaymentTerm" class="form-select form-select-sm" style="min-width:180px">
                        <option value="">Select Payment Term</option>
                        @foreach ($paymentTerms as $pt)
                            <option value="{{ $pt }}">{{ $pt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto ms-auto d-flex gap-2 flex-wrap">
                    <a href="{{ route('suppliers.create') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Add New Supplier
                    </a>
                    <button id="btnExport" class="btn btn-success btn-sm">
                        <i class="fa fa-file-export"></i> Export Data
                    </button>
                    {{-- <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#importModal">
                        <i class="fa fa-file-import"></i> Import Excel
                    </button> --}}
                    <button id="btnClearFilter" class="btn btn-success btn-sm">
                        <i class="fa fa-filter"></i> Clear Filter
                    </button>
                </div>
            </div>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif --}}

        <div class="card p-3">
            <div class="table-responsive">
                <table id="suppliersTable" class="table table-striped table-bordered display nowrap w-100">
                    <thead>
                        <tr>
                            <th>CATEGORY</th>
                            <th>BUSINESS NAME</th>
                            <th>EMAIL</th>
                            <th>PHONE</th>
                            <th>ABN</th>
                            <th>ACCOUNT EMAIL ADDRESS</th>
                            <th>PAYMENT TERM</th>
                            <th>ADDRESS</th>
                            <th>NOTES</th>
                            <th>SUPPLIER RANK</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>

    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title"><i class="fa fa-file-import me-2"></i>Import Suppliers</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('suppliers.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            Upload an Excel (.xlsx, .xls) or CSV file. The file must have the following columns:
                            <strong>category, business_name, email, phone, abn, address, account_email, bank_name, bsb_no,
                                account_number, account_name, payment_terms, notes</strong>
                        </p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select File <span class="text-danger">*</span></label>
                            <input type="file" name="import_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <a href="{{ route('suppliers.import.template') }}" class="small text-primary">
                            <i class="fa fa-download me-1"></i> Download Template
                        </a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-upload me-1"></i> Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .supplier-page-header {
            background: var(--bs-secondary, #6c757d);
            color: #fff;
            padding: 1rem 1.25rem;
            border-radius: 0.375rem;
        }

        .ls-1 {
            letter-spacing: .05em;
        }

        .rank-select {
            font-size: 0.78rem;
            padding: 3px 6px;
            border-radius: 4px;
            border: 1px solid #ced4da;
            background: #fff;
            cursor: pointer;
            min-width: 130px;
        }

        .rank-select.rank-1 {
            background: #fde8e8;
            border-color: #dc3545;
            color: #dc3545;
        }

        .rank-select.rank-2 {
            background: #fff8db;
            border-color: #ffc107;
            color: #856404;
        }

        .rank-select.rank-3 {
            background: #d4edda;
            border-color: #198754;
            color: #155724;
        }

        .rank-select.rank-null {
            background: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
        }

        #suppliersTable th {
            white-space: nowrap;
            font-size: 0.78rem;
        }

        #suppliersTable td {
            vertical-align: middle;
            font-size: 0.82rem;
        }

        .action-btn {
            padding: 3px 7px;
            font-size: 0.75rem;
        }

        .notes-cell {
            max-width: 180px;
            white-space: normal;
            font-size: 0.78rem;
            color: #555;
        }
    </style>
@endpush

<script>
    const suppliersDataUrl = "{{ route('suppliers.data') }}";
    const supplierBaseUrl = "{{ url('suppliers') }}";
    const supplierRankUrl = "{{ url('suppliers') }}";
    const csrfToken = "{{ csrf_token() }}";
</script>

@push('scripts')
    <script src="{{ asset('js/modules/suppliers.js') }}"></script>
@endpush
