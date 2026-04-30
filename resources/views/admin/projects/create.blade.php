@extends('layouts.admin')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="card-container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Create New Project</h4>
            <a href="{{ route('projects.index') }}" class="btn btn-success">
                <i class="fa fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card p-4 step-card">

            {{-- ══════════════════════════════════
             STEP NAVIGATION
        ══════════════════════════════════ --}}
            <div class="mb-4 d-flex gap-2 flex-wrap">
                <button class="btn {{ in_array(1, $completedSteps) ? 'btn-success' : 'btn-light' }} step-btn"
                    data-step="1">
                    <i class="fa fa-folder me-1"></i> Project Details
                </button>
                <button class="btn {{ in_array(2, $completedSteps) ? 'btn-success' : 'btn-light' }} step-btn"
                    data-step="2">
                    <i class="fa fa-user-tie me-1"></i> Client Details
                </button>
                <button class="btn {{ in_array(3, $completedSteps) ? 'btn-success' : 'btn-light' }} step-btn"
                    data-step="3">
                    <i class="fa fa-users me-1"></i> Assign Project Personnel
                </button>
                <button class="btn {{ in_array(4, $completedSteps) ? 'btn-success' : 'btn-light' }} step-btn"
                    data-step="4">
                    <i class="fa fa-file-contract me-1"></i> Contract Details
                </button>
                <button class="btn {{ in_array(5, $completedSteps) ? 'btn-success' : 'btn-light' }} step-btn"
                    data-step="5">
                    <i class="fa fa-file-contract me-1"></i> Contract Value
                </button>
                <button class="btn {{ in_array(6, $completedSteps) ? 'btn-success' : 'btn-light' }} step-btn"
                    data-step="5">
                    <i class="fa fa-file-contract me-1"></i> Bank Guarantee
                </button>
                <button class="btn {{ in_array(7, $completedSteps) ? 'btn-success' : 'btn-light' }} step-btn"
                    data-step="5">
                    <i class="fa fa-file-contract me-1"></i> Cash Retentions
                </button>
                <button class="btn {{ in_array(8, $completedSteps) ? 'btn-success' : 'btn-light' }} step-btn"
                    data-step="5">
                    <i class="fa fa-file-contract me-1"></i> Pricing Schedule
                </button>
                <button class="btn {{ in_array(9, $completedSteps) ? 'btn-success' : 'btn-light' }} step-btn"
                    data-step="5">
                    <i class="fa fa-file-contract me-1"></i> Setup Codes
                </button>
                <button class="btn {{ in_array(10, $completedSteps) ? 'btn-success' : 'btn-light' }} step-btn"
                    data-step="5">
                    <i class="fa fa-file-contract me-1"></i> Assign codes
                </button>
                <button class="btn {{ in_array(11, $completedSteps) ? 'btn-success' : 'btn-light' }} step-btn"
                    data-step="5">
                    <i class="fa fa-file-contract me-1"></i> Materials
                </button>
                <button class="btn {{ in_array(12, $completedSteps) ? 'btn-success' : 'btn-light' }} step-btn"
                    data-step="5">
                    <i class="fa fa-file-contract me-1"></i> Plant
                </button>
                <button class="btn {{ in_array(13, $completedSteps) ? 'btn-success' : 'btn-light' }} step-btn"
                    data-step="5">
                    <i class="fa fa-file-contract me-1"></i> Labour
                </button>
            </div>
            <div id="step1" class="step-panel">
                <h6 class="fw-semibold mb-3 text-muted border-bottom pb-2">
                    <i class="fa fa-folder me-1"></i> Project Details
                </h6>
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Project Code</label>
                        <input type="text" id="project_code_id" class="form-control bg-light"
                            value="{{ $generatedCode }}" readonly placeholder="Auto-generated">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Project Name <span class="text-danger">*</span></label>
                        <input type="text" id="project_name" class="form-control" placeholder="Enter project name">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Region <span class="text-danger">*</span></label>
                        <select id="project_region" class="form-select">
                            <option value="">Select Region</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="col-md-6 d-none" id="other_region_wrap">
                        <label class="form-label fw-semibold">Specify Region <span class="text-danger">*</span></label>
                        <input type="text" id="project_other_region" class="form-control"
                            placeholder="Enter region name">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Project Number <span class="text-danger">*</span></label>
                        <input type="text" id="project_number" class="form-control bg-light"
                            placeholder="Auto-filled on region select" readonly>
                        {{-- <small class="text-muted">Filled automatically when region is selected</small> --}}
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Project Description</label>
                        <textarea id="project_description" class="form-control" rows="3" placeholder="Describe the project..."></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Project Location / Address</label>
                        <textarea id="project_address" class="form-control" rows="3" placeholder="Project site address..."></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Project Notes</label>
                        <textarea id="project_notes" class="form-control" rows="3" placeholder="Additional notes..."></textarea>
                    </div>

                </div>
                <div class="mt-4">
                    <button type="button" class="btn btn-success" id="step1_next">
                        Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            {{-- ══════════════════════════════════
             STEP 2 — CLIENT DETAILS
        ══════════════════════════════════ --}}
            <div id="step2" class="step-panel d-none">
                <h6 class="fw-semibold mb-3 text-muted border-bottom pb-2">
                    <i class="fa fa-user-tie me-1"></i> Client Details
                </h6>
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Client</label>
                        <select id="client_id" class="form-select">
                            <option value="">Select Client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->client_name ?? $client->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Client Representative <span
                                class="text-danger">*</span></label>
                        <input type="text" id="client_representative" class="form-control" placeholder="Full name">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Client Rep Email <span class="text-danger">*</span></label>
                        <input type="email" id="client_rep_email" class="form-control"
                            placeholder="email@example.com">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Superintendent <span class="text-danger">*</span></label>
                        <input type="text" id="superintendent" class="form-control"
                            placeholder="Superintendent name">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Superintendent Rep</label>
                        <input type="text" id="superintendent_rep" class="form-control"
                            placeholder="Superintendent representative">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Superintendent Rep Email</label>
                        <input type="email" id="superintendent_rep_email" class="form-control"
                            placeholder="email@example.com">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Client Phone Number <span
                                class="text-danger">*</span></label>
                        <input type="tel" id="client_phone_number" class="form-control" placeholder="Phone number"
                            pattern="[0-9]{10}" maxlength="10">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Client Address <span class="text-danger">*</span></label>
                        <textarea type="text" id="client_address" class="form-control" placeholder="Address">
                        </textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Invoices Sent To <span class="text-danger">*</span></label>
                        <input type="email" id="invoices_sent_to" class="form-control"
                            placeholder="email@example.com">
                    </div>

                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="step2_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="step2_next">
                        Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            {{-- ══════════════════════════════════
             STEP 3 — TEAM & DATES
        ══════════════════════════════════ --}}
            <div id="step3" class="step-panel d-none">
                <h6 class="fw-semibold mb-3 text-muted border-bottom pb-2">
                    <i class="fa fa-users me-1"></i> Team &amp; Dates
                </h6>
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Construction Manager</label>
                        <select id="construction_manager" class="form-select">
                            <option value="">Select</option>
                            @foreach ($constructionManagers as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Project Manager</label>
                        <select id="project_manager" class="form-select">
                            <option value="">Select</option>
                            @foreach ($projectManagers as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Supervisor</label>
                        <select id="supervisor" class="form-select">
                            <option value="">Select</option>
                            @foreach ($supervisors as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Project Engineer</label>
                        <select id="project_engineer" class="form-select">
                            <option value="">Select</option>
                            @foreach ($projectEngineers as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contract Administrator</label>
                        <select id="contract_admin" class="form-select">
                            <option value="">Select</option>
                            @foreach ($contractAdmins as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="step3_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="step3_next">
                        Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            {{-- ══════════════════════════════════
             STEP 4 — CONTRACT
        ══════════════════════════════════ --}}
            <div id="step4" class="step-panel d-none">
                <h6 class="fw-semibold mb-3 text-muted border-bottom pb-2">
                    <i class="fa fa-file-contract me-1"></i> Contract Details
                </h6>
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contract Type</label>
                        <select id="contract_type" class="form-select">
                            <option value="">Select</option>
                            @foreach ($contractTypes as $contract)
                                <option value="{{ $contract->id }}">
                                    {{ $contract->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contract Number <span class="text-danger">*</span></label>
                        <input type="text" id="contract_number" class="form-control" placeholder="Contract Number">
                    </div>


                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Commencement Date <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="commencement_date" class="form-control datepicker"
                            placeholder="dd/mm/yyyy">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Completion Date <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="completion_date" class="form-control datepicker"
                            placeholder="dd/mm/yyyy">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Payment Terms</label>
                        <select id="payment_term" class="form-select">
                            <option value="">Select</option>
                            @foreach ($paymentTerms as $terms)
                                <option value="{{ $terms->id }}">
                                    {{ $terms->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Claims Certification Period</label>
                        <select id="claims_certification_period" class="form-select">
                            <option value="">Select</option>
                            @foreach ($paymentTerms as $terms)
                                <option value="{{ $terms->id }}">
                                    {{ $terms->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Contract Notes</label>
                        <textarea id="contract_notes" class="form-control" rows="3" placeholder="Additional contract notes..."></textarea>
                    </div>

                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="step4_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="step4_next">
                        Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            <div id="step5" class="step-panel d-none">
                <h6 class="fw-semibold mb-3 text-muted border-bottom pb-2">
                    <i class="fa fa-file-contract me-1"></i> Contract Value
                </h6>

                <div class="row g-3">

                    <!-- Project Type -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold d-block mb-3">Project Type</label>

                        <div class="d-flex gap-4">

                            <!-- Lump Sum -->
                            <div class="custom-radio-option">
                                <input type="radio" name="project_type" value="lump_sum" id="lump_sum" checked>
                                <label for="lump_sum" class="option-label text-success">
                                    <span class="check-circle">
                                        <i class="fa fa-check check-icon"></i>
                                    </span>
                                    <i class="fa fa-file-invoice me-2"></i>
                                    Lump Sum
                                </label>
                            </div>

                            <!-- Schedule Of Rates -->
                            <div class="custom-radio-option">
                                <input type="radio" name="project_type" value="schedule_of_rate"
                                    id="schedule_of_rate">
                                <label for="schedule_of_rate" class="option-label">
                                    <span class="check-circle">
                                        <i class="fa fa-check check-icon"></i>
                                    </span>
                                    <i class="fa fa-list me-2"></i>
                                    Schedule Of Rates
                                </label>
                            </div>

                        </div>
                    </div>
                    <div>
                    </div>

                    <!-- Contract Value -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Contract Value (Inc GST) <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="contract_value" class="form-control" placeholder="Enter value">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Contract Value (Ex GST) <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="contract_value_gst" class="form-control" readonly>
                    </div>

                    <!-- Provisional Sum -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Provisional Sum (Inc GST) <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="provisional_sum_total" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Provisional Sum (Ex GST) <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="provisional_sum_total_gst" class="form-control" readonly>
                    </div>

                    <!-- Profit Margin -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Profit Margin (%) <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="assign_profit_margin" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Budget Value (Ex GST) <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="assign_profit_margin_value" class="form-control" readonly>
                    </div>

                    <!-- Insurance -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Insurance (%) <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="insurance_percentage" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Insurance Value (Ex GST) <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="insurance_percentage_value" class="form-control" readonly>
                    </div>

                    <!-- Profit Value -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Profit Value <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="profit_value" class="form-control" readonly>
                    </div>

                </div>


                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="step5_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="step5_next">
                        Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            <div id="step6" class="step-panel d-none">
                <h6 class="fw-semibold mb-3 text-muted border-bottom pb-2">
                    <i class="fa fa-file-contract me-1"></i> Bank Guarantee
                </h6>

                <div class="row g-3">

                    <!-- YES / NO -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold d-block mb-2">
                            Bank Guarantee Required? <span class="text-danger">*</span>
                        </label>

                        <div class="d-flex gap-4">

                            <div class="custom-radio-option">
                                <input type="radio" name="bank_guarantee_required" value="1" id="bg_yes">
                                <label for="bg_yes" class="option-label text-success">
                                    <span class="check-circle"><i class="fa fa-check"></i></span>
                                    Yes
                                </label>
                            </div>

                            <div class="custom-radio-option">
                                <input type="radio" name="bank_guarantee_required" value="0" id="bg_no">
                                <label for="bg_no" class="option-label">
                                    <span class="check-circle"><i class="fa fa-check"></i></span>
                                    No
                                </label>
                            </div>

                        </div>
                    </div>

                    <!-- CONDITIONAL FIELDS -->
                    <div id="bank_fields" class="row g-3 d-none">

                        <!-- Practical -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Practical Completion</label>
                            <select id="practical_completion" class="form-select">
                                <option value="">Select</option>
                                <option value="0">0%</option>
                                <option value="2.5">2.5%</option>
                                <option value="5">5%</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>

                        <div class="col-md-4 d-none" id="custom_practical_wrap">
                            <input type="number" id="custom_practical_completion" class="form-control"
                                placeholder="Custom %">
                        </div>

                        <div class="col-md-4">
                            <input type="text" id="practical_completion_amount" class="form-control" readonly>
                        </div>

                        <!-- Final -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Final Completion</label>
                            <select id="final_completion" class="form-select">
                                <option value="">Select</option>
                                <option value="0">0%</option>
                                <option value="2.5">2.5%</option>
                                <option value="5">5%</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>

                        <div class="col-md-4 d-none" id="custom_final_wrap">
                            <input type="number" id="custom_final_completion" class="form-control"
                                placeholder="Custom %">
                        </div>

                        <div class="col-md-4">
                            <input type="text" id="final_completion_amount" class="form-control" readonly>
                        </div>

                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="step6_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="step6_next">
                        Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
            <div id="step7" class="step-panel d-none">
                <h6 class="fw-semibold mb-3 text-muted border-bottom pb-2">
                    <i class="fa fa-file-contract me-1"></i> Cash Retentions
                </h6>

                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="step7_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="step7_next">
                        Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
            <div id="step8" class="step-panel d-none">
                <h6 class="fw-semibold mb-3 text-muted border-bottom pb-2">
                    <i class="fa fa-file-contract me-1"></i> Pricing Schedule
                </h6>


                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="step8_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="step8_next">
                        Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
            <div id="step9" class="step-panel d-none">
                <h6 class="fw-semibold mb-3 text-muted border-bottom pb-2">
                    <i class="fa fa-file-contract me-1"></i> Setup Codes
                </h6>


                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="step9_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="step9_next">
                        Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
            <div id="step10" class="step-panel d-none">
                <h6 class="fw-semibold mb-3 text-muted border-bottom pb-2">
                    <i class="fa fa-file-contract me-1"></i> Assign codes
                </h6>


                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="step10_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="step10_next">
                        Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
            <div id="step11" class="step-panel d-none">
                <h6 class="fw-semibold mb-3 text-muted border-bottom pb-2">
                    <i class="fa fa-file-contract me-1"></i> Materials
                </h6>


                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="step11_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="step11_next">
                        Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
            <div id="step12" class="step-panel d-none">
                <h6 class="fw-semibold mb-3 text-muted border-bottom pb-2">
                    <i class="fa fa-file-contract me-1"></i> Plant
                </h6>


                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="step12_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="step12_next">
                        Next <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
            <div id="step13" class="step-panel d-none">
                <h6 class="fw-semibold mb-3 text-muted border-bottom pb-2">
                    <i class="fa fa-file-contract me-1"></i> Labour
                </h6>


                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary" id="step13_prev">
                        <i class="fa fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-success" id="step13_finish">
                        Save & Finish <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>{{-- /.card --}}
    </div>{{-- /.card-container --}}
@endsection

@push('scripts')
    <script>
        const existingProject = @json($existingProject ?? null);
        const completedStepsData = @json($completedSteps ?? []);
        const projectsDataUrl = "{{ route('projects.data') }}";
        const projectStepUrl = "{{ route('projects.step') }}";
        const projectShowUrl = "{{ url('projects') }}";
        const projectEditUrl = "{{ url('projects') }}";
        const projectDeleteUrl = "{{ url('projects') }}";
        const projectNumberUrl = "{{ route('project.generateNumber') }}";
        const getClientUrl = "{{ route('clients.getClient', ':id') }}";
    </script>
    <script src="{{ asset('js/modules/projects.js') }}"></script>
@endpush
