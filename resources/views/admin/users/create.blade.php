@extends('layouts.admin')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="step-container">
    <div class="card p-4">

        <h4 class="mb-4">Create New User</h4>

        <!-- STEPS NAV -->
        <div class="mb-3 d-flex gap-2">
            <button class="btn btn-success step-btn" data-step="1">My Details</button>
            <button class="btn btn-light step-btn" data-step="2">Certificates</button>
            <button class="btn btn-light step-btn" data-step="3">Contract</button>
            <button class="btn btn-light step-btn" data-step="4">App Permission</button>
        </div>

        <!-- STEP 1 -->
        <div id="step1" class="step">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label for="first_name" class="form-label"> First Name<span class="danger">*</span> </label>
                    <input name="first_name" id="first_name" class="form-control" placeholder="First Name">
                </div>
                <div class="col-md-6 mb-2">
                    <label for="last_name" class="form-label"> Last Name<span class="danger">*</span> </label>
                    <input name="last_name" id="last_name" class="form-control" placeholder="Last Name">
                </div>
                <div class="col-md-6 mb-2">
                    <label for="email" class="form-label"> Email<span class="danger">*</span> </label>
                    <input name="email" id="email" class="form-control" placeholder="Email">
                </div>
                {{-- <div class="col-md-6 mb-2">
                    <label for="password" class="form-label"> Password<span class="danger">*</span> </label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                </div> --}}
                <div class="col-md-6 mb-2">
                    <label for="start_date" class="form-label"> Start Date<span class="danger">*</span> </label>
                    <input type="text" name="start_date" id="start_date" class="form-control datepicker" placeholder="dd/mm/yyyy">
                </div>
                <div class="col-md-6 mb-2">
                    <label for="end_date" class="form-label"> End Date</label>
                    <input type="text" name="end_date" id="end_date" class="form-control datepicker" placeholder="dd/mm/yyyy">

                </div>
            </div>
            <button type="button" class="btn btn-success mt-3" id="first_step">Next</button>
        </div>

        <!-- STEP 2 -->
        <div id="step2" class="step d-none">
            <button class="btn btn-dark mb-3" id="addCertBtn">Add Certificate</button>

            <ul id="certList"></ul>

            <button class="btn btn-secondary" id="second_prev">Previous</button>
            <button class="btn btn-success" id="second_step">Next</button>
        </div>

        <!-- STEP 3 -->
        <div id="step3" class="step d-none">
            <div id="contractDropzone" class="dropzone"></div>

            <button class="btn btn-secondary mt-3" id="third_prev">Previous</button>
            <button class="btn btn-success mt-3" id="third_step">Next</button>
        </div>

        <!-- STEP 4 -->
        <div id="step4" class="step d-none">

            <select id="roleSelect" class="form-control select2">
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>

            <button class="btn btn-secondary mt-3" id="last_prev">Previous</button>
            <button class="btn btn-success mt-3" id="last_step">Finish</button>
        </div>

    </div>
</div>
<!-- CERT MODAL -->

<div class="modal fade" id="certModal">
    <div class="modal-dialog">
        <div class="modal-content p-3">

            <select id="certTitle" class="form-select" name="title">
                <option value="">Select Title</option>

                @foreach($titles as $title)
                    <option value="{{ $title->id }}">{{ $title->name }}</option>
                @endforeach

                <option value="other">Other</option>
            </select>
            <div id="otherTitleDiv" class="mt-2 d-none">
                <input type="text" id="otherTitleInput" class="form-control" placeholder="Enter custom title">
            </div>
            <input id="certExpiry" name="expiry_date" class="form-control mb-2 datepicker" placeholder="Expiry Date">

            <div id="certDropzone" class="dropzone"></div>
            <button type="button" class="btn btn-success mt-2" id="save_cart">Save</button>

        </div>
    </div>
</div>
<script>
    const existingUser = @json($draft ?? null);
    const usersDataUrl = "{{ route('users.data') }}";
    const userStepUrl = "{{ route('users.step') }}";
    const userEditUrl = "{{ url('users') }}";

</script>
@endsection
@push('scripts')
<script src="{{ asset('js/modules/users.js') }}"></script>
@endpush
