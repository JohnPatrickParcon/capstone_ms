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
            background-color: rgb(0, 128, 0) !important;
            color: white !important;
            border: 1px solid rgb(0, 128, 0);
        }

        .page-link {
            color: rgb(0, 128, 0) !important;
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
                        @if ($role == 2 && !$is_published)
                            <div class="text-end">
                                <!-- {{$is_finalized. $is_published}} -->
                                <button class="btn btn-primary" onclick="publishedCapstone()">Publish</button>
                            </div>
                            <br>
                            
                        @elseif($role == 2 && $is_published)
                            <div class="text-end">
                                <p><b><i>* Capstone Published</i></b></p>
                            </div>
                            <br>
                        @endif
                        @if ($role == 3 && !$is_published)
                            <div class="text-end">
                            <p><b><i>* Capstone Not Published</i></b></p>
                            </div>
                            <br>
                            
                        @elseif($role == 3 && $is_published)
                            <div class="text-end">
                                <p><b><i>* Capstone Published</i></b></p>
                            </div>
                            <br>
                        @endif
                        <div class="p-5 card" id="preview" style="height: 100vh;">
                            <h3 class="text-center">{{ $capstone_info[0]->title }}</h3>
                            <br>
                            <iframe style="border: 1px solid;" src="{{ "../".$capstone_info[0]->file }}" height="100%"
                                width="100%" title="Capstone Preview" type="application/pdf"></iframe>ifr
                            <br>
                            <hr>
                            <input type="hidden" id="group_reference" name="group_reference"
                                value="{{ $capstone_info[0]->group_reference }}">
                            @if ($role == 3)
                                <textarea class="comment_section" name="comments" id="comments" cols="30" rows="10"></textarea>
                                <br>
                                <div class="text-end">
                                    <button class="btn btn-primary" onclick="saveComment()">Comment</button>
                                </div>
                            @endif
                        </div>
                        <br>
                    </div>
                    @if ($role == 3)
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
                    @endif

                    {{-- @if ($role == 2)
                        <div class="p-3 card">
                            <h3 class="text-center">Grades</h3>
                            <table id="gradesTable" class="table table-bordered" style="width: 100%;">
                                <thead>
                                    <tr style="background: green; color: white;">
                                        <th>Name</th>
                                        <th>Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($grades) > 0)
                                        @foreach ($grades as $item)
                                            @if (count($item["grades"]) > 0)
                                                @foreach ($item["grades"] as $grado)
                                                    <?php 
                                                        $index = 0;
                                                    ?>
                                                    <tr>
                                                        @if ($index == 0)
                                                            <td rowspan="{{ count($item['grades']) > 0 ? count($item['grades']) : 1 }}">{{ $item["name"] }}</td>
                                                            <?php 
                                                                $index++;
                                                            ?>
                                                        @endif
                                                        <td>{{ $grado->name }} - {{ $grado->score }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td rowspan="{{ count($item['grades']) > 0 ? count($item['grades']) : 1 }}">{{ $item["name"] }}</td>
                                                    <td>No grades submitted yet</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2" class="text-center">
                                                No Data to Show
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <br>
                            @if (!$is_finalized)
                                <div class="text-end">
                                    <button class="btn btn-primary" onclick="finalizedGrades()">Finalized</button>
                                </div>
                            @else
                                <div class="text-end">
                                    <p><i>Grades finalized *</i></p>
                                </div>
                            @endif
                        </div>
                    @endif --}}
                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-center text-muted d-block text-sm-left d-sm-inline-block"></span>
                        <span class="float-none mt-1 text-center float-sm-right d-block mt-sm-0"><b>Capstone Management
                                System</b></span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    @include('pages.adviser.modals.published')
    @include('pages.includes.scripts')

    <script>
        $(() => {
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
                    url: "../getComments/" + reference,
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

        const handleUpdateComment = (id, type) => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '../updateComments/'+type+'/' + id,
                type: 'PUT',

                success: function(data) {
                    $('#commentsTable').DataTable().ajax.reload();
                }
            });
        }

        const saveComment = () => {
            let reference = $("#group_reference").val();
            let comments = $("#comments").val();

            // console.log({
            //     reference, comments
            // });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '../saveCapstone/' + reference,
                type: 'POST',
                data: JSON.stringify({
                    reference,
                    comments
                }),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Comment Saved",
                    }).then(() => {
                        $("#comments").val("");
                        $('#commentsTable').DataTable().ajax.reload();
                    });
                }
            });
        }

        const finalizedGrades = () => {
            let reference = $("#group_reference").val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '../checkGrades/' + reference,
                type: 'GET',

                success: function(data) {
                    if(data){
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '../finalizedGrades',
                            type: 'POST',
                            data: JSON.stringify({
                                reference
                            }),
                            dataType: 'json',
                            cache: false,
                            contentType: false,
                            processData: false,

                            success: function(data) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Grades Finalized",
                                    text: "Successfully finalized the grades.",
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(data) {
                                errorMsg();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Missing Grades",
                        });
                    }
                }
            });
        }

        const errorMsg = () => {
            Swal.fire({
                icon: "error",
                title: "something went wrong",
            }).then(() => {
                // location.reload();
            });
        }

        const publishedCapstone = () => {
            let guard = "{{ $is_finalized }}";
            if(guard == 1){
                $("#publishModal").modal("show");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Grades not Finalized",
                }).then(() => {});
            }
        }

        const closeModal = (modalName) => {
            $(`#${modalName}`).modal("hide");
        }

        const publishAbstract = () => {
            let abstract = $("#abstract").val();
            let reference = $("#group_reference").val();

            //if(abstract != ""){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: './../publishcapstone/' + reference,
                    type: 'POST',
                    data: JSON.stringify({
                        abstract
                    }),
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        Swal.fire({
                            icon: "success",
                            title: "Capstone Published!",
                            text: "Successfully published the capstone.",
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(data) {
                        errorMsg();
                    }
                });
            }
        //}
    </script>
</body>

</html>
