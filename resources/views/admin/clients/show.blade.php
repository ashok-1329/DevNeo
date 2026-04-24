@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- ── Page Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 bg-secondary rounded px-3 py-2">
            <div>
                <h4 class="mb-1 text-uppercase ls-1 text-light">Client Details</h4>
                <nav class="small text-light">
                    <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('clients.index') }}" class="text-light text-decoration-none">Client List</a>
                    <span class="mx-1">/</span>
                    <span>View Client</span>
                </nav>
            </div>
            <div class="d-flex gap-2">
                {{-- <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-success btn-sm">
                    <i class="fa fa-edit me-1"></i> Edit
                </a> --}}
                <a href="{{ route('clients.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        {{-- ── Basic Information ── --}}
        <div class="client-form-body mb-3">

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Client Name</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $client->client_name ?? '-' }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">ABN</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $client->client_abn ?? '-' }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Phone</label>
                    <div class="input-group ">
                        <span class="input-group-text bg-secondary text-light">
                            <i class="fa fa-phone"></i>
                        </span>
                        <input type="text" class="form-control show-readonly" value="{{ $client->client_phone ?? '-' }}"
                            readonly>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Representative</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $client->client_representative ?? '-' }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Representative Email</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $client->client_rep_email ?? '-' }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Account Email</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $client->client_account_email ?? '-' }}" readonly>
                </div>
            </div>

            <div class="row g-3">
                {{-- <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Terms</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $client->client_terms ?? '-' }}" readonly>
                </div> --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Status</label>
                    <input type="text" class="form-control show-readonly"
                        value="{{ $client->status == 1 ? 'Active' : ($client->status == 0 ? 'Inactive' : 'Blocked') }}"
                        readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Address</label>
                    <textarea class="form-control show-readonly" rows="3" readonly>{{ $client->client_address ?? '-' }}</textarea>
                </div>
            </div>

        </div>

        {{-- ── Notes ── --}}
        <div class="client-form-body mb-3">
            <label class="form-label fw-semibold small text-uppercase">Notes</label>
            <div class="show-notes-display">
                {!! $client->internal_note ?? '<span class="text-muted">No notes.</span>' !!}
            </div>
        </div>

        {{-- ── Logo ── --}}
        @if ($client->client_logo)
            <div class="client-form-body mb-4">
                <label class="form-label fw-semibold small text-uppercase">Client Logo</label><br>
                <img src="{{ asset('storage/' . $client->client_logo) }}" alt="Client Logo" class="rounded border mt-1"
                    style="height:200px; width:auto; object-fit:contain;">
            </div>
        @endif

    </div>
@endsection
