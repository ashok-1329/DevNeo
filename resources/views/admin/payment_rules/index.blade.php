@extends('layouts.admin')

@section('content')
<div class="card-container">

    <div class="pr-page-header mb-4">
        <h4 class="mb-0 text-uppercase fw-bold ls-1">Payment Rules</h4>
    </div>

    {{-- ── Filters ── --}}
    <div class="card p-3 mb-3 bg-secondary">
        <div class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label fw-semibold small mb-1 text-light">SUPPLIER</label>
                <select id="filterSupplier" class="form-select form-select-sm" style="min-width:180px">
                    <option value="">All Suppliers</option>
                    @foreach ($suppliers as $s)
                        <option value="{{ $s->supplier_name }}">{{ $s->supplier_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label fw-semibold small mb-1 text-light">FREQUENCY</label>
                <select id="filterFrequency" class="form-select form-select-sm" style="min-width:160px">
                    <option value="">All Frequencies</option>
                    @foreach ($frequencyPayments as $fp)
                        <option value="{{ $fp->name }}">{{ $fp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label fw-semibold small mb-1 text-light">STATUS</label>
                <select id="filterStatus" class="form-select form-select-sm" style="min-width:130px">
                    <option value="">All</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <div class="col-auto ms-auto d-flex gap-2 flex-wrap">
                <a href="{{ route('payment-rules.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus me-1"></i> Add Payment Rule
                </a>
                <button id="btnExport" class="btn btn-success btn-sm">
                    <i class="fa fa-file-export me-1"></i> Export
                </button>
                <button id="btnClearFilter" class="btn btn-success btn-sm">
                    <i class="fa fa-filter me-1"></i> Clear Filter
                </button>
            </div>
        </div>
    </div>

    {{-- ── Table ── --}}
    <div class="card p-3">
        <div class="table-responsive">
            <table id="paymentRulesTable"
                   class="table table-striped table-bordered display nowrap w-100">
                <thead>
                    <tr>
                        <th>SUPPLIER</th>
                        <th>PAYMENT DATE</th>
                        <th>FREQUENCY</th>
                        <th>END DATE</th>
                        <th>VALUE (INC. GST)</th>
                        <th>PROJECT NUMBER</th>
                        <th>PROJECT CODE</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .pr-page-header {
        background: var(--bs-secondary, #6c757d);
        color: #fff;
        padding: 1rem 1.25rem;
        border-radius: 0.375rem;
    }
    .ls-1 { letter-spacing: .05em; }
    #paymentRulesTable th { white-space: nowrap; font-size: 0.78rem; }
    #paymentRulesTable td { vertical-align: middle; font-size: 0.82rem; }
    .action-btn { padding: 3px 7px; font-size: 0.75rem; }
</style>
@endpush

<script>
    const paymentRulesDataUrl = "{{ route('payment-rules.data') }}";
    const paymentRuleBaseUrl  = "{{ url('payment-rules') }}";
    const csrfToken           = "{{ csrf_token() }}";
</script>

@push('scripts')
    <script src="{{ asset('js/modules/payment-rules.js') }}"></script>
@endpush