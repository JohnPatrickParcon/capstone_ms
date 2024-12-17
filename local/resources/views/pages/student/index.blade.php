<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Capstone Management System | Student</title>
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

    <style>
        .carousel-control-next-icon {
            background-color: green;
            border-radius: 10%;
            padding: 24px;
        }

        .carousel-control-prev-icon {
            background-color: green;
            border-radius: 10%;
            padding: 24px;
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
                    <div id="defense_n_consultation_section"></div>
                    <div class="mb-3 text-end">
                        <button class="btn btn-primary" onclick="requestForGroupings()">Request for Groupings</button>
                        <button class="btn btn-primary" onclick="requestForEditGroupings()">Request to Edit Groupings</button>
                    </div>
                    <div id="main_section" class="">
                        <div id='calendar'></div>
                        <br><br><br>
                    </div>
                    <br>
                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-center text-muted d-block text-sm-left d-sm-inline-block"></span>
                        <span class="float-none mt-1 text-center float-sm-right d-block mt-sm-0"><b>Capstone Management System</b></span>
                    </div>
                </footer>
            </div>
            @include('pages.student.modals.events')
            @include('pages.student.modals.update')
            @include('pages.student.modals.selectTime')
        </div>
    </div>

    @include('pages.includes.scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './getTakenDate',
                type: 'GET',

                success: function(data) {
                    loadCalendar(data);
                }
            });

            $('.js-example-basic-multiple').select2({
                width: 'resolve',
                theme: "classic"
            });
            $('.js-example-basic-single').select2({
                width: 'resolve',
                theme: "classic"
            });

            checkShedulerStatus();
        });

        const checkShedulerStatus = () => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './getScheduler',
                type: 'GET',

                success: function(data) {
                    // loadCalendar(data);
                    // console.log(data);
                    html = "";

                    if(data.length > 0) {
                        html += `
                            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                        `;

                        html += `<div class="carousel-inner">`;

                        let index = 0;
                        data.forEach(element => {
                            let active = (index == 0) ? "active" : "";
                            if(element.purpose == "Defense"){
                                html += `<div class="p-3 card my-2 carousel-item ${active}">
                                    <div class="d-block w-100" alt="Slide 1">
                                        <center>
                                            <p><b>Request Type:</b> Defense<br>
                                            <b>Date and Time:</b> ${element.final_date} @ ${element.final_time}<br>
                                            <b>Request Status:</b> ${element.status_final}</p>
                                        </center>
                                    </div>
                                </div>`;
                            } else {
                                html += `<div class="p-3 card my-2 carousel-item ${active}">
                                    <div class="d-block w-100 h-50" >
                                        <center>
                                            <p><b>Request Type:</b> Consultation<br>
                                            <b>Date and Time:</b> ${element.final_date} @ ${element.final_time}<br>
                                            <b>Request Status:</b> ${element.status_final}</p>
                                        </center>
                                    </div>
                                </div>`;
                            }
                            index++;
                        });
                        html += `</div>`;

                        html += `<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                        <br>`;

                    }

                    $("#defense_n_consultation_section").empty().append(html);
                }
            });
        }

        const loadCalendar = (data) => {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                // initialView: 'dayGridMonth', 
                events: data,
                initialView: 'dayGridMonth', // Use 'multiMonthYear' or 'multiMonth' view
                views: {
                    multiMonth: {
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
                url: './checkDate/' + date,
                type: 'GET',

                success: function(response) {
                    let response_data = JSON.parse(response);
                    let data = response_data.result;
                    let has_consultation = response_data.has_consultation;
                    // console.log(data);

                    let main_date = new Date(date);
                    let options = { year: 'numeric', month: 'long', day: 'numeric' };
                    let formattedDate = new Intl.DateTimeFormat('en-US', options).format(main_date);
                    let html = ``;
                    let button = `<button type="button" onclick="requestDefense('${date}')" class="btn form-control" style="background-color: green; color: white;">Request for Defense</button>`;

                    if(data.length > 0) {
                        data.forEach(item => {
                            // console.log(item);
                            if(item.purpose == "Consultation"){
                                html += `- ${item.groupings} (${item.purpose} with ${item.name})<br>`;
                            } else {
                                html += `- ${item.groupings} (${item.purpose})<br>`;
                            }
                        });
                    } else {
                        html += "- No event scheduled for the selected date.";
                    }

                    if(has_consultation.length > 0){
                        has_consultation.forEach(item => {
                            button += `<button type="button" onclick="requestConsultation('${date}', '${item.user_id}')" class="btn form-control" style="background-color: green; color: white;">Request Consultation for ${item.name}</button>`;
                        });
                    }

                    $(".modal-title").empty().html("Date: " + formattedDate);
                    $(".modal-body").empty().html(html);
                    $(".modal-footer").empty().html(button);
                    $("#events_modal").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#events_modal").modal('show');
                }
            });
        }

        const requestDefense = (date) => {
            $("#select_time_div").empty().html(`
                <label>Time of defense</label>
                <input type="time" id="appt-time" name="appt-time" class="form-control">
            `);

            $("#footer_button").empty().html(`
                <button class="btn btn-primary" onclick="sendRequestDefense('${date}')">Submit Request</button>
            `);

            $("#events_modal").modal("hide");
            $("#select_hour_modal").modal("show");
        }

        const handleCloseModal = (name) => {
            $("#"+name).modal("hide");
        }

        const sendRequestDefense = (date) => {
            let time = $("#appt-time").val();

            if(time == ""){
                Swal.fire({
                    icon: "error",
                    title: "Empty field",
                });
                $("#select_hour_modal").modal("hide");
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: './requestDefense/' + date,
                    type: 'POST',
                    data: JSON.stringify({
                        date, time
                    }),
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        Swal.fire({
                            icon: "success",
                            title: "Schedule Added",
                            text: "Successfully added the schedule. Please wait for the coordinator to approve your request. Thank you.",
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            }

        }

        const requestConsultation = (date, user_id) => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './requestConsultation/' + date + "/" + user_id,
                type: 'POST',
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

        const requestForGroupings = () => {
            if("{{ $has_groupings }}"){
                already_has_groupings();
            } else {
                requestGroupings();
            }
        }

        const already_has_groupings = () => {
            Swal.fire({
                title: "Error",
                text: "You are currently in a group.",
                icon: "error",
                // showCancelButton: true,
                confirmButtonColor: "green",
            }).then((result) => {
                if (result.isConfirmed) {
                    // requestGroupings();
                }
            });
        }

        const requestGroupings = () => {
            window.location.assign("./request-groupings");
        }

        const requestForEditGroupings = () => {
            if("{{ $has_groupings }}"){
                $("#update_groupings_modal").modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $("#update_groupings_modal").modal("show");
            } else {
                Swal.fire({
                    title: "No Group",
                    text: "No group to update",
                    icon: "error",
                    confirmButtonColor: "green",
                });
            }
        }

        const updateGroupButton = () => {
            let selected_option = $("#update_option").val();

            if(selected_option == 3){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: './request_my_groupings_disband',
                    type: 'POST',
                    data: JSON.stringify({}),
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        Swal.fire({
                            icon: "success",
                            title: "Request Sent",
                        }).then(() => {
                            window.location.assign("./student-dashboard");
                        });
                    }
                });
            } else {
                window.location.assign("./request-edit-groupings/" + selected_option);
            }
        }
    </script>
</body>

</html>
