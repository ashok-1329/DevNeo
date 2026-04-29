<div class="modal center-modal fade" id="edit-plant-type-modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-seedling me-2"></i>Edit Plant Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-plant-type-form"
                  method="post"
                  action=""
                  autocomplete="off">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="edit_plt_name">Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               name="name"
                               id="edit_plt_name"
                               placeholder="Plant Type Name"
                               maxlength="255" />
                    </div>
                    <div class="form-group mb-2">
                        <label for="edit_plt_status">Status</label>
                        <select class="form-select" name="status" id="edit_plt_status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
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
