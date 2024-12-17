<!-- Modal -->
<div class="modal fade" id="setGrade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" >
    <div class="modal-dialog">
        <div class="modal-content rounded-1">
            <div class="modal-header alert alert-secondary rounded-0">
                <h2 class="modal-title fw-bold" id="exampleModalLabel">Student Grade</h2>
            </div>
            <div class="modal-body" id="main_section">
                {{-- <label for="">Grade</label> --}}
                <input id="score" type="number" class="form-control">
                <input id="member_key" type="hidden" class="form-control">
                <input id="criteria_key" type="hidden" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal('setGrade')">Close</button>
                <button type="button" class="btn btn-primary" onclick="save()">Submit</button>
            </div>
        </div>
    </div>
</div>