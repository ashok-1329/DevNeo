@extends('layouts.admin')

@section('content')
    <div class="card-container">

        <div class="d-flex justify-content-between mb-4 bg-secondary px-3 py-2 rounded">
            <h4 class="text-light mb-0">Plants</h4>

            <div>
                <a href="{{ route('plant.create') }}" class="btn btn-success btn-sm">
                    Add Plant
                </a>
                <button id="btnExport" class="btn btn-success btn-sm">Export</button>
            </div>
        </div>


        <table id="plantTableProject" class="table table-striped table-bordered display nowrap w-100">
            <thead>
                <tr>
                    <th>Asset ID</th>
                    <th>Plant Type</th>
                    <th>Capacity</th>
                    <th>Supplier</th>
                    <th>Plant Description</th>
                    <th>Unit</th>
                    <th>Rate</th>
                    <th>Dockets</th>
                    {{-- <th>Assign to Project</th> --}}
                    <th class="text-center" style="width:130px;">Action</th>
                </tr>
            </thead>
        </table>

    </div>
@endsection

<script>
    const plantDataUrl = "{{ route('plant.data') }}";
    const plantBaseUrl = "{{ url('plant') }}";
    const csrfToken = "{{ csrf_token() }}";
</script>

@push('scripts')
    <script src="{{ asset('js/modules/plants.js') }}"></script>
@endpush
