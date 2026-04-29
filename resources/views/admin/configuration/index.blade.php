@extends('layouts.admin')

@section('content')
    <section class="content project">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h4 class="card-title mb-0 fw-semibold">Manage Project Configuration</h4>
                    </div>

                    <div class="card-body p-0">
                        <div class="row g-0">

                            <div class="col-lg-3 col-md-4 border-end bg-light" style="min-height: 650px;">
                                <div class="nav flex-column nav-pills p-3" role="tablist">
                                    <a class="nav-link active d-flex align-items-center gap-3 py-3 px-4 mb-2"
                                        data-bs-toggle="pill" href="#contract-type" role="tab">
                                        <i class="fa-solid fa-file-contract fa-lg"></i>
                                        <span class="fw-medium">Manage Project Contract Type</span>
                                    </a>

                                    <a class="nav-link d-flex align-items-center gap-3 py-3 px-4 mb-2" data-bs-toggle="pill"
                                        href="#payment-term" role="tab">
                                        <i class="fa-solid fa-dollar-sign fa-lg"></i>
                                        <span class="fw-medium">Manage Project Payment Terms</span>
                                    </a>

                                    <a class="nav-link d-flex align-items-center gap-3 py-3 px-4 mb-2" data-bs-toggle="pill"
                                        href="#code-category" role="tab">
                                        <i class="fa-solid fa-code fa-lg"></i>
                                        <span class="fw-medium">Manage Project Code Category</span>
                                    </a>

                                    <a class="nav-link d-flex align-items-center gap-3 py-3 px-4 mb-2" data-bs-toggle="pill"
                                        href="#plant-type" role="tab">
                                        <i class="fa-solid fa-seedling fa-lg"></i>
                                        <span class="fw-medium">Manage Plant Type</span>
                                    </a>

                                    <a class="nav-link d-flex align-items-center gap-3 py-3 px-4 mb-2" data-bs-toggle="pill"
                                        href="#plant-capacity" role="tab">
                                        <i class="fa-solid fa-gauge-high fa-lg"></i>
                                        <span class="fw-medium">Manage Plant Capacities</span>
                                    </a>

                                    <a class="nav-link d-flex align-items-center gap-3 py-3 px-4" data-bs-toggle="pill"
                                        href="#project-region" role="tab">
                                        <i class="fa-solid fa-map-location-dot fa-lg"></i>
                                        <span class="fw-medium">Manage Project Region</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Main Content Area -->
                            <div class="col-lg-9 col-md-8 p-4">

                                <div class="tab-content">

                                    <!-- ==================== CONTRACT TYPE TAB ==================== -->
                                    <div class="tab-pane fade show active" id="contract-type" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h3 class="mb-0 fw-semibold text-dark">Manage Contract Type</h3>
                                            <button class="btn btn-success px-4 py-2 add_new_contract_type">
                                                <i class="fa fa-plus me-2"></i> Add New
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered align-middle"
                                                id="contract_type_list"
                                                data-url="{{ route('admin.project.configuration.contract-types.index') }}">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="8%">#</th>
                                                        <th>Contract Type</th>
                                                        <th width="15%" class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- ==================== PAYMENT TERMS TAB ==================== -->
                                    <div class="tab-pane fade" id="payment-term" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h3 class="mb-0 fw-semibold text-dark">Manage Payment Terms</h3>
                                            <button class="btn btn-success px-4 py-2 add_new_payment_term">
                                                <i class="fa fa-plus me-2"></i> Add New
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered align-middle"
                                                id="payment_term_list"
                                                data-url="{{ route('admin.project.configuration.payment-terms.index') }}" style="width: 100%;">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Days</th>
                                                        <th>Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- ==================== CODE CATEGORY TAB ==================== -->
                                    <div class="tab-pane fade" id="code-category" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h3 class="mb-0 fw-semibold text-dark">Manage Code Category</h3>
                                            <button class="btn btn-success px-4 py-2 add_code_category">
                                                <i class="fa fa-plus me-2"></i> Add New
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered align-middle"
                                                id="code_category_list"
                                                data-url="{{ route('admin.project.configuration.code-categories.index') }}" style="width: 100%;">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th width="25%">Category Name</th>
                                                        <th width="28%">Code Name</th>
                                                        <th width="15%">Assign Margin</th>
                                                        <th width="12%">Status</th>
                                                        <th width="15%" class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- ==================== PLANT TYPE TAB ==================== -->
                                    <div class="tab-pane fade" id="plant-type" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h3 class="mb-0 fw-semibold text-dark">Manage Plant Type</h3>
                                            <button class="btn btn-success px-4 py-2 add_plant_type">
                                                <i class="fa fa-plus me-2"></i> Add New
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered align-middle"
                                                id="plant_type_list"
                                                data-url="{{ route('admin.project.configuration.plant-types.index') }}" style="width: 100%;">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="8%">#</th>
                                                        <th width="50%">Name</th>
                                                        <th width="17%">Status</th>
                                                        <th width="25%" class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- ==================== PLANT CAPACITIES TAB ==================== -->
                                    <div class="tab-pane fade" id="plant-capacity" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h3 class="mb-0 fw-semibold text-dark">Manage Plant Capacities</h3>
                                            <button class="btn btn-success px-4 py-2 add_plant_capacity">
                                                <i class="fa fa-plus me-2"></i> Add New
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered align-middle"
                                                id="plant_capacity_list"
                                                data-url="{{ route('admin.project.configuration.plant-capacities.index') }}" style="width: 100%;">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="8%">#</th>
                                                        <th width="28%">Capacity</th>
                                                        <th width="27%">Plant Type</th>
                                                        <th width="12%">Status</th>
                                                        <th width="25%" class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- ==================== PROJECT REGION TAB ==================== -->
                                    <div class="tab-pane fade" id="project-region" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h3 class="mb-0 fw-semibold text-dark">Manage Project Region</h3>
                                            <button class="btn btn-success px-4 py-2 add_project_region">
                                                <i class="fa fa-plus me-2"></i> Add New
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered align-middle"
                                                id="project_region_list"
                                                data-url="{{ route('admin.project.configuration.project-regions.index') }}" style="width: 100%;">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="8%">#</th>
                                                        <th width="50%">Name</th>
                                                        <th width="17%">Status</th>
                                                        <th width="25%" class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div><!-- /.tab-content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include All Modals -->
        @include('admin.configuration.modal.create_contract_type_modal')
        @include('admin.configuration.modal.edit_contract_type_modal')
        @include('admin.configuration.modal.create_payment_term_modal')
        @include('admin.configuration.modal.edit_payment_term_modal')
        @include('admin.configuration.modal.create_code_category_modal')
        @include('admin.configuration.modal.edit_code_category_modal')
        @include('admin.configuration.modal.create_plant_type_modal')
        @include('admin.configuration.modal.edit_plant_type_modal')
        @include('admin.configuration.modal.create_plant_capacity_modal')
        @include('admin.configuration.modal.edit_plant_capacity_modal')
        @include('admin.configuration.modal.create_project_region_modal')
        @include('admin.configuration.modal.edit_project_region_modal')

    </section>
@endsection


@push('scripts')
    <script src="{{ asset('js/modules/configuration.js') }}"></script>
@endpush
