@extends('layouts.admin')

@section('content')
    <div class="card-container">

        <div class="d-flex justify-content-between mb-4 bg-secondary px-3 py-2 rounded">
            <h4 class="text-light mb-0">Materials</h4>

            <div>
                <a href="{{ route('materials.create') }}" class="btn btn-success btn-sm">
                    Add Material
                </a>
                <button id="btnExport" class="btn btn-success btn-sm">Export</button>
            </div>
        </div>


        <table id="materialsTable" class="table table-striped table-bordered display nowrap w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Item</th>
                    <th>Supplier</th>
                    <th>Unit</th>
                    <th>Rate</th>
                    <th>Docket</th>
                    <th>Diary</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>

    </div>
@endsection

<script>
    const matDataUrl = "{{ route('materials.data') }}";
    const matBaseUrl = "{{ url('materials') }}";
    const csrfToken = "{{ csrf_token() }}";
</script>

@push('scripts')
    <script src="{{ asset('js/modules/materials.js') }}"></script>
@endpush
