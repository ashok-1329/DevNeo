@extends('layouts.admin')

@section('content')
    <div class="card-container">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between mb-4 bg-secondary px-3 py-2 rounded">
            <h4 class="text-light mb-0 text-uppercase">Project Details</h4>

            <div>
                <a href="{{ route('projects.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        {{-- BASIC INFO --}}
        <div class="row g-3 mb-3">

            <div class="col-md-6">
                <label class="text-muted">Project Name</label>
                <div class="form-control bg-light">{{ $project->project_name }}</div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Project Number</label>
                <div class="form-control bg-light">{{ $project->project_number }}</div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Project Code</label>
                <div class="form-control bg-light">{{ $project->project_code_id }}</div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Region</label>
                <div class="form-control bg-light">
                    {{ $project->project_region === 'other' ? $project->project_other_region : $project->project_region }}
                </div>
            </div>

        </div>

        {{-- CLIENT INFO --}}
        <div class="row g-3 mb-3">

            <div class="col-md-6">
                <label class="text-muted">Client</label>
                <div class="form-control bg-light">
                    {{ $project->client_representative ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Client Email</label>
                <div class="form-control bg-light">
                    {{ $project->client_rep_email ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Client Phone</label>
                <div class="form-control bg-light">
                    {{ $project->client_phone_number ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Client Address</label>
                <div class="form-control bg-light">
                    {{ $project->client_address ?? '-' }}
                </div>
            </div>

        </div>

        {{-- TEAM INFO --}}
        <div class="row g-3 mb-3">

            <div class="col-md-6">
                <label class="text-muted">Construction Manager</label>
                <div class="form-control bg-light">
                    {{ $project->construction_manager ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Project Manager</label>
                <div class="form-control bg-light">
                    {{ $project->project_manager ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Supervisor</label>
                <div class="form-control bg-light">
                    {{ $project->supervisor ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Project Engineer</label>
                <div class="form-control bg-light">
                    {{ $project->project_engineer ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Contract Admin</label>
                <div class="form-control bg-light">
                    {{ $project->contract_admin ?? '-' }}
                </div>
            </div>

        </div>

        {{-- CONTRACT INFO --}}
        <div class="row g-3 mb-3">

            <div class="col-md-6">
                <label class="text-muted">Contract Type</label>
                <div class="form-control bg-light">
                    {{ $project->contract_type === 'other' ? $project->contract_type_other : $project->contract_type }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Contract Number</label>
                <div class="form-control bg-light">
                    {{ $project->contract_number ?? '-' }}
                </div>
            </div>

        </div>

        {{-- DATES --}}
        <div class="row g-3 mb-3">

            <div class="col-md-6">
                <label class="text-muted">Commencement Date</label>
                <div class="form-control bg-light">
                    {{ $project->commencement_date?->format('d M Y') ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Completion Date</label>
                <div class="form-control bg-light">
                    {{ $project->completion_date?->format('d M Y') ?? '-' }}
                </div>
            </div>

        </div>

        {{-- FINANCIAL --}}
        <div class="row g-3 mb-3">

            <div class="col-md-6">
                <label class="text-muted">Contract Value</label>
                <div class="form-control bg-light">
                    {{ $project->contract_value ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Contract Value (GST)</label>
                <div class="form-control bg-light">
                    {{ $project->contract_value_gst ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Profit Value</label>
                <div class="form-control bg-light">
                    {{ $project->profit_value ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted">Status</label>
                <div class="form-control bg-light">
                    @if ($project->status == 1)
                        Active
                    @elseif ($project->status == 2)
                        Deactive
                    @elseif ($project->status == 3)
                        Archive
                    @elseif ($project->status == 4)
                        Defects Period
                    @elseif ($project->status == 5)
                        Complete
                    @else
                        Unknown Status
                    @endif
                </div>
            </div>

        </div>

    </div>
@endsection
