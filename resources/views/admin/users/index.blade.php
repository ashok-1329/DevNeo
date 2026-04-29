@extends('layouts.admin')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="card-container">

        <div class="d-flex justify-content-between mb-3">
            <h4>Manage Users</h4>
            <a href="{{ route('users.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add New User
            </a>
        </div>

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

@push('scripts')
    <script>
        const usersDataUrl = "{{ route('users.data') }}";
        const userEditUrl = "{{ url('users') }}";
        const userShowUrl = "{{ url('users') }}";
        const userDeleteUrl = "{{ url('users') }}";
        {{-- These are unused on index but must be defined to avoid JS errors if users.js references them --}}
        const userStepUrl = "";
        const fileUploadUrl = "";
        const certGetUrl = "";
        const certUpdateUrl = "";
        const certDeleteUrl = "";
        const storageUrl = "{{ asset('storage') }}";
    </script>
    <script src="{{ asset('js/modules/users.js') }}"></script>
@endpush
