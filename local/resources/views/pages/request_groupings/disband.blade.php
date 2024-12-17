<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Capstone Management System | Request Edit Groupings</title>
    @include('pages.includes.styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container-scroller">
        @include('pages.components.sidebar')
        <div class="container-fluid page-body-wrapper">
            @include('pages.components.navbar')
            <div class="main-panel" style="min-height: 100vh; max-height: 100vh; overflow-y: auto;">
                <div class="content-wrapper pb-0">
                    <div class="page-header flex-wrap">
                        <h3 class="mb-0"> Requesting a Groupings | Disband Group</h3>
                    </div>

                    <div id="main_section" class="card p-5">
                        <label for="">Student name</label>
                        <select name="students[]" multiple="multiple" id="student" class="js-example-basic-multiple form-control" style="width: 100%">
                            @foreach ($student_list as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
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
            $('.js-example-basic-multiple').select2({
                width: 'resolve',
                theme: "classic"
            });
            $('.js-example-basic-single').select2({
                width: 'resolve',
                theme: "classic"
            });
        })

        const handleSubmit = () => {
            let studentData_tmp = $('#student').select2('data');
            let final_student_list = [];
            Object.keys(studentData_tmp).forEach(key => {
                final_student_list[final_student_list.length] = studentData_tmp[key].id;
            });

            if(final_student_list.length == 0){
                Swal.fire({
                    icon: "error",
                    title: "Some fields are empty.",
                    text: "Something went wrong, please try again.",
                })
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '../request_my_groupings_add',
                    type: 'POST',
                    data: JSON.stringify({
                        final_student_list,
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
                            window.location.assign("../student-dashboard");
                        });
                    }
                });
            }
        }
    </script>
</body>

</html>
