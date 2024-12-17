<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Capstone Management System | Admin</title>
    @include('pages.includes.styles')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .page-item.active .page-link {
            background-color: rgb(0,128,0) !important;
            color:white !important;
            border: 1px solid rgb(0,128,0);
        }
        .page-link {
            color: rgb(0,128,0) !important;
        }
        #cb1{
            accent-color: #008000;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        @include('pages.components.sidebar')
        <div class="container-fluid page-body-wrapper">
            @include('pages.components.navbar')
            <div class="main-panel" style="min-height: 100vh; max-height: 100vh; overflow-y: auto;">
                <div class="content-wrapper pb-0">
                    <div class="page-header flex-wrap">
                        <h3 class="mb-0"> Hi, welcome to Capstone Management System!</h3>
                    </div>

                    <div class="card p-5" id="addSection_main" style="display: none;">
                        <label for="student">Students</label>
                        <select name="students[]" multiple="multiple" id="student" class="js-example-basic-multiple form-control" style="width: 100%">
                            @foreach ($student_list as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>

                        <br>

                        <label for="adviser">Advisers</label>
                        <select name="adviser" id="adviser" class="js-example-basic-single form-control" style="width: 100%">
                            @foreach ($panels_and_adviser_list as $adviser)
                                <option value="{{ $adviser->id }}">{{ $adviser->name }}</option>
                            @endforeach
                        </select>

                        <br>

                        <label for="panel">Panels</label>
                        <select name="panels[]" multiple="multiple" id="panel" class="js-example-basic-multiple form-control" style="width: 100%">
                            @foreach ($panels_and_adviser_list as $panel)
                                <option value="{{ $panel->id }}">{{ $panel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>

                    <div class="text-end" id="addSection_BTN">
                        <button class="btn btn-primary" onclick="handleAddGrounpings()">Add Groupings</button>
                    </div>
                    <div class="text-end" id="saveSection_BTN" style="display: none;">
                        <button class="btn btn-primary" onclick="handlCancelGrounpings()">Cancel</button>
                        <button class="btn btn-primary" onclick="handleSaveGrounpings()">Save</button>
                    </div>
                    <br>


                    <div class="card p-5">
                        <div class="text-center">
                            <h3>Groupings</h3>
                        </div>
                        <table id="groupingsTable" class="table table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr style="background: green; color: white;">
                                    <th>#</th>
                                    <th>Students Name</th>
                                    <th>Adviser</th>
                                    <th>Panels</th>
                                    <th data-orderable="false">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block"></span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"><b>Capstone Management System</b></span>
                    </div>
                </footer>
            </div>
        </div>
    </div>


    @include('pages.includes.scripts')

    <script>
        $(() => {
            loadTable();
            $('.js-example-basic-multiple').select2({
                width: 'resolve',
                theme: "classic"
            });
            $('.js-example-basic-single').select2({
                width: 'resolve',
                theme: "classic"
            });

            changeOptions($("#adviser").val());
        })

        const loadingFunc = () => {
            Swal.fire({
                title: "Please wait...",
                didOpen: () => {
                    Swal.showLoading();
                },
                allowOutsideClick: false,
            });
        }

        const loadTable = () => {
            let dataTable = $('#groupingsTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                pageLength: 20,
                lengthChange: false,
                ajax: {
                    url: "./groupings/",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'names', name: 'names'},
                    {data: 'adviser', name: 'adviser'},
                    {data: 'panels', name: 'panels'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });
        }

        const handleAddGrounpings = () => {
            $("#addSection_BTN").hide();
            $("#saveSection_BTN").show();
            $("#addSection_main").show();
        }

        const handlCancelGrounpings = () => {
            $("#addSection_BTN").show();
            $("#saveSection_BTN").hide();
            $("#addSection_main").hide();
        }

        const handleSaveGrounpings = () => {
            loadingFunc();
            let studentData_tmp = $('#student').select2('data');
            let final_student_list = [];
            Object.keys(studentData_tmp).forEach(key => {
                final_student_list[final_student_list.length] = studentData_tmp[key].id;
            });

            let panelData_tmp = $('#panel').select2('data');
            let final_panel_list = [];
            Object.keys(panelData_tmp).forEach(key => {
                final_panel_list[final_panel_list.length] = panelData_tmp[key].id;
            });

            let adviserData_tmp = $('#adviser').select2('data');
            let final_adviser_list = [];
            Object.keys(adviserData_tmp).forEach(key => {
                final_adviser_list[final_adviser_list.length] = adviserData_tmp[key].id;
            });

            if(final_student_list.length == 0){
                Swal.fire({
                    icon: "error",
                    title: "Invalid Input",
                    text: "No Student/s Selected",
                });
                return null;
            }

            if(final_adviser_list.length == 0){
                Swal.fire({
                    icon: "error",
                    title: "Invalid Input",
                    text: "No Adviser Selected",
                });
                return null;
            }

            if(final_panel_list.length == 0){
                Swal.fire({
                    icon: "error",
                    title: "Invalid Input",
                    text: "No Panel/s Selected",
                });
                return null;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './saveGroupings',
                type: 'POST',
                data: JSON.stringify({
                    final_student_list,
                    final_panel_list,
                    final_adviser_list
                }),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Group Created",
                        text: "Successfully created the group.",
                    }).then(() => {
                        location.reload();
                    });
                }
            });

        }

        const handleDeleteGroup = (reference) => {
            loadingFunc();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './deleteGroupings/' + reference,
                type: 'DELETE',

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Group Removed",
                        text: "Successfully removed the group.",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        const handleEditGroup = (reference) => {
            console.log(reference);
        }

        const handleGradeGroup = (reference) => {
            window.location.assign("./grading_form/" + reference);
        }

        $("#adviser").change(() => {
            changeOptions($("#adviser").val());
        })

        const changeOptions = (exception) => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './getAdvisersAndPanel/' + exception,
                type: 'GET',
                success: function(data) {
                    let html = "";
                    Object.keys(data).forEach(key => {
                        html += `<option value="${data[key].id}">${data[key].name}</option>`;
                    });
                    $("#panel").empty().append(html);
                }
            });
        }

    </script>
</body>

</html>
