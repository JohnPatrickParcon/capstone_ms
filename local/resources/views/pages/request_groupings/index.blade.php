<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Capstone Management System | Request Groupings</title>
    @include('pages.includes.styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container-scroller">
        @include('pages.components.sidebar')
        <div class="container-fluid page-body-wrapper">
            @include('pages.components.navbar')
            <div class="main-panel" style="min-height: 100vh; max-height: 100vh; overflow-y: auto;">
                <div class="pb-0 content-wrapper">
                    <div class="flex-wrap page-header">
                        <h3 class="mb-0"> Requesting a Groupings</h3>
                    </div>

                    @if (count($has_request) > 0)
                        <div class="alert alert-warning" role="alert" id="old_request_warning">
                            <br>
                            <center><h3><b>Current Request</b></h3></center>
                            <hr>

                            <div id="old_request">
                                <br><b>Student/s:</b> <u>{{$has_request[0]->text1}}</u>
                                <br><b>Panel/s:</b> <u>{{$has_request[0]->text2}}</u>
                                <br><b>Adviser:</b> <u>{{$has_request[0]->text3}}</u>
                            </div>
                            <br>
                            <b>Note:</B> You still have a pending request. Requesting a new one will void all current requests.
                        </div>
                    @endif

                    <div id="main_section" class="p-5 card">
                        <label for="">Expected groupmates</label>
                        <select name="students[]" multiple="multiple" id="student" class="js-example-basic-multiple form-control" style="width: 100%">
                            @foreach ($student_list as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                        <br>

                        <label for="">Expected adviser</label>
                        <select name="adviser" id="adviser" class="js-example-basic-single form-control" style="width: 100%">
                            @foreach ($panels_and_adviser_list as $adviser)
                                <option value="{{ $adviser->id }}">{{ $adviser->name }}</option>
                            @endforeach
                        </select>
                        <br>

                        <label for="">Expected panels</label>
                        <select name="panels[]" multiple="multiple" id="panel" class="js-example-basic-multiple form-control" style="width: 100%">
                            @foreach ($panels_and_adviser_list as $panel)
                                <option value="{{ $panel->id }}">{{ $panel->name }}</option>
                            @endforeach
                        </select>
                        <br>

                        <div class="text-end">
                            <button class="btn btn-primary" onclick="handleSubmit()">Submit Request</button>
                        </div>

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

    @include('pages.includes.scripts')

    <script>
        $(() => {
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

        const handleSubmit = () => {
            let studentData_tmp = $('#student').select2('data');
            let final_student_list = [];
            Object.keys(studentData_tmp).forEach(key => {
                final_student_list[final_student_list.length] = studentData_tmp[key].id;
            });
            final_student_list[final_student_list.length] = "{{ Auth::user()->id }}";

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

            if(final_student_list.length == 0 || final_panel_list.length == 0 || final_adviser_list.length == 0){
                Swal.fire({
                    icon: "error",
                    title: "Some fields are empty.",
                    text: "Something went wrong, please try again.",
                })
            } 
            if($finalfinal_student_list.length > 3)
            {
                Swal.fire({
                    icon: "error",
                    title: "Group member limit exceeded.",
                    text: "Maximum of three members per group",
                })
            }
            else {
                console.log({
                    final_student_list,
                    final_panel_list,
                    final_adviser_list
                });

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: './request_my_groupings',
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
                            title: "Request Sent",
                        }).then(() => {
                            window.location.assign("./student-dashboard");
                        });
                    }
                });
            }
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
