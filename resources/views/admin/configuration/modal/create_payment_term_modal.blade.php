<div class="modal center-modal fade" id="add-payment-term-modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-dollar-sign me-2"></i>Add Payment Term</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="payment-term-form"
                  method="post"
                  action="{{ route('admin.project.configuration.payment-terms.store') }}"
                  autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="pt_name">Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               name="name"
                               id="pt_name"
                               placeholder="e.g. Net 30"
                               maxlength="255" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="pt_days">Days <span class="text-danger">*</span></label>
                        <input type="number"
                               class="form-control"
                               name="days"
                               id="pt_days"
                               placeholder="Number of days"
                               min="0"
                               max="999" />
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input"
                               type="checkbox"
                               name="is_active"
                               id="pt_is_active"
                               value="1"
                               checked />
                        <label class="form-check-label" for="pt_is_active">Active</label>
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
