<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Capstone Management System | Coordinator</title>
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
        .fc .fc-button-primary:disabled{
            background-color: green !important;
            border-color: green !important;
            color: white !important;
        }
        .fc .fc-button-primary {
            background-color: green !important;
            border-color: green !important;
            color: white !important;
        }
        .fc-col-header-cell-cushion {
            color: green !important;
        }
        .fc-daygrid-day-number {
            color: green !important;
        }
        .fc-h-event {
            background-color: green !important;
            border: 1px solid green !important;
            color: white;
            display: block;
        }
        .fc-daygrid-day-frame .fc-scrollgrid-sync-inner {
            background-color: green !important;
        }
        .fc-day .fc-day-today .fc-daygrid-day {
            background-color: green !important;
        }
        .fc .fc-event {
            border: 0;
            color: white !important;
            padding: 0.5rem;
            background-color:  green !important;
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

                    <div class="p-5 card">
                        <div class="text-center">
                            <h3>Groupings Request</h3>
                        </div>
                        <table id="groupingsTable" class="table table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr style="background: green; color: white;">
                                    <th>#</th>
                                    <th>Students Name</th>
                                    <th data-orderable="false">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="p-5 card">
                        <div class="text-center">
                            <h3>Groupings Edit Request</h3>
                        </div>
                        <table id="groupingsEditTable" class="table table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr style="background: green; color: white;">
                                    <th>#</th>
                                    <th>Students Name</th>
                                    <th>Purpose</th>
                                    <th data-orderable="false">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div id="main_section" class="px-5 card">
                        <div id='calendar'></div>
                        <br><br><br>
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

    @include('pages.coordinator.modals.viewSchedule')
    @include('pages.coordinator.modals.viewRequest')
    @include('pages.coordinator.modals.viewEditRequest')
    
    @include('pages.includes.scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './getAllDateCoordinator',
                type: 'GET',

                success: function(data) {
                    loadCalendar(data);
                }
            });

            loadAllRequests();
        });

        const loadingFunc = () => {
            Swal.fire({
                title: "Please wait...",
                didOpen: () => {
                    Swal.showLoading();
                },
                allowOutsideClick: false,
            });
        }
        
        const loadAllRequests = () => {
            let dataTable = $('#groupingsTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                pageLength: 5,
                lengthChange: false,
                ajax: {
                    url: "./request_list/",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'requested_by', name: 'requested_by'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });

            let dataTable2 = $('#groupingsEditTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                pageLength: 5,
                lengthChange: false,
                ajax: {
                    url: "./request_edit_list/",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'requested_by', name: 'requested_by'},
                    {data: 'purpose', name: 'purpose'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });
        }

        const loadCalendar = (data) => {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                // initialView: 'dayGridMonth', 
                events: data,
                initialView: 'dayGridMonth', // Use 'multiMonthYear' or 'multiMonth' view
                views: {
                    month: {
                        type: 'dayGridMonth',
                        duration: { months: 1 }, // Number of months to display
                        buttonText: 'dayGridMonth'
                    }
                },
                dateClick: function(info) { 
                    // console.log(info);
                    // alert('Date: ' + info.dateStr); 
                    handleClickDate(info.dateStr);
                },
            });
            calendar.render();
        }

        const handleClickDate = (date) => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './checkDateActions/' + date,
                type: 'GET',

                success: function(response) {

                    // console.log(response);

                    let main_date = new Date(date);
                    let options = { year: 'numeric', month: 'long', day: 'numeric' };
                    let formattedDate = new Intl.DateTimeFormat('en-US', options).format(main_date);

                    let html = "";

                    if(response.length > 0){
                        response.forEach(element => {
                            // console.log(element.group_reference);

                            let student_name = "";
                            let buttons = "";
                            let status = "";

                            element.students.forEach(student => {
                                if(student_name != ""){
                                    student_name += `, ${student.name}`;
                                } else {
                                    student_name += `${student.name}`;
                                }
                            });

                            if(element.is_approved == 0){
                                status = "Pending";
                            } else if(element.is_approved == 1){
                                status = "Approved";
                            } else if(element.is_approved == 2){
                                status = "Rejected"; 
                            } else {
                                status = "Reschedule"; 
                            }

                            buttons = `
                                </br>
                                <div class="row">
                                    <div class="col-4">
                                        <button class="form-control btn btn-primary btn-rounded" onclick="approve('${element.schedule_id}')">Approved</button>
                                    </div>
                                    <div class="col-4">
                                        <button class="form-control btn btn-primary btn-rounded" onclick="remove('${element.schedule_id}')">Remove</button>
                                    </div>
                                    <div class="col-4">
                                        <button class="form-control btn btn-primary btn-rounded" onclick="reschedule('${element.schedule_id}')">Reschedule</button>
                                    </div>
                                </div>
                            `;
                            // buttons = `
                            //     </br>
                            //     <div class="row">
                            //         <div class="col-4">
                            //             <button ${ element.is_approved == 0 ? "" : "disabled" } class="form-control btn btn-primary btn-rounded" onclick="approve('${element.schedule_id}')">Approved</button>
                            //         </div>
                            //         <div class="col-4">
                            //             <button ${ element.is_approved == 0 ? "" : "disabled" } class="form-control btn btn-primary btn-rounded" onclick="remove('${element.schedule_id}')">Remove</button>
                            //         </div>
                            //         <div class="col-4">
                            //             <button ${ element.is_approved == 0 ? "" : "disabled" } class="form-control btn btn-primary btn-rounded" onclick="reschedule('${element.schedule_id}')">Reschedule</button>
                            //         </div>
                            //     </div>
                            // `;

                            html += `
                                For the group of <u><i>${student_name}</i></u>. <br>
                                <span style="font-size: 12px;"><i>* ${status}</i></span><br>
                                ${buttons} <br><hr>
                            `;

                        });
                    } else {
                        html += `
                            <p>- No defense schedule for this day.</p>
                        `;
                    }

                    $("#date_span").empty().append(formattedDate);
                    $("#modal_id_preview").empty().append(html);
                    $("#viewScheduleModal").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#viewScheduleModal").modal("show");
                }
            });
        }

        const closeModal = (modalName) => {
            $(`#${modalName}`).modal("hide");
        }

        const approve = (id) => {
            loadingFunc();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './coordinator_transaction/' + id,
                type: 'POST',
                data: JSON.stringify({
                    purpose : "approve"
                }),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Transaction success",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        const remove = (id) => {
            loadingFunc();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './coordinator_transaction/' + id,
                type: 'POST',
                data: JSON.stringify({
                    purpose : "remove"
                }),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Transaction success",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        const reschedule = (id) => {
            loadingFunc();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './coordinator_transaction/' + id,
                type: 'POST',
                data: JSON.stringify({
                    purpose : "reschedule"
                }),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Transaction success",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        const viewRequest = (id) => {
            // console.log(id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'get_request_info/' + id,
                type: 'GET',

                success: function(data) {
                    let result = JSON.parse(data);
                    let users = ""; 

                    users += "<b>Students:</b> ";
                    let index1 = 1;
                    result["students"].forEach(value => {
                        users += value.name;
                        if(index1 < result["students"].length){
                            index1++;
                            users += ",";   
                        }
                        users += " ";   
                    })

                    users += "<br><b>Panels:</b> ";
                    let index2 = 1;
                    result["panels"].forEach(value => {
                        users += value.name;
                        if(index2 < result["panels"].length){
                            index2++;
                            users += ",";   
                        }
                        users += " ";   
                    })

                    users += "<br><b>Adviser:</b> ";
                    let index3 = 1;
                    result["adviser"].forEach(value => {
                        users += value.name;
                        if(index3 < result["adviser"].length){
                            index3++;
                            users += ",";   
                        }
                        users += " ";   
                    })
                    

                    $("#request_preview_modal").empty().append(`
                        ${users}
                    `);

                    $("#footer_div").empty().append(`
                        <button class='btn btn-primary' onclick='approveRequest("${id}")'>Approve</button>&nbsp;
                        <button class='btn btn-danger' onclick='rejectRequest("${id}")'>Reject</button>&nbsp;
                    `);

                    $("#viewRequestModal").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#viewRequestModal").modal("show");
                }
            });
        }

        const viewRequestEdit = (id) => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'get_request_edit_info/' + id,
                type: 'GET',

                success: function(data) {
                    let result = JSON.parse(data);
                    $("#request_edit_preview_modal").empty().append(`
                        ${result.messages}
                    `);

                    $("#footer_div_edit").empty().append(`${result.buttons}`);

                    $("#viewEditRequestModal").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#viewEditRequestModal").modal("show");
                }
            });
        }

        const approveRequest = (id) => {
            loadingFunc();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'approve_request_info/' + id,
                type: 'PUT',

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Request Approved",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        const rejectRequest = (id) => {
            loadingFunc();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'reject_request_info/' + id,
                type: 'PUT',

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Request rejected",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        const declineEdit = (id) => {
            loadingFunc();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'edit_reject_request_info/' + id,
                type: 'PUT',

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Request Declined",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        const approveEdit = (id) => {
            loadingFunc();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'edit_approve_request_info/' + id,
                type: 'PUT',

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Request Approved",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }
    </script>
</body>

</html>
