<div class="modal center-modal fade" id="edit-contract-type-modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-file-contract me-2"></i>Edit Contract Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-contract-type-form"
                  method="post"
                  action=""
                  autocomplete="off">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit_contract_type_id" name="id" value="">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="edit_contract_type_value">Contract Type Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               name="value"
                               id="edit_contract_type_value"
                               placeholder="Enter Contract Type Name"
                               maxlength="255" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
