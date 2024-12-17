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

                    <div id="addSchedule_section" class="card p-5" style="display: none;">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="day">Day</label>
                                <select name="day" id="day" class="form-control" style="height: 38px;">
                                    <option value="1">Monday</option>
                                    <option value="2">Tuesday</option>
                                    <option value="3">Wednesday</option>
                                    <option value="4">Thursday</option>
                                    <option value="5">Friday</option>
                                    <option value="6">Saturday</option>
                                    <option value="7">Sunday</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="time_start">Start Time</label>
                                <input type="time" name="time_start" id="time_start" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <label for="time_end">End Time</label>
                                <input type="time" name="time_end" id="time_end" class="form-control">
                            </div>
                        </div>
                        <br>
                        <div class="text-end">
                            <button class="btn btn-primary" onclick="cancelAddSchedule()">Cancel</button>
                            <button class="btn btn-primary" onclick="saveSchedule()">Save</button>
                        </div>
                    </div>

                    <div id="editSchedule_section" class="card p-5" style="display: none;">
                        <input type="hidden" id="schedule_id">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="edit_day">Day</label>
                                <select name="edit_day" id="edit_day" class="form-control" style="height: 38px;">
                                    <option value="1">Monday</option>
                                    <option value="2">Tuesday</option>
                                    <option value="3">Wednesday</option>
                                    <option value="4">Thursday</option>
                                    <option value="5">Friday</option>
                                    <option value="6">Saturday</option>
                                    <option value="7">Sunday</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="edit_time_start">Start Time</label>
                                <input type="time" name="edit_time_start" id="edit_time_start" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <label for="edit_time_end">End Time</label>
                                <input type="time" name="edit_time_end" id="edit_time_end" class="form-control">
                            </div>
                        </div>
                        <br>
                        <div class="text-end">
                            <button class="btn btn-primary" onclick="cancelEditSchedule()">Cancel</button>
                            <button class="btn btn-primary" onclick="editSchedule()">Update</button>
                        </div>
                    </div>

                    <br>
                    <div class="text-end" id="addSchedule_button">
                        <button class="btn btn-primary" onclick="AddSchedule()">Add Schedule</button>
                    </div>

                    <br>
                    <div class="card p-3">
                        <h3>Consultation Schedule</h3>
                        <table id="schedTable" class="table table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr style="background: green; color: white;">
                                    <th>Day</th>
                                    <th>Time</th>
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
        })

        const loadTable = () => {
            let dataTable = $('#schedTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                pageLength: 20,
                lengthChange: false,
                ajax: {
                    url: "./consultation-hours/",
                },
                columns: [
                    {data: 'day', name: 'day'},
                    {data: 'time', name: 'time'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });
        }

        const AddSchedule = () => {
            $("#addSchedule_section").show();
            $("#addSchedule_button").hide();
        }

        const cancelAddSchedule = () => {
            $("#editSchedule_section").hide();
            $("#addSchedule_section").hide();
            $("#addSchedule_button").show();
        }

        const cancelEditSchedule = () => {
            $("#editSchedule_section").hide();
            $("#addSchedule_section").hide();
            $("#addSchedule_button").show();
        }

        const saveSchedule = () => {
            let day = $("#day").val();
            let start_time = $("#time_start").val();
            let end_time = $("#time_end").val();

            if(start_time == ""){
                Swal.fire({
                    icon: "error",
                    title: "Invalid Input",
                    text: "No Start Time",
                });
                return null;
            }

            if(end_time == ""){
                Swal.fire({
                    icon: "error",
                    title: "Invalid Input",
                    text: "No End Time",
                });
                return null;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './saveConsultationSchedule',
                type: 'POST',
                data: JSON.stringify({
                    day, start_time, end_time
                }),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Schedule Added",
                        text: "Successfully added the schedule.",
                    }).then(() => {
                        location.reload();
                    });
                }
            });

        }

        const handleEditSchedule = (id) => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './getMySchedule/' + id,
                type: 'GET',

                success: function(data) {
                    $("#schedule_id").val(data.id);
                    $("#edit_day").val(data.day);
                    $("#edit_time_start").val(data.start_time);
                    $("#edit_time_end").val(data.end_time);

                    $("#editSchedule_section").show();
                    $("#addSchedule_section").hide();
                    $("#addSchedule_button").hide();

                }
            });
        }

        const handleDeleteSchedule = (id) => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './deleteMySchedule/' + id,
                type: 'DELETE',

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Schedule Removed",
                        text: "Successfully removed the schedule.",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        const editSchedule = () => {
            let day = $("#edit_day").val();
            let start_time = $("#edit_time_start").val();
            let end_time = $("#edit_time_end").val();
            let id = $("#schedule_id").val();

            if(start_time == ""){
                Swal.fire({
                    icon: "error",
                    title: "Invalid Input",
                    text: "No Start Time",
                });
                return null;
            }

            if(end_time == ""){
                Swal.fire({
                    icon: "error",
                    title: "Invalid Input",
                    text: "No End Time",
                });
                return null;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './updateMySchedule/' + id,
                type: 'PUT',
                data: JSON.stringify({
                    day, start_time, end_time
                }),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Schedule Updated",
                        text: "Successfully updated the schedule.",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

    </script>
</body>

</html>
