<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Capstone Management System | Public Access</title>
    @include('pages.includes.styles')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        #display_table {
            border: solid 1px green;
            border-radius: 10px;
            padding: 10px;
            background-color: white
        }

        .page-item.active .page-link {
            background-color: rgb(0,128,0) !important;
            color:white !important;
            border: 1px solid rgb(0,128,0);
        }
        .page-link {
            color: rgb(0,128,0) !important;
        }

        .loginregister {
            color: green;
            cursor: pointer;
        }
        .loginregister:hover {
            text-decoration: underline
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <div class="text-center sidebar-brand-wrapper d-flex align-items-center">
            </div>
            <ul class="nav" id="asdasd">
                <li class="nav-item nav-profile">
                    <div class="pr-3 nav-profile-text d-flex flex-column">
                        <h1 style="color: white;"><b><span><img src="{{ asset("local/public/images/clsu_logo.png") }}" style="height: 50px"></span>&nbsp;CMS</b></h1>
                    </div>
                    <hr>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./login">
                        <i class="mdi mdi-account-key menu-icon"></i>
                        <span class="menu-title">Login</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./register">
                        <i class="mdi mdi-account-plus menu-icon"></i>
                        <span class="menu-title">Register</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="container-fluid page-body-wrapper">
            <nav class="flex-row navbar col-lg-12 col-12 p-lg-0 fixed-top d-flex" style="background-color: #d6dde5;" id="nav_var_id">
                <div class="navbar-menu-wrapper d-flex align-items-stretch justify-content-between">
                    <button class="mr-2 navbar-toggler align-self-center" type="button" data-toggle="minimize">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </div>
            </nav>
            <div class="main-panel" style="min-height: 100vh; max-height: 100vh; overflow-y: auto;">
                <div class="pb-0 content-wrapper">
                    <div class="flex-wrap page-header">
                        <h3 class="mb-0"> Browse and Explore published Capstone here in Capstone Management System</h3>
                    </div>
                    <br>

                    <div id="display_table">
                        <table id="capstoneTable" class="table table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr style="background: green; color: white;">
                                    <th>Title</th>
                                    <th data-orderable="false">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-center text-muted d-block text-sm-left d-sm-inline-block"></span>
                        <span class="float-none mt-1 text-center float-sm-right d-block mt-sm-0"><b>Capstone Management System</b></span>
                    </div>
                </footer>
            </div>

            @include('pages.public_access.modals.preview')
        </div>
    </div>

    @include('pages.includes.scripts')

    <script>
        $(() => {
            loadTable();
        })

        const loadTable = () => {
            let dataTable = $('#capstoneTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                pageLength: 10,
                lengthChange: false,
                ajax: {
                    url: "./",
                },
                columns: [
                    {data: 'title', name: 'title'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });

        }


        const closeModal = (modalName) => {
            $(`#${modalName}`).modal("hide");
        }

        const handleViewCapstone = (encrypt_id) => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './previewCapstone/' + encrypt_id,
                type: 'GET',

                success: function(data) {
                    // console.log(data);
                    $("#modal_id_preview").empty().append(`
                        <h4><b>Title: ${data.title}</b></h4>
                        <p>Abstract: ${data.abstract}</p>
                    `);
                    $("#previewModal").modal("show");
                }
            });
        }
    </script>
</body>

</html>
