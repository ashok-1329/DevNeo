@extends('layouts.admin')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="card-container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Edit User — {{ $user->first_name }} {{ $user->last_name }}</h4>
            <a href="{{ route('users.index') }}" class="btn btn-success">
                <i class="fa fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card p-4">

            {{-- STEP NAV --}}
            <div class="mb-4 d-flex gap-2 flex-wrap">
                <button class="btn btn-success step-btn" data-step="1">
                    <i class="fa fa-user me-1"></i> My Details
                </button>
                <button
                    class="btn {{ $user->certifications && $user->certifications->count() ? 'btn-success' : 'btn-light' }} step-btn"
                    data-step="2">
                    <i class="fa fa-certificate me-1"></i> Certificates
                </button>
                <button class="btn {{ $user->contract ? 'btn-success' : 'btn-light' }} step-btn" data-step="3">
                    <i class="fa fa-file-contract me-1"></i> Contract
                </button>
                <button class="btn {{ $user->role_id ? 'btn-success' : 'btn-light' }} step-btn" data-step="4">
                    <i class="fa fa-shield-alt me-1"></i> App Permission
                </button>
            </div>

            {{-- ==============================
                 STEP 1 – PERSONAL DETAILS
            ============================== --}}
            <div id="step1" class="step">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" class="form-control"
                            placeholder="First Name" value="{{ $user->first_name }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name"
                            value="{{ $user->last_name }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email Address"
                            value="{{ $user->email }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                        <input type="text" name="start_date" id="start_date" class="form-control datepicker"
                            placeholder="dd/mm/yyyy"
                            value="{{ $user->start_date ? \Carbon\Carbon::parse($user->start_date)->format('d/m/Y') : '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">End Date</label>
                        <input type="text" name="end_date" id="end_date" class="form-control datepicker"
                            placeholder="dd/mm/yyyy"
                            value="{{ $user->finish_date ? \Carbon\Carbon::parse($user->finish_date)->format('d/m/Y') : '' }}">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" class="btn btn-success" id="first_step">
                        Save &amp; Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            {{-- ==============================
                 STEP 2 – CERTIFICATES
            ============================== --}}
            <div id="step2" class="step d-none">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-semibold">Certificates &amp; Qualifications</h6>
                    <button type="button" class="btn btn-dark" id="addCertBtn">
                        <i class="fa fa-plus me-1"></i> Add Certificate
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="certTable">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Title</th>
                                <th>Expiry Date</th>
                                <th>Attachment</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($certifications as $i => $cert)
                                <tr id="cert-row-{{ $cert->id }}">
                                    <td>{{ $i + 1 }}</td>
                                    {{-- ✅ Fixed: was $cert->title (relationship object) --}}
                                    <td>{{ $cert->custom_title ?: optional($cert->title)->name ?? '—' }}</td>
                                    {{-- ✅ Fixed: format date for display --}}
                                    <td>{{ $cert->expiry_date ? \Carbon\Carbon::parse($cert->expiry_date)->format('d/m/Y') : '—' }}
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
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm me-1 btn-edit-cert"
                                            data-id="{{ $cert->id }}" title="Edit">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm btn-del-cert"
                                            data-id="{{ $cert->id }}" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="certTableEmpty">
                                    <td colspan="5" class="text-center text-muted py-3">
                                        <i class="fa fa-inbox me-1"></i> No certificates added yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="second_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="second_step">
                        Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            {{-- ==============================
                 STEP 3 – CONTRACT
            ============================== --}}
            <div id="step3" class="step d-none">
                @php
                    $contract = $user->contract;
                    // ✅ Decode staff_type once for use in checkboxes
                    $staffTypes = [];
                    if ($contract && $contract->staff) {
                        $staffTypes = is_string($contract->staff)
                            ? json_decode($contract->staff, true) ?? []
                            : (array) $contract->staff;
                    }
                @endphp
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Employment Type <span class="text-danger">*</span></label>
                        <select id="employment_type" class="form-select">
                            <option value="">Select Employment Type</option>
                            @foreach ([
            'full_time' => 'Full Time',
            'part_time' => 'Part Time',
            'casual' => 'Casual',
            'contract' => 'Contract',
            'subcontractor' => 'Subcontractor',
        ] as $val => $label)
                                {{-- ✅ Fixed: DB column is employment_name --}}
                                <option value="{{ $val }}"
                                    {{ optional($contract)->employment_name === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Hourly Rate <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            {{-- ✅ Fixed: DB column is salary_rate --}}
                            <input type="number" id="hourly_rate" class="form-control" placeholder="0.00"
                                min="0" step="0.01" value="{{ optional($contract)->salary_rate }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Payments Frequency <span
                                class="text-danger">*</span></label>
                        <select id="payment_frequency" class="form-select">
                            <option value="">Select Payments Frequency</option>
                            @foreach (['weekly' => 'Weekly', 'fortnightly' => 'Fortnightly', 'monthly' => 'Monthly'] as $val => $label)
                                {{-- ✅ Fixed: DB column is payment_made --}}
                                <option value="{{ $val }}"
                                    {{ optional($contract)->payment_made === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Timesheets Required <span
                                class="text-danger">*</span></label>
                        <select id="timesheet_required" class="form-select">
                            <option value="">Select</option>
                            {{-- ✅ Fixed: DB column is timesheet --}}
                            <option value="yes" {{ optional($contract)->timesheet === 'daily' ? 'selected' : '' }}>
                                Daily
                            </option>
                            <option value="yes" {{ optional($contract)->timesheet === 'weekly' ? 'selected' : '' }}>
                                Weekly
                            </option>
                            <option value="yes"
                                {{ optional($contract)->timesheet === 'fortnightly' ? 'selected' : '' }}>Fortnightly
                            </option>
                            <option value="no" {{ optional($contract)->timesheet === 'monthly' ? 'selected' : '' }}>
                                Monthly
                            </option>
                        </select>
                    </div>

                    {{-- Staff type checkboxes --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold d-block mb-3">Staff Type</label>

                        <div class="d-flex gap-4">

                            <!-- Office Staff -->
                            <div class="custom-radio-option">
                                <input type="radio" name="staff_type" value="office" id="staffOffice"
                                    {{ ($contract->staff ?? '') === 'office' ? 'checked' : '' }}>
                                <label for="staffOffice" class="option-label text-success">
                                    <span class="check-circle">
                                        <i class="fa fa-check check-icon"></i>
                                    </span>
                                    <i class="fa fa-building me-2"></i>
                                    Office Staff
                                </label>
                            </div>

                            <!-- Field Staff -->
                            <div class="custom-radio-option">
                                <input type="radio" name="staff_type" value="field" id="staffField"
                                    {{ ($contract->staff ?? '') === 'field' ? 'checked' : '' }}>
                                <label for="staffField" class="option-label">
                                    <span class="check-circle">
                                        <i class="fa fa-check check-icon"></i>
                                    </span>
                                    <i class="fa fa-hard-hat me-2"></i>
                                    Field Staff
                                </label>
                            </div>

                        </div>
                    </div>

                    {{-- Contract file upload --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Contract File (PDF Only) <span class="text-danger">*</span>
                        </label>
                        {{-- ✅ Fixed: DB column is file_path --}}
                        <x-dropzone name="contract_file" id="contractFile" type="document" :existing="optional($contract)->file_path"
                            placeholder="Drag and drop a file here or click to browse" />
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea id="notes" class="form-control" rows="3" placeholder="Additional notes...">{{ optional($contract)->notes }}</textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="third_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="third_step">
                        Save &amp; Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            {{-- ==============================
                 STEP 4 – APP PERMISSION
            ============================== --}}
            <div id="step4" class="step d-none">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Access Level / Role <span
                                class="text-danger">*</span></label>
                        <select id="roleSelect" class="form-select">
                            <option value="">Select Access Level</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="last_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="last_step">
                        <i class="fa fa-check me-1"></i> Save Changes
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- ==============================
         CERT MODAL
    ============================== --}}
    <div class="modal fade" id="certModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="certModalLabel">Add Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Certification Title <span
                                class="text-danger">*</span></label>
                        <select id="certTitle" class="form-select">
                            <option value="">Select Title</option>
                            @foreach ($titles as $title)
                                <option value="{{ $title->id }}">{{ $title->name }}</option>
                            @endforeach
                            <option value="other">Other (Custom)</option>
                        </select>
                    </div>

                    <div id="otherTitleDiv" class="mb-3 d-none">
                        <label class="form-label fw-semibold">Custom Title</label>
                        <input type="text" id="otherTitleInput" class="form-control"
                            placeholder="Enter custom title">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Expiry Date</label>
                        <input type="text" id="certExpiry" class="form-control datepicker" placeholder="dd/mm/yyyy">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Certificate File</label>
                        <x-dropzone name="cert_file" id="certFile" type="document"
                            placeholder="Upload Certificate (PDF, Word, Image)" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="save_cert">
                        <i class="fa fa-save me-1"></i> Save Certificate
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        {{-- ✅ Use pre-formatted data from controller --}}
        const existingUser = @json($existingUserJs);
        const existingCerts = @json($existingCerts->count() ? $existingCerts : null);
        const existingContract = @json($existingContract);
        const existingRole = "{{ $user->role_id }}";

        const completedStepsData = [
            @if ($user->id)
                1,
            @endif
            @if ($certifications && $certifications->count())
                2,
            @endif
            @if ($user->contract)
                3,
            @endif
            @if ($user->role_id)
                4,
            @endif
        ];

        const usersDataUrl = "{{ route('users.data') }}";
        const userStepUrl = "{{ route('users.step') }}";
        const userEditUrl = "{{ url('users') }}";
        const userShowUrl = "{{ url('users') }}";
        const userDeleteUrl = "{{ url('users') }}";
        const fileUploadUrl = "{{ route('users.upload') }}";
        const certGetUrl = "{{ url('users/cert') }}/:id/get";
        const certUpdateUrl = "{{ url('users/cert') }}/:id/update";
        const certDeleteUrl = "{{ url('users/cert') }}/:id";
        const storageUrl = "{{ asset('storage') }}";
    </script>
    <script src="{{ asset('js/modules/users.js') }}"></script>
@endpush
