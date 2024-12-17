<!-- Modal -->
<div class="modal fade" id="AddModalGradingForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" >
    <div class="modal-dialog">
        <div class="modal-content rounded-1">
            <div class="modal-header alert alert-secondary rounded-0">
                <h2 class="modal-title fw-bold" id="exampleModalLabel">Panel and Coordinator</h2>
            </div>
            <div class="modal-body" id="main_section">
                <label for="">Chair Panel</label>
                <input id="chair_panel" type="text" class="form-control">
                <br>
                <label for="">R & E Coordinator</label>
                <input id="rne_coordinator" type="text" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal('AddModalGradingForm')">Close</button>
                <button type="button" class="btn btn-primary" onclick="save()">Submit</button>
            </div>
        </div>
    </div>
</div>