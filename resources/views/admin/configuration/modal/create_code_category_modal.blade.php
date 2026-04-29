<div class="modal center-modal fade" id="add-code-category-modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-code me-2"></i>Add Code Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="add-code-category-form"
                  method="post"
                  action="{{ route('admin.project.configuration.code-categories.store') }}"
                  autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="cc_name">Category Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               name="name"
                               id="cc_name"
                               placeholder="Category Name"
                               maxlength="255" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="cc_code_name">Code Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               name="code_name"
                               id="cc_code_name"
                               placeholder="Code Name"
                               maxlength="255" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="cc_assign_margin">Assign Margin (%)</label>
                        <input type="number"
                               class="form-control"
                               name="assign_margin"
                               id="cc_assign_margin"
                               placeholder="e.g. 15"
                               min="0"
                               max="100"
                               step="0.01" />
                    </div>
                    <div class="form-group mb-2">
                        <label for="cc_status">Status</label>
                        <select class="form-select" name="status" id="cc_status">
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
