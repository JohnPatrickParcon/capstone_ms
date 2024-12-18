<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Capstone Management System | Adviser</title>
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
    </style>
</head>

<body>
    <div class="container-scroller">
        @include('pages.components.sidebar')
        <div class="container-fluid page-body-wrapper">
            @include('pages.components.navbar')
            <div class="main-panel" style="min-height: 100vh; max-height: 100vh; overflow-y: auto;">
                <div class="pb-0 content-wrapper">
                    <div class="flex-wrap page-header">
                        <h3 class="mb-0"> Hi, welcome to Capstone Management System!</h3>
                    </div>
                    <div class="p-3 card">
                        <h3 class="text-center">Advisories Table</h3>
                        <table id="advisoryTable" class="table table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr style="background: green; color: white;">
                                    <th>#</th>
                                    <th>Students</th>
                                    <th>Panels</th>
                                    <th>Defense Schedule</th>
                                    <th>Status</th>
                                    <th data-orderable="false">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <br>
                    <div class="p-3 card">
                        <h3 class="text-center">Panel Table</h3>
                        <table id="panelTable" class="table table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr style="background: green; color: white;">
                                    <th>#</th>
                                    <th>Students</th>
                                    <th>Adviser</th>
                                    <th>Defense Schedule</th>
                                    <th>Status</th>
                                    <th data-orderable="false">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-center text-muted d-block text-sm-left d-sm-inline-block"></span>
                        <span class="float-none mt-1 text-center float-sm-right d-block mt-sm-0"><b>Capstone Management System</b></span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- @include('pages.adviser.modals.score')
    @include('pages.adviser.modals.score2') -->


    @include('pages.includes.scripts')

    <script>
        $(() => {
            loadTableAdviser();
            loadTablePanel();
        })

        const loadTableAdviser = () => {
            let dataTable = $('#advisoryTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                pageLength: 20,
                lengthChange: false,
                ajax: {
                    url: "./adviser-dashboard/adviser/",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'stundens_name', name: 'stundens_name'},
                    {data: 'panels_name', name: 'panels_name'},
                    {data: 'schedule', name: 'schedule'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });
        }

        const loadTablePanel = () => {
            let dataTable = $('#panelTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                pageLength: 20,
                lengthChange: false,
                ajax: {
                    url: "./adviser-dashboard/panel/",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'stundens_name', name: 'stundens_name'},
                    {data: 'adviser_name', name: 'adviser_name'},
                    {data: 'schedule', name: 'schedule'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });
        }

        const closeModal = (modalName) => {
            $(`#${modalName}`).modal("hide");
        }

        const handleScoreCapstoneAdviser = (reference) => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './getStudentInfo/' + reference,
                type: 'GET',

                success: function(data) {
                    let id_list = "";
                    let html_tag = `<input id="group_reference" type="hidden" value="${reference}">`;
                    Object.keys(data).forEach(key => {
                        html_tag += `
                            <div>
                                <p class="customForm name">Name: <b>${data[key].name}</b></p>
                                <input id="score_${data[key].user_id}" type="number" class="form-control" placeholder="0">
                            </div>
                            <hr>
                        `;

                        if(id_list != ""){
                            id_list += "-" + data[key].user_id;
                        } else {
                            id_list += data[key].user_id;
                        }
                    });
                    html_tag += `<input id="id_list" type="hidden" value="${id_list}">`;

                    $("#main_section").empty().append(html_tag);
                    $("#scoreModalAdviser").modal("show");
                }
            });
        }

        const saveScoreAdviser = () => {
            let group_reference = $("#group_reference").val();
            let id_list = $("#id_list").val().split("-");
            let score_obj = {};

            for (let index = 0; index < id_list.length; index++) {
                let value = $(`#score_${id_list[index]}`).val();
                score_obj[Object.keys(score_obj).length] = {id: id_list[index], score: value};
                if(value == ""){
                    Swal.fire({
                        icon: "error",
                        title: "Invalid Input",
                    });
                    return null;
                }
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './saveScoreAdviser/' + group_reference,
                type: 'PUT',
                data: JSON.stringify(score_obj),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Score Submitted",
                        text: "Successfully scored the group.",
                    }).then(() => {
                        location.reload();
                    });
                }
            });

        }

        const handleScoreCapstonePanel = (reference) => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './getStudentInfo/' + reference,
                type: 'GET',

                success: function(data) {
                    let id_list = "";
                    let html_tag = `<input id="group_reference_panel" type="hidden" value="${reference}">`;
                    Object.keys(data).forEach(key => {
                        html_tag += `
                            <div>
                                <p class="customForm name">Name: <b>${data[key].name}</b></p>
                                <input id="scorel_${data[key].user_id}_panel" type="number" class="form-control" placeholder="0">
                            </div>
                            <hr>
                        `;

                        if(id_list != ""){
                            id_list += "-" + data[key].user_id;
                        } else {
                            id_list += data[key].user_id;
                        }
                    });
                    html_tag += `<input id="id_list_panel" type="hidden" value="${id_list}">`;

                    $("#main_section_panel").empty().append(html_tag);
                    $("#scoreModalPanel").modal("show");
                }
            });
        }

        const saveScorePanel = () => {
            let group_reference = $("#group_reference_panel").val();
            let id_list = $("#id_list_panel").val().split("-");
            let score_obj = {};

            for (let index = 0; index < id_list.length; index++) {
                let value = $(`#score_${id_list[index]}_panel`).val();
                score_obj[Object.keys(score_obj).length] = {id: id_list[index], score: value};
                if(value == ""){
                    Swal.fire({
                        icon: "error",
                        title: "Invalid Input",
                    });
                    return null;
                }
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './saveScorePanel/' + group_reference,
                type: 'PUT',
                data: JSON.stringify(score_obj),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Score Submitted",
                        text: "Successfully scored the group.",
                    }).then(() => {
                        location.reload();
                    });
                }
            });

        }

        const handleViewCapstone = (ref) => {
            window.location.assign("./viewCapstone/" + ref);
        }

        const scoreCapstone = (reference) => {
            // console.log(reference);
            window.location.assign("./view-grading-form/" + reference);
        }

    </script>
</body>

</html>
