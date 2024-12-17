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
                        <div class="text-center">
                            <h3 class="mb-0"> Admin and Panels Consultation Schedule</h3>
                        </div>
                    </div>
                    <div id="main_section">
                        @if (!$has_groupings)
                            <div class="card p-3 text-center">
                                <i class="mdi mdi-inbox-multiple mdi-48px"></i>
                                <br>
                                <h3>Sorry, it seems that you are not in a group yet.</h3>
                            </div>
                        @else
                            <div class="row">
                                @foreach ($schedules as $key => $user_id)
                                    <div class="card p-3 col-md-6 col-lg-4">
                                        <p>
                                            <span style="font-size: 20px;">Name: <b>{{ $user_id['name'] }}</b></span><br><br>
                                            @if (count($user_id["schedules"]) > 0)
                                                @foreach ($user_id["schedules"] as $item)
                                                    <span>Day: {{ $item["day"] }} <i class="mdi mdi-calendar"></i></span><br>
                                                    <span>Time: {{ $item["time"] }} <i class="mdi mdi-clock-outline"></i></span><br><br>
                                                @endforeach
                                            @else
                                                <center><span><i class="mdi mdi-inbox-multiple"></i> No Schedules yet</span></center>
                                            @endif
                                            <hr>
                                        </p>
                                    </div>
                                @endforeach
                            </div> 
                        @endif
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
            console.log("Sample");
        })
    </script>
</body>

</html>
