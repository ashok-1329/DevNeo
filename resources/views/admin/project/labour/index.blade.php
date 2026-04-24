@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- ── Page Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 bg-secondary rounded px-3 py-2">
            <div>
                <h4 class="mb-1 text-uppercase ls-1 text-light">Labour Register</h4>
                <nav class="small text-light">
                    <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <span class="mx-1">/</span>
                    <span>Labour List</span>
                </nav>
            </div>
            <a href="{{ route('admin.project.labour.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus me-1"></i> Add Labour
            </a>
        </div>

        {{-- ── Table ── --}}
        <div class="supplier-form-body">
            <table id="labourTable" class="table table-striped table-bordered display nowrap w-100">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Employment Type</th>
                        <th>Position</th>
                        <th>Employer</th>
                        <th>Region</th>
                        <th>Rate</th>
                        {{-- <th>Type</th> --}}
                        <th class="text-center" style="width:130px;">Action</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
@endsection

<script>
    const labourDataUrl = "{{ route('admin.project.labour.data') }}";
    const labourBaseUrl = "{{ url('project/labour') }}";
    const csrfToken = "{{ csrf_token() }}";
</script>

@push('scripts')
    <script src="{{ asset('js/modules/labour.js') }}"></script>
@endpush
