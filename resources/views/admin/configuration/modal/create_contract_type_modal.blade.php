<div class="modal center-modal fade" id="add-contract-type-modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-file-contract me-2"></i>Add Contract Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="contract-type-form"
                  method="post"
                  action="{{ route('admin.project.configuration.contract-types.store') }}"
                  autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="contract_type_value">Contract Type Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               name="value"
                               id="contract_type_value"
                               placeholder="Enter Contract Type Name"
                               maxlength="255" />
                        {{-- Inline error injected by JS --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
