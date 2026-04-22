@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')

<p class="text-muted mb-3">
    Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed to you.
    If you didn’t receive the email, we will gladly send you another.
</p>

{{-- SUCCESS MESSAGE --}}
@if (session('status') == 'verification-link-sent')
    <div class="alert alert-success">
        A new verification link has been sent to your email address.
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mt-3">

    <!-- RESEND EMAIL -->
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="btn btn-success">
            Resend Email
        </button>
    </form>

    <!-- LOGOUT -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-danger">
            Logout
        </button>
    </form>

</div>

@endsection
