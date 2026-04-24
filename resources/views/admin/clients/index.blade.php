@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- ── Page Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 bg-secondary rounded px-3 py-2">
            <div>
                <h4 class="mb-1 text-uppercase ls-1 text-light">Clients</h4>
                <nav class="small text-light">
                    <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <span class="mx-1">/</span>
                    <span>Client List</span>
                </nav>
            </div>
            <a href="{{ route('clients.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus me-1"></i> Add Client
            </a>
        </div>

        {{-- ── Table ── --}}
        <div class="supplier-form-body">
            <table id="clientsTable" class="table table-striped table-bordered display nowrap w-100">
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>ABN</th>
                        <th>Phone</th>
                        <th>Representative</th>
                        <th>Rep Email</th>
                        <th>Status</th>
                        <th class="text-center" style="width:130px;">Action</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
@endsection

<script>
    const clientsDataUrl = "{{ route('clients.data') }}";
    const clientBaseUrl  = "{{ url('clients') }}";
    const csrfToken      = "{{ csrf_token() }}";
</script>


@push('scripts')
    <script src="{{ asset('js/modules/clients.js') }}"></script>
@endpush