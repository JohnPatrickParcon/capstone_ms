<!-- Modal -->
<div class="modal fade" id="update_groupings_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Update Group</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="handleCloseModal('update_groupings_modal')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <select name="update_option" id="update_option" class="js-example-basic-single form-control" style="width: 100%">
                    <option value="1">Add Member</option>
                    <option value="2">Kick Member</option>
                    <option value="3">Disband Group</option>
                </select>
            </div>
            <div class="modal-footer">
               <button class="btn btn-primary" onclick="updateGroupButton()">Proceed</button>
            </div>
        </div>
    </div>
</div>
