@extends('layouts.admin')

@section('content')

<div class="card-container">

    <div class="bg-secondary text-white p-3 mb-3 rounded">
        <h5 class="mb-0">MY DETAILS</h5>
    </div>

    <div class="card p-4">
        <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
            @csrf
            @method('PATCH')

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label>Employee No.</label>
                    <input type="text" class="form-control" value="{{ auth()->id() }}" disabled>
                </div>

                <div class="col-md-4 mb-3">
                    <label>First Name <span class="danger">*</span></label>

                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', auth()->user()->first_name) }}">
                         @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label>Last Name <span class="danger">*</span></label>
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', auth()->user()->last_name) }}">
                         @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Email <span class="danger">*</span></label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', auth()->user()->email) }}" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Start Date <span class="danger">*</span></label>
                    <input type="text" name="start_date" id="start_date" class="form-control datepicker" placeholder="dd/mm/yyyy"
                    value="{{ old('start_date', formatToUserDate(auth()->user()->start_date)) }}" disabled>
                </div>

                {{-- <div class="col-md-6 mb-3">
                    <label>Finish Date</label>
                    <input type="date" name="finish_date" class="form-control"
                        value="{{ old('finish_date', auth()->user()->finish_date) }}">
                </div> --}}

            </div>

            <div class="text-center mt-3">
                <button class="btn btn-success px-4">Update</button>
            </div>

        </form>

    </div>

</div>
@endsection
@push('scripts')
<script src="{{ asset('js/modules/profile.js') }}"></script>
@endpush
