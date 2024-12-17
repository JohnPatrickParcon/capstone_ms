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
                            <h3>Published Capstones</h3>
                        </div>
                        <br>
                        <table id="capstoneTable" class="table table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr style="background: green; color: white;">
                                    <th>#</th>
                                    <th>Capstone Title</th>
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
            let dataTable = $('#capstoneTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                pageLength: 20,
                lengthChange: false,
                ajax: {
                    url: "./publishedCapstone",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'title', name: 'title'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });
        }

        const unpublishCapstone = (id) => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './unpublished_capstone/' + id,
                type: 'PUT',

                success: function(data) {
                        Swal.fire({
                            icon: "success",
                            title: "Capstone Unpublished!",
                            text: "Successfully unpublished the capstone.",
                        }).then(() => {
                            location.reload();
                        });
                },
                error: function(data) {
                        errorMsg();
                }
            });
        }
    </script>
</body>

</html>
