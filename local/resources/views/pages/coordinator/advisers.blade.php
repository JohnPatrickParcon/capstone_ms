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
        .swal2-modal {
            min-height: 100px !important;
        }
        div:where(.swal2-container) input:where(.swal2-input){
            width: 80% !important;
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
                        <div class="text-center">
                            <h3>Advisers List</h3>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary" onclick="inviteAdviser()">Invite Adviser</button>
                        </div>
                        <br>
                        <table id="advisersTable" class="table table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr style="background: green; color: white;">
                                    <th>#</th>
                                    <th>Name</th>
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
        </div>
    </div>


    @include('pages.includes.scripts')

    <script>
        $(() => {
            loadTable();
        })

        const loadTable = () => {
            let dataTable = $('#advisersTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                pageLength: 20,
                lengthChange: false,
                ajax: {
                    url: "./advisers/",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });
        }

        const inviteAdviser = () => {
            Swal.fire({
                title: "Enter the email address",
                input: "email",
                inputAttributes: {
                    autocapitalize: "off"
                },
                showCancelButton: true,
                confirmButtonText: "Send Invitation",
                showLoaderOnConfirm: true,
                customClass: {
                    confirmButton: "btn btn-primary m-2",
                    cancelButton: "btn btn-secondary m-2",
                },
                preConfirm: async (email) => {
                    Swal.fire({
                        title: "Please wait...",
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    const invite_url = `./inviteAdviser`;
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: invite_url,
                            type: 'POST',
                            data: JSON.stringify({ email }),
                            dataType: 'json',
                            cache: false,
                            contentType: false,
                            processData: false,

                            success: function(data) {
                                // result = JSON.parse(data);
                                Swal.fire({
                                    icon: data.status,
                                    title: data.message,
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: "error",
                                    title: "Something went wrong, Please try again later.",
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        }
    </script>
</body>

</html>
