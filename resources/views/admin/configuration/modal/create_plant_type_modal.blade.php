<div class="modal center-modal fade" id="plant-type-modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-seedling me-2"></i>Add Plant Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="plant-type-form"
                  method="post"
                  action="{{ route('admin.project.configuration.plant-types.store') }}"
                  autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="plt_name">Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               name="name"
                               id="plt_name"
                               placeholder="Plant Type Name"
                               maxlength="255" />
                    </div>
                    <div class="form-group mb-2">
                        <label for="plt_status">Status</label>
                        <select class="form-select" name="status" id="plt_status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
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
