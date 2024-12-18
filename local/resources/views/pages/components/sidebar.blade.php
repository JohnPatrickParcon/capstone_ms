<style>
    .nav .active {
        pointer-events: none;
    }
</style>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="text-center sidebar-brand-wrapper d-flex align-items-center">
    </div>
    <ul class="nav" id="asdasd">
        <li class="flex items-center justify-center nav-item nav-profile">
                <div class="flex flex-col">
                    <img src="{{ asset("local/public/images/clsu_logo.png") }}" style="height: 50px">
                    <h1 style="color: white;">
                        <b>
                            &nbsp;CMS
                        </b>
                    </h1>
                </div>
            <hr>
        </li>

        {{-- For Coordinator --}}
        @if (Auth::user()->role == 2)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('coordinator.dashboard') }}">
                    <i class="mdi mdi-home menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('students') }}">
                    <i class="mdi mdi-account-group menu-icon"></i>
                    <span class="menu-title">Students</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('advisers') }}">
                    <i class="mdi mdi-account-group menu-icon"></i>
                    <span class="menu-title">Advisers</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('capstones') }}">
                    <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                    <span class="menu-title">Capstones/Research</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('publishedCapstone') }}">
                    <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                    <span class="menu-title">Published Capstones</span>
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link" href="/grading_form">
                    <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                    <span class="menu-title">Grading Form</span>
                </a>
            </li> --}}
        @endif

        {{-- For Adviser --}}
        @if (Auth::user()->role == 3)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('adviser.dashboard') }}">
                    <i class="mdi mdi-home menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('requests') }}">
                    <i class="mdi mdi-bell menu-icon"></i>
                    <span class="menu-title">Requests</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('consultation-hours') }}">
                    <i class="mdi mdi-calendar-clock menu-icon"></i>
                    <span class="menu-title">Consultation Schedule</span>
                </a>
            </li>
        @endif

        {{-- For Student --}}
        @if (Auth::user()->role == 4)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('student.dashboard') }}">
                    <i class="mdi mdi-home menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('consultation') }}">
                    <i class="mdi mdi-calendar-account menu-icon"></i>
                    <span class="menu-title">Consultation</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('myCapstone') }}">
                    <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                    <span class="menu-title">Capstones</span>
                </a>
            </li>
        @endif

        {{-- <li class="nav-item">
            <a class="nav-link" href="/notifications">
                <i class="mdi mdi-bell menu-icon"></i>
                <span class="menu-title">Notifications <span style="color: rgb(176, 239, 176);" id="notification_count"></span></span>
            </a>
        </li> --}}

        <li class="nav-item">
            <a class="nav-link" href="{{ route('messages') }}">
                <i class="mdi mdi-chat menu-icon"></i>
                <span class="menu-title">Messages</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('settings') }}">
                <i class="mdi mdi-cogs menu-icon"></i>
                <span class="menu-title">Settings</span>
            </a>
        </li>
        <li class="nav-item sidebar-actions">
            <div class="nav-link">
                <div class="mt-2">
                    <ul class="pl-0 mt-2">
                        <li class="btn" style="color: white; border: solid 1px white;" onclick="handleLogout()">
                            Logout <i class="mdi mdi-logout"></i>
                        </li>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
</nav>
<script>
    
    const handleLogout = () => {
        document.getElementById('logout-form').submit();   
    }

    document.addEventListener("DOMContentLoaded", function() {
        // setInterval(function() {
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: './getNotifications/',
        //         type: 'GET',

        //         success: function(data) {
        //             if(data.length > 0){
        //                 $("#notification_count").empty().append("(" + data.length + ")");
        //             } else {
        //                 $("#notification_count").empty().append("");
        //             }
        //         },
        //         error: function() {
        //             console.error("Connection Error! Trying to reconnect");
        //         }
        //     });
        // }, 2000);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: './getNotifications/',
            type: 'GET',

            success: function(data) {
                if(data.length > 0){
                    $("#notification_count").empty().append("(" + data.length + ")");
                }
            },
            error: function() {
                console.error("Connection Error! Trying to reconnect");
            }
        });
    });
</script>
