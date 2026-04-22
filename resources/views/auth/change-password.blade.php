@extends('layouts.admin')

@section('content')

<div class="card-container">

    <div class="card p-4">

        <h3 class="text-center mb-4">Change Password</h3>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('change.password.update') }}" id="changePasswordForm">
            @csrf

            <div class="row justify-content-center">

                <div class="col-md-6 mb-3 position-relative">
                    <label>Old Password <span class="danger">*</span></label>
                    <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror">
                    <span class="toggle-password" onclick="togglePassword(this)">
                        <i class="fa fa-eye"></i>
                    </span>
                @error('old_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                </div>

                <div class="col-md-6 mb-3 position-relative">
                    <label>New Password <span class="danger">*</span></label>
                    <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                    <span class="toggle-password" onclick="togglePassword(this)">
                        <i class="fa fa-eye"></i>
                    </span>
                    @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3 position-relative">
                    <label>Confirm Password <span class="danger">*</span></label>
                    <input type="password" name="new_password_confirmation" class="form-control @error('new_password') is-invalid @enderror">
                    <span class="toggle-password" onclick="togglePassword(this)">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>

            </div>

            <div class="text-center mt-3">
                <button class="btn btn-success px-4">Update Password</button>
            </div>

        </form>

    </div>

</div>
@endsection
@push('scripts')
<script src="{{ asset('js/modules/profile.js') }}"></script>
@endpush
