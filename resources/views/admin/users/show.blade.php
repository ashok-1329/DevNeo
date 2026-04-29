@extends('layouts.admin')

@section('content')

    <div class="card-container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">User Profile</h4>
            <div class="d-flex gap-2">
                {{-- <a href="{{ route('users.edit', $user->id) }}" class="btn btn-success">
                <i class="fa fa-pen me-1"></i> Edit
            </a> --}}
                <a href="{{ route('users.index') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        {{-- Profile Card --}}
        <div class="card p-4 mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-auto">
                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center text-white fw-bold"
                        style="width:72px;height:72px;font-size:1.8rem;">
                        {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                    </div>
                </div>
                <div class="col">
                    <h5 class="mb-1 fw-bold">{{ $user->first_name }} {{ $user->last_name }}</h5>
                    <p class="mb-1 text-muted"><i class="fa fa-envelope me-1"></i>{{ $user->email }}</p>
                    @if ($user->role)
                        <span class="badge bg-secondary text-light">{{ $user->role->name }}</span>
                    @endif
                    @if ($user->status == 1)
                        <span class="badge bg-success ms-1 text-light">Active</span>
                    @else
                        <span class="badge bg-danger ms-1 text-light">Inactive</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-3" id="profileTabs" role="tablist">
            <li class="nav-item">
                <button class="profiletabs-link nav-link active" data-bs-toggle="tab" data-bs-target="#tab-details">
                    <i class="fa fa-user me-1"></i> Details
                </button>
            </li>
            <li class="nav-item">
                <button class="profiletabs-link nav-link" data-bs-toggle="tab" data-bs-target="#tab-certs">
                    <i class="fa fa-certificate me-1"></i> Certificates
                    {{-- @if ($user->certifications && $user->certifications->count())
                        <span class="badge bg-success ms-1">{{ $user->certifications->count() }}</span>
                    @endif --}}
                </button>
            </li>
            <li class="nav-item">
                <button class="profiletabs-link nav-link" data-bs-toggle="tab" data-bs-target="#tab-contract">
                    <i class="fa fa-file-contract me-1"></i> Contract
                </button>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ── Details Tab ── --}}
            <div class="tab-pane fade show active" id="tab-details">
                <div class="card p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1 fw-semibold text-uppercase">First Name</p>
                            <p class="mb-0 form-control">{{ $user->first_name ?: '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1 fw-semibold text-uppercase">Last Name</p>
                            <p class="mb-0 form-control">{{ $user->last_name ?: '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1 fw-semibold text-uppercase">Email</p>
                            <p class="mb-0 form-control">{{ $user->email ?: '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1 fw-semibold text-uppercase">Role</p>
                            <p class="mb-0 form-control">{{ optional($user->role)->name ?: '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1 fw-semibold text-uppercase">Start Date</p>
                            <p class="mb-0 form-control">{{ $user->start_date ?: '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1 fw-semibold text-uppercase">End Date</p>
                            <p class="mb-0 form-control">{{ $user->finish_date ?: '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1 fw-semibold text-uppercase">Status</p>
                            <p class="mb-0 form-control">
                                @if ($user->status == 1)
                                    <span class="">Active</span>
                                @else
                                    <span class="">Inactive</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1 fw-semibold text-uppercase">Created</p>
                            <p class="mb-0 form-control">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Certificates Tab ── --}}
            <div class="tab-pane fade" id="tab-certs">
                <div class="card p-4">
                    @if ($user->certifications && $user->certifications->count())
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Title</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Attachment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->certifications as $i => $cert)
                                        @php
                                            $expired =
                                                $cert->expiry_date &&
                                                \Carbon\Carbon::parse($cert->expiry_date)->isPast();
                                            $soon =
                                                !$expired &&
                                                $cert->expiry_date &&
                                                \Carbon\Carbon::parse($cert->expiry_date)->diffInDays(now()) <= 30;
                                        @endphp
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $cert->title->name }}</td>
                                            <td>{{ $cert->expiry_date ?: '—' }}</td>
                                            <td>
                                                @if ($expired)
                                                    <span class="badge bg-danger text-light">Expired</span>
                                                @elseif($soon)
                                                    <span class="badge bg-warning  text-light">Expiring Soon</span>
                                                @else
                                                    <span class="badge bg-success text-light">Valid</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($cert->file)
                                                    <a href="{{ Storage::url($cert->file) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa fa-paperclip me-1"></i>View
                                                    </a>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fa fa-certificate fa-2x mb-2 d-block opacity-25"></i>
                            No certificates on record.
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Contract Tab ── --}}
            <div class="tab-pane fade" id="tab-contract">
                <div class="card p-4">
                    @if ($user->contract)
                        @php $c = $user->contract; @endphp
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1 fw-semibold text-uppercase">Employment Type</p>
                                <p class="mb-0 form-control">
                                    {{ ucfirst(str_replace('_', ' ', $c->employment_name)) ?: '—' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1 fw-semibold text-uppercase">Hourly Rate</p>
                                <p class="mb-0 form-control">${{ number_format($c->salary_rate, 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1 fw-semibold text-uppercase">Payment Frequency</p>
                                <p class="mb-0 form-control">{{ ucfirst($c->payment_made) ?: '—' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1 fw-semibold text-uppercase">Timesheets Required</p>
                                <p class="mb-0 form-control">
                                    @if ($c->timesheet === 'yes')
                                        <span class="">Yes</span>
                                    @elseif($c->timesheet === 'no')
                                        <span class="">No</span>
                                    @else
                                        —
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1 fw-semibold text-uppercase">Staff Type</p>
                                <p class="mb-0 form-control">
                                    {{ ucfirst($c->staff ?? '') }} Staff
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1 fw-semibold text-uppercase">Contract File</p>
                                <p class="mb-0 form-control">
                                    @if ($c->file_path)
                                        <a href="{{ Storage::url($c->file_path) }}" target="_blank"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-file-pdf me-1 text-danger"></i>
                                            {{ basename($c->file_name) }}
                                        </a>
                                    @else
                                        <span class="text-muted small">No file uploaded</span>
                                    @endif
                                </p>
                            </div>
                            @if ($c->notes)
                                <div class="col-12">
                                    <p class="text-muted small mb-1 fw-semibold text-uppercase">Notes</p>
                                    <p class="mb-0 form-control" style="white-space:pre-wrap">{{ $c->notes }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fa fa-file-contract fa-2x mb-2 d-block opacity-25"></i>
                            No contract on record.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

@endsection
