
<form method="POST" action="./updateCapstoneDesc">
    @csrf
    <input type="hidden" id="reference" name="reference" value="{{ $capstone_info[0]->group_reference }}">
    <div class="row">
        <div class="col-12 mt-2">
            <label for="">Capstone Title <span class="required_tag">*</span></label>
            <input name="title" id="title" type="text" class="form-control" required value="{{ $capstone_info[0]->title }}">
        </div>
        <div class="col-12 mt-2">
            <label for="">Capstone Abstract</label>
            <textarea name="abstract" id="abstract" cols="30" rows="10" class="form-control" value="{{ $capstone_info[0]->abstract }}"></textarea>
        </div>
    </div>
    <div class="text-end mt-3">
        <button class="btn btn-danger" id="cancelEditDesc">Cancel</button>
        <button class="btn btn-primary">Update</button>
    </div>
</form>