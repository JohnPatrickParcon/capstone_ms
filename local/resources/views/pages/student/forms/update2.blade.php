
<form method="POST" action="./updateCapstoneFile" enctype="multipart/form-data">
    @csrf

    <input type="hidden" id="reference" name="reference" value="{{ $capstone_info[0]->group_reference }}">
    <div class="row">
        <div class="mt-2 col-12">
            <label for="">Capstone File <span class="required_tag">*</span></label>
            <input name="file" id="file" type="file" accept="application/pdf" class="form-control" required>
        </div>
    </div>
    <div class="mt-3 text-end">
        <button class="btn btn-danger" id="cancelEditFile">Cancel</button>
        <button class="btn btn-primary">Update</button>
    </div>
</form>