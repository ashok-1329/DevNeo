@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- ── Page Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 bg-secondary rounded px-3 py-2">
            <div>
                <h4 class="mb-1 text-uppercase ls-1 text-light">Edit Client</h4>
                <nav class="small text-light">
                    <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('clients.index') }}" class="text-light text-decoration-none">Client List</a>
                    <span class="mx-1">/</span>
                    <span>Edit Client</span>
                </nav>
            </div>
            <div class="d-flex gap-2">
                {{-- <a href="{{ route('clients.show', $client->id) }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-eye me-1"></i> View
                </a> --}}
                <a href="{{ route('clients.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <form action="{{ route('clients.update', $client->id) }}" method="POST" id="clientForm"
            enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <div class="form-fieldsclient">
                {{-- ── Basic Information ── --}}
                <div class="client-form-body mb-3">

                    <div class="row g-3 mb-3">
                        {{-- Client Name --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-uppercase">
                                Client Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="client_name"
                                class="form-control @error('client_name') is-invalid @enderror"
                                placeholder="Enter client name" value="{{ old('client_name', $client->client_name) }}">
                            <div class="invalid-feedback">
                                @error('client_name')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        {{-- ABN --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-uppercase">
                                ABN <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="client_abn"
                                class="form-control @error('client_abn') is-invalid @enderror"
                                placeholder="Enter ABN (11 digits)" maxlength="11" inputmode="numeric" pattern="[0-9]*"
                                value="{{ old('client_abn', $client->client_abn) }}">
                            <div class="invalid-feedback">
                                @error('client_abn')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-uppercase">
                                Phone <span class="text-danger">*</span>
                            </label>
                            <div class="input-group ">
                                <span class="input-group-text bg-secondary text-light"><i class="fa fa-phone"></i></span>
                                <input type="number" name="client_phone"
                                    class="form-control @error('client_phone') is-invalid @enderror"
                                    placeholder="Enter phone number"
                                    value="{{ old('client_phone', $client->client_phone) }}">
                            </div>
                            <div class="invalid-feedback d-block">
                                @error('client_phone')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        {{-- Representative --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-uppercase">
                                Representative <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="client_representative"
                                class="form-control @error('client_representative') is-invalid @enderror"
                                placeholder="Enter representative name"
                                value="{{ old('client_representative', $client->client_representative) }}">
                            <div class="invalid-feedback">
                                @error('client_representative')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        {{-- Rep Email --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-uppercase">
                                Representative Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="client_rep_email"
                                class="form-control @error('client_rep_email') is-invalid @enderror"
                                placeholder="Enter representative email"
                                value="{{ old('client_rep_email', $client->client_rep_email) }}">
                            <div class="invalid-feedback">
                                @error('client_rep_email')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        {{-- Account Email --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-uppercase">
                                Account Email
                            </label>
                            <input type="email" name="client_account_email"
                                class="form-control @error('client_account_email') is-invalid @enderror"
                                placeholder="Enter account email"
                                value="{{ old('client_account_email', $client->client_account_email) }}">
                            <div class="invalid-feedback">
                                @error('client_account_email')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- Terms --}}
                        {{-- <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase">Terms</label>
                        <input type="text" name="client_terms"
                            class="form-control @error('client_terms') is-invalid @enderror"
                            placeholder="Enter payment terms" value="{{ old('client_terms', $client->client_terms) }}">
                        <div class="invalid-feedback">
                            @error('client_terms')
                                {{ $message }}
                            @enderror
                        </div>
                    </div> --}}

                        {{-- Address --}}
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small text-uppercase">
                                Address <span class="text-danger">*</span>
                            </label>
                            <textarea name="client_address" rows="3" class="form-control @error('client_address') is-invalid @enderror"
                                placeholder="Enter full address">{{ old('client_address', $client->client_address) }}</textarea>
                            <div class="invalid-feedback">
                                @error('client_address')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ── Notes ── --}}
                <div class="client-form-body mb-3">
                    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
                    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
                    <label class="form-label fw-semibold small text-uppercase">Notes</label>

                    <input type="hidden" name="internal_note" id="clientNotesHidden"
                        value="{{ old('internal_note', $client->internal_note) }}">

                    <div id="clientEditor" style="height:180px;"></div>

                    <div class="invalid-feedback d-block">
                        @error('internal_note')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ── Logo ── --}}
            <div class="client-form-body mb-3 form-imageclient">
                <label class="form-label fw-semibold small text-uppercase">Client Logo</label>

                @if ($client->client_logo)
                    <div class="mb-2">
                        <p class="small text-muted mb-1">Current Logo:</p>
                        <img src="{{ asset('storage/' . $client->client_logo) }}" alt="Client Logo"
                            class="rounded border" style="height:80px; width:auto; object-fit:contain;">
                    </div>
                    <p class="small text-muted">Upload a new image below to replace it.</p>
                @endif

                <x-dropzone name="client_logo" type="image"  placeholder="add Logo" />

                @error('client_logo')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- ── Status ── --}}
            <div class="client-form-body mb-3" style="width: 100%;">
                <label class="form-label fw-semibold small text-uppercase">Status</label>
                <select name="status" class="form-select form-control @error('status') is-invalid @enderror">
                    <option value="1" {{ old('status', $client->status) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $client->status) == 0 ? 'selected' : '' }}>Inactive</option>
                    <option value="2" {{ old('status', $client->status) == 2 ? 'selected' : '' }}>Blocked</option>
                </select>
                <div class="invalid-feedback">
                    @error('status')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="text-center my-4">
                <button type="submit" class="btn btn-success px-5 text-uppercase fw-bold ls-1">
                    Update Client
                </button>
            </div>

        </form>

    </div>
@endsection

@push('styles')
    <style>
        .ls-1 {
            letter-spacing: .05em;
        }

        .client-form-body {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1.25rem;
        }

        .input-group~.invalid-feedback {
            display: block;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/modules/clients.js') }}"></script>
@endpush
