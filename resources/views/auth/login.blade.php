@extends('layouts.auth')
@section('title', 'Login')
@section('heading', 'Lets Get Started')
@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email') }}" required>

        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


                <div class="mb-3 position-relative">
                <label>Password</label>
                <input type="password" id="password" name="password" class="form-control">

                <i class="fa fa-eye position-absolute"
                style="top: 38px; right: 15px; cursor:pointer;"
                onclick="togglePassword()"></i>
            </div>

    <div class="mb-3 form-check">
        <input type="checkbox" name="remember" class="form-check-input">
        <label class="form-check-label">Remember me</label>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('password.request') }}">Forgot Password?</a>

        <button class="btn btn-success">SIGN IN</button>
    </div>

</form>

@endsection
<script>
    function togglePassword() {
    let input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
