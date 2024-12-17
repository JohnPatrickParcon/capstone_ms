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

        .required_tag {
            color: red;
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
                    <div id="main_section">
                        @if (!$has_groupings)
                            <div class="p-3 text-center card">
                                <i class="mdi mdi-inbox-multiple mdi-48px"></i>
                                <br>
                                <h3>Sorry, it seems that you are not in a group yet.</h3>
                            </div>
                        @else
                            @if (count($capstone_info) == 0)
                                @include('pages.student.forms.add')
                            @else
                                <input type="hidden" id="group_reference" value="{{$capstone_info[0]->group_reference}}">
                                <div class="p-5 card" id="preview" style="height: 100vh;">
                                    <h3 class="text-center">{{ $capstone_info[0]->title }}</h3>
                                    <br>
                                    <iframe style="border: 1px solid;" src="{{ $capstone_info[0]->file }}" height="100%" 
                                        width="100%" title="Capstone Preview" type="application/pdf"></iframe>
                                    <br>
                                    <hr>
                                    @if ($is_published)
                                        <div class="text-end">
                                            <b><i>*Captone Published</i></b>
                                        </div>
                                    @else
                                        <div class="text-end">
                                            <button class="btn btn-danger" onclick='handleRemoveCapstone("{{ $capstone_info[0]->group_reference }}")'>Remove </button>
                                            <button class="btn btn-primary" onclick="editDesc()">Update File</button>
                                            <button class="btn btn-primary" onclick="editFile()">Update Description</button>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-5 card" id="updateDesc" style="display: none">
                                    @include('pages.student.forms.update1')
                                </div>  
                                <div class="p-5 card" id="updateFile" style="display: none">
                                    @include('pages.student.forms.update2')
                                </div>  
                                <br>
                            @endif
                        @endif

                        <div class="p-3 card">
                            <h3 class="text-center">Comments</h3>
                            <table id="commentsTable" class="table table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr style="background: green; color: white;">
                                        <th>Comments</th>
                                        <th>Status</th>
                                        <th>Added by</th>
                                        <th data-orderable="false">Action</th>
                                    </tr>
                                </thead>
                            </table>
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

    <script>
        
        $(() => {
            @if (session('success'))
                Swal.fire({
                    icon: "success",
                    title: "Transaction successful",
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: "error",
                    title: "Something went wrong",
                });
            @endif

            loadTable();
        })

        const loadTable = () => {
            let reference = $("#group_reference").val();
            let dataTable = $('#commentsTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                pageLength: 20,
                lengthChange: false,
                ajax: {
                    url: "./getCommentsStudents/" + reference,
                },
                columns: [{
                        data: 'comments',
                        name: 'comments'
                    },
                    {
                        data: 'status_name',
                        name: 'status_name'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
        }


        const handleRemoveCapstone = (reference) => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './removeMyCapstone/' + reference,
                type: 'DELETE',

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Capstone Removed",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        $("#cancelEditDesc").click((e) => {
            e.preventDefault();
            $("#preview").show();
            $("#updateDesc").hide();
            $("#updateFile").hide();
        })

        $("#cancelEditFile").click((e) => {
            e.preventDefault();
            $("#preview").show();
            $("#updateDesc").hide();
            $("#updateFile").hide();
        })

        const editDesc = () => {
            $("#preview").hide();
            $("#updateDesc").hide();
            $("#updateFile").show();
        }

        const editFile = () => {
            $("#preview").hide();
            $("#updateDesc").show();
            $("#updateFile").hide();
        }

        const handleUpdateComment = (id) => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: './updateComments/2/' + id,
                type: 'PUT',

                success: function(data) {
                    $('#commentsTable').DataTable().ajax.reload();
                }
            });
        }

    </script>
</body>

</html>
