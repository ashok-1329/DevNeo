<div class="modal center-modal fade" id="edit-plant-capacity-modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-gauge-high me-2"></i>Edit Plant Capacity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-plant-capacity-form"
                  method="post"
                  action=""
                  autocomplete="off">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="edit_pc_name">Capacity Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control"
                                       name="name"
                                       id="edit_pc_name"
                                       placeholder="e.g. 5 kW"
                                       maxlength="255" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="edit_pc_plant_type_id">Plant Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="plant_type_id" id="edit_pc_plant_type_id">
                                    <option value="">-- Select Plant Type --</option>
                                    @foreach ($plantTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="edit_pc_status">Status</label>
                                <select class="form-select" name="status" id="edit_pc_status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
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
