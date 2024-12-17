<!-- Modal -->
<div class="modal fade" id="publishModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" >
    <div class="modal-dialog modal-l ">
        <div class="modal-content rounded-1">
            <div class="modal-header alert alert-secondary rounded-0">
                <h2 class="modal-title fw-bold" id="exampleModalLabel">Publish Capstone</h2>
            </div>
            <div class="modal-body">
                <label for="">Abstract</label>
                <textarea name="abstract" id="abstract" class="form-control" rows="20"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal('publishModal')">Close</button>
                <button type="button" class="btn btn-primary" onclick="publishAbstract()">Published</button>
            </div>
        </div>
    </div>
</div>