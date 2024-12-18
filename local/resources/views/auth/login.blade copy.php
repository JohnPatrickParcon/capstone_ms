<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Capstone Management System | Login</title>

    @include('pages.includes.styles')

    <style>
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
            padding: 10px !important;
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

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
                <div class="flex-grow row">
                    <div class="flex-row col-lg-6 login-half-bg d-flex" id="top_part">
                        <p class="flex-grow px-5 pt-3 text-center text-white font-weight-medium align-self-end"
                            style="margin: auto 0px;">
                            <b><span  class="header_26"></span></b>
                            <b><span class="image_text_span">
                                </span></b>
                            <br>
                            <br>
                        </p>
                    </div>
                    <div class="col-lg-6 d-flex align-items-center justify-content-center">
                        <div class="auth-form-transparent" style="padding: 5%;">
                            <div id="welcome_tag" class="grid mt-4 justify-items-center">
                                <a href="{{route('public')}}">
                                    <img src="{{ asset("local/public/images/clsu_logo.png") }}" alt="logo" class="w-16 ">
                                </a>
                                <p><b>Capstone Management System</b></p>
                            </div>
                            <h4><b>Hello, let's get started</b></h4>
                            <h6 class="font-weight-light">Sign in to continue.</h6>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="my-3 row">
                                    <label for="exampleInputEmail"><b>{{ __('Email Address') }}</b></label>
                                    <div class="input-group">
                                        <div class="bg-transparent input-group-prepend">
                                            <span class="bg-transparent input-group-text border-right-0">
                                                <i class="mdi mdi-account-outline" style="color: green;"></i>
                                            </span>
                                        </div>
                                        <input id="email" type="email"
                                            class="form-control form-control-lg border-left-0 @error('email') is-invalid @enderror"
                                            name="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="password" class="col-12"><b>{{ __('Password') }}</b></label>
                                    <div class="input-group">
                                        <div class="bg-transparent input-group-prepend">
                                            <span class="bg-transparent input-group-text border-right-0">
                                                <i class="mdi mdi-lock-outline" style="color: green;"></i>
                                            </span>
                                        </div>
                                        <input id="password" type="password"
                                            class="form-control form-control-lg border-left-0 @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="current-password">
                                    
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div> 
                                </div>

                                <div class="mb-3 row d-none">
                                    <label for="enabled"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Enabled') }}</label>

                                    <div class="col-md-6">
                                        <input id="enabled" type="text" class="form-control" name="enabled"
                                            value="1" required autocomplete="current-password">

                                        @error('enabled')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-2 row">
                                    <div class="col-12">
                                        <center>
                                            <button type="submit" class="btn form-control font-weight-medium customBtn">
                                                SIGN IN
                                            </button>
                                        </center>
                                    </div>
                                </div>
                                <div class="mb-0 row">
                                    <div class="col-12">
                                        <center>
                                            <p id="register_button" class="btn form-control font-weight-medium customBtn">
                                                REGISTER
                                            </p>
                                        </center>
                                    </div>
                                </div>
                                {{-- <div id="f_pass" class="text-end" style="color: green;">
                                    <p>Forgot Password?</p>
                                </div> --}}
                                <div class="contact-container">
                                    <p class="contact-text">For concerns please click here!</p>
                                    <a href="mailto:mainEmail"
                                        class="contact-link">Email ng system manager</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="flex-row col-lg-6 login-half-bg d-flex" id="bottom_part">
                        <p class="flex-grow px-5 pt-3 text-center text-white font-weight-medium align-self-end"
                            style="margin: auto 0px;">
                            <b><span  class="header_26"></span></b>
                            <b><span class="image_text_span">

                                </span></b>
                            <br>
                            <br>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pages.includes.scripts')
    <script>
        $(window).on("resize", function() {
            screen_shot()
        });

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

        $("#register_button").click(() => {
            window.location.assign("./register");
        })

        $(() => {
            screen_shot();
        })

        $("#welcome_tag").click(() => {
            window.location.assign("./");
        })
    </script>
</body>

</html>
