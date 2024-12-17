
<div class="p-3 card">
    <form method="POST" action="./uploadCapstone" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="mt-2 col-12">
                <label for="">Capstone Title <span class="required_tag">*</span></label>
                <input name="title" id="title" type="text" class="form-control" required>
            </div>
            <div class="mt-2 col-12">
                <label for="">Capstone Abstract</label>
                <textarea name="abstract" id="abstract" cols="30" rows="10" class="form-control"></textarea>
            </div>
            <div class="mt-2 col-12">
                <label for="">Capstone File <span class="required_tag">*</span></label>
                <input name="file" id="file" type="file" accept="application/pdf" class="form-control" required>
            </div>
        </div>
        <div class="mt-3 text-end">
            <button class="btn btn-primary">Upload</button>
        </div>
    </form>
</div>