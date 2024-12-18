<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Capstone Management System | Register</title>
    
        @include('pages.includes.styles')
    
        <style>
            #cb1{
                accent-color: #008000;
            }
            #register_button {
                cursor: pointer;
            }
            .contact-container {
                padding: 20px;
                background-color: #f4f4f4;
                border-radius: 8px;
                text-align: center;
                max-width: 300px;
                margin: auto;
                margin-top: 5px;
            }
            .contact-text {
                margin-bottom: 10px;
            }
            .contact-link {
                color: #008000;
                text-decoration: none;
                font-weight: bold;
                display: block;
            }
            .customBtn {
                border-radius: 10px;
                padding: 5px !important;
                background-color: white !important;
                color: green !important;
                border-color: green !important;
            }
            .customBtn:hover {
                background-color: green !important; 
                color: white !important; 
                border-color: white !important;
            }
            #welcome_tag {
                cursor: pointer;
            }
        </style>
    </head>

<body class="grid h-full bg-green-600 justify-items-center">
    <div class="w-1/2 px-10 my-3 rounded-lg bg-slate-200 justify-items-center">
        <div id="welcome_tag" class="grid mt-4 justify-items-center">
                <a href="{{route('public')}}">
                    <img src="{{ asset("local/public/images/clsu_logo.png") }}" alt="logo" class="w-16">
                </a>
                <p><b>Capstone Management System</b></p>
        </div>
        <h4><b>New here?</b></h4>
        <h6 class="m-1 font-weight-light">Signing up is easy. It only takes a few steps</h6>
        <form id="reg_form_id" method="POST" action="{{ route('register') }}">
            @csrf
            <label for="full_name"><b>{{ __('Name') }}</b>&nbsp;<span style="color: red">*</span></label>
            <div class="mb-2 input-group">
                <div class="bg-transparent input-group-prepend">
                    <span class="bg-transparent input-group-text border-right-0">
                        <i class="mdi mdi-account-outline" style="color: green;"></i>
                    </span>
                </div>
                <input id="full_name" type="text"
                    class="form-control form-control-l border-left-0 @error('full_name') is-invalid @enderror"
                    name="full_name" value="{{ old('full_name') }}" required autocomplete="full_name" autofocus>

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <label for="email" class="col-12"><b>{{ __('Email Address') }}</b>&nbsp;<span style="color: red">*</span></label>
            <div class="mb-2 input-group">
                <div class="bg-transparent input-group-prepend">
                    <span class="bg-transparent input-group-text border-right-0">
                        <i class="mdi mdi-email-outline" style="color: green;"></i>
                    </span>
                </div>
                <input id="email" type="email"
                    class="form-control form-control-l border-left-0 @error('email') is-invalid @enderror"
                    name="email"  value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <label for="password" class="col-12"><b>{{ __('Password') }}</b>&nbsp;<span style="color: red">*</span></label>
            <div class="mb-2 input-group">
                <div class="bg-transparent input-group-prepend">
                    <span class="bg-transparent input-group-text border-right-0">
                        <i class="mdi mdi-lock-outline" style="color: green;"></i>
                    </span>
                </div>
                <input id="password" type="password" maxlength="100"
                    class="form-control form-control-l border-left-0 @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        
            <label for="password-confirm" class="col-12"><b>{{ __('Confirm Password') }}</b>&nbsp;<span style="color: red">*</span></label>
            <div class="mb-2 input-group">
                <div class="bg-transparent input-group-prepend">
                    <span class="bg-transparent input-group-text border-right-0">
                        <i class="mdi mdi-lock-outline" style="color: green;"></i>
                    </span>
                </div>
                <input id="password-confirm" type="password"
                    class="form-control form-control-l border-left-0"
                    name="password_confirmation" maxlength="100" autocomplete="new-password">
            </div>

            <div>
                <input name="role" value="2" type="hidden">
            </div>

            <div class="mb-2">
                <div class="text-end">
                    <input id="cb1" type="checkbox" name="showPass" id="showPass" onclick="togglePass()">
                    <label for="showPass" style="font-size: 14px">Show Password</label>
                </div>
            </div>

            <div class="mt-1 mb-2">
                <div class="col-12">
                    <button type="submit"
                        class="btn btn-lg font-weight-medium auth-form-btn btn-block form-control customBtn"
                        id="submit_button">
                        REGISTER
                    </button>
                </div>
            </div>

            <div class="mt-1 mb-2">
                <div class="col-12">
                    <p id="login_button" class="font-weight-medium btn form-control customBtn">
                        SIGN IN
                    </p>
                </div>
            </div>
        </form>
    </div>
            
    

    @include('pages.includes.scripts')
    
    <script>
        const togglePass = () => {
            if ($("#password").attr("type") === "password") {
                $("#password").attr("type", "text")
                $("#password-confirm").attr("type", "text")
            } else {
                $("#password").attr("type", "password")
                $("#password-confirm").attr("type", "password")
            }
        }
    </script>
</body>

<script>
    $(window).on("resize", function() {
        screen_shot()
    });

    $("#login_button").click(() => {
        window.location.assign("./login");
    })

    const screen_shot = () => {
        if ($(window).width() <= 991) {
            $(".image_text_span").css("font-size", "18px");
            $(".image_text_span").html(`
                Discover the Capstone Management System, streamlining projects.
            `);
            $(".header_26").html(
                ``
            )
            $("#top_part").removeClass("d-flex");
            $("#bottom_part").removeClass("d-none");
            $("#top_part").addClass("d-none");
            $("#bottom_part").addClass("d-flex");
        } else {
            $(".image_text_span").css("font-size", "24px");
            $(".image_text_span").html(`
            Learn more about the Capstone Management System, a comprehensive platform designed to streamline the management and execution of capstone projects in educational institutions.
            `);
            
            $(".header_26").css("font-size", "50px");
            $(".header_26").html(
                `Welcome to web-based Capstone Management System!<br>`
            )
            $("#top_part").removeClass("d-none");
            $("#bottom_part").removeClass("d-flex");
            $("#top_part").addClass("d-flex");
            $("#bottom_part").addClass("d-none");
        }
    }

    $(() => {
        screen_shot();
    })
</script>
</html>
