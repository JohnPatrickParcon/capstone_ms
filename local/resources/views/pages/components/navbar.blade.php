<nav class="navbar col-lg-12 col-12 p-lg-0 fixed-top d-flex flex-row" style="background-color: #d6dde5;" id="nav_var_id">
    <div class="navbar-menu-wrapper d-flex align-items-stretch justify-content-between">
        <button class="navbar-toggler navbar-toggler align-self-center mr-2" type="button" data-toggle="minimize">
            <i class="mdi mdi-menu"></i>
        </button>

        <ul class="navbar-nav navbar-nav-right ml-lg-auto">
            <li class="nav-item nav-profile dropdown border-0">
                <?php 
                    if(Auth::user()->role == 1){
                        $users_temp = "Admin";
                    } else if(Auth::user()->role == 2) {
                        $users_temp = "Coordinator";
                    } else if(Auth::user()->role == 3) {
                        $users_temp = "Adviser";
                    } else {
                        $users_temp = "Student";
                    }
                ?>

                <span class="profile-name" style="color: #3a4652;">{{ Auth::user()->name }} ({{ $users_temp }})</span>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>