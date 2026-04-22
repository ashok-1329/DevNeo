@extends('layouts.auth')
@section('title', 'Forgot Password')
@section('content')

<p class="text-muted mb-3">
    Forgot your password? Enter your email and we’ll send you a reset link.
</p>

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <!-- EMAIL -->
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email') }}" required autofocus>

        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="text-end">
        <button class="btn btn-success">
            Send Reset Link
        </button>
    </div>

</form>

@endsection
