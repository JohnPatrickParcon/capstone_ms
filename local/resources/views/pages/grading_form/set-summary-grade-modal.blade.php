<!-- Modal -->
<div class="modal fade" id="setGradeSummary" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" >
    <div class="modal-dialog">
        <div class="modal-content rounded-1">
            <div class="modal-header alert alert-secondary rounded-0">
                <h2 class="modal-title fw-bold" id="exampleModalLabel">Summary Rating</h2>
            </div>
            <div class="modal-body" id="main_section">
                {{-- <label for="">Grade</label> --}}
                <input id="summary_score" type="number" class="form-control">
                <input id="member_key" type="hidden" class="form-control">
                <input id="panel_id" type="hidden" class="form-control">
                <input id="type" type="hidden" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal('setGradeSummary')">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveSummary()">Submit</button>
            </div>
        </div>
    </div>
</div>