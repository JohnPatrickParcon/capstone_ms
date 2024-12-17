<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Capstone Management System | Messages</title>
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
                            {{-- <h3 class="mb-0"> Messages</h3> --}}
                        </div>
                    </div>
                    <div id="main_section">
                        <div class="card p-3">
                            <h3 class="text-center">Messages</h3>
                            <table id="messagesTable" class="table table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr style="background: green; color: white;">
                                        <th>#</th>
                                        <th>Students</th>
                                        <th data-orderable="false">Action</th>
                                    </tr>
                                </thead>
                            </table>
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
            loadMessagesTable();
        })

        const loadMessagesTable = () => {
            let dataTable = $('#messagesTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                pageLength: 20,
                lengthChange: false,
                ajax: {
                    url: "./messages",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'students_name', name: 'students_name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });
        }

        const viewMessages = (ref) => {
            window.location.assign("./messages/" + ref);
        }
    </script>
</body>

</html>
