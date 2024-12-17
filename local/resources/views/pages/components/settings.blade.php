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
                <div class="pb-0 content-wrapper">
                    <div class="flex-wrap page-header">
                        <h3 class="mb-0"> Hi, welcome to Capstone Management System!</h3>
                    </div>
                    <div class="p-5 card">
                        <label for="">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" readonly value="{{ Auth::user()->email }}">
                        
                        <br>
                        <label for="">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="{{ Auth::user()->name }}">

                        
                        <div class="row">
                            <div class="mt-3 col-lg-6 col-sm-12">
                                <label for="">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="mt-3 col-lg-6 col-sm-12">
                                <label for="">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                        </div>
                        
                        <br>
                        <div class="mb-2">
                            <div class="text-end">
                                <input id="cb1" type="checkbox" name="showPass" id="showPass" onclick="togglePass()">
                                <label for="showPass" style="font-size: 14px">Show Password</label>
                            </div>
                        </div>
                        
                        <br><br>
                        <div class="flex self-end">
                            <button class="btn btn-primary" onclick="handleUpdateInfo()">Update</button>
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
    {{-- <script src="{{ asset('local/public/js/dashboard.js') }}"></script> --}}

    <script>
        const togglePass = () => {
            if ($("#password").attr("type") === "password") {
                $("#password").attr("type", "text")
                $("#confirm_password").attr("type", "text")
            } else {
                $("#password").attr("type", "password")
                $("#confirm_password").attr("type", "password")
            }
        }

        const handleUpdateInfo = () => {
            let data = {
                full_name : $("#full_name").val(),
                password : $("#password").val(),
                password_confirmation : $("#confirm_password").val(),
            };

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './settings/updateMyInfo',
                type: 'PUT',
                data: JSON.stringify(data),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    // console.log(data);
                    if(data.result == 1){
                        Swal.fire({
                            icon: data.icon,
                            title: data.title,
                            text: data.message,
                        });
                    } else {
                        let mess = "";

                        data.errors.forEach(element => {
                            if(mess != ""){
                                mess += " and " + element;
                            } else {
                                mess += element;
                            }
                        });

                        mess += ".";

                        Swal.fire({
                            icon: "error",
                            title: "Input Error",
                            text: mess,
                        });

                    }
                }
            });
        }
    </script>
</body>

</html>
