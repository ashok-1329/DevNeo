@extends('layouts.admin')

@section('content')

<div class="card-container">

    <!-- HEADER -->
    <div class="d-flex justify-content-between mb-3">
        <h4>Manage Users</h4>

        <a href="{{ route('users.create') }}" class="btn btn-success">
            <i class="fa fa-plus"></i> Add New User
        </a>
    </div>

    <!-- TABLE -->
    <div class="card p-3">

       <table id="usersTable" class="table table-striped table-bordered display dataTable no-footer">
            <thead>
                <tr>
                    <th>S.NO.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>

    </div>

</div>

@endsection
<script>
    const usersDataUrl = "{{ route('users.data') }}";
    const userEditUrl = "{{ url('users') }}";
</script>
@push('scripts')
<script src="{{ asset('js/modules/users.js') }}"></script>
@endpush
