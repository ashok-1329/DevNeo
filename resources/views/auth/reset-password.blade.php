@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')

<form method="POST" action="{{ route('password.store') }}">
    @csrf

    <!-- TOKEN -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <!-- EMAIL -->
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $request->email) }}" required autofocus>

        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- PASSWORD -->
    <div class="mb-3 position-relative">
        <label class="form-label">New Password</label>
        <input type="password" id="password" name="password"
            class="form-control @error('password') is-invalid @enderror" required>

        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <!-- EYE ICON -->
        <i class="fa fa-eye position-absolute"
           style="top: 38px; right: 15px; cursor:pointer;"
           onclick="togglePassword('password')"></i>
    </div>

    <!-- CONFIRM PASSWORD -->
    <div class="mb-3 position-relative">
        <label class="form-label">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation"
            class="form-control @error('password_confirmation') is-invalid @enderror" required>

        @error('password_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <!-- EYE ICON -->
        <i class="fa fa-eye position-absolute"
           style="top: 38px; right: 15px; cursor:pointer;"
           onclick="togglePassword('password_confirmation')"></i>
    </div>

    <!-- BUTTON -->
    <div class="text-end">
        <button class="btn btn-success">
            Reset Password
        </button>
    </div>

</form>

@endsection

@push('scripts')
<script>
function togglePassword(id) {
    let input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
