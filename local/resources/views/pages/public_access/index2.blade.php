<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Public Access</title>  
        @include('pages.includes.styles')
    
    <style>
        body {
            background-image: url('local/public/images/clirdec_bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            width: 100%;
            height: 100dvh;
            display: grid;
            justify-items: center;
        }

        #temp_card {
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            width: 75%;
            display: flex;
            flex-direction: column;
            justify-items: center;
            margin-top: 4rem;
            margin-bottom: 4rem;
        }

        .form-control, .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--single .select2-search__field, .typeahead, .tt-query, .tt-hint {
            background-color: rgba(255, 255, 255, 0.5);
        }

        #display_table {
            border: solid 1px green;
            border-radius: 10px;
            background-color: white;
            width: 75%;
            align-self: center;
            padding: 3dvh;
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
    {{--<div style="min-height: 100vh; max-height: 100vh; overflow-y: auto;">--}}
        <div class="p-3" id="temp_card">
            <center><h3 class="mb-0 text-2xl"><b>Capstone Management System</b></h3></center>
            <center><h5 class="mb-0 text-xl"><i>Browse and Explore published Capstone here</i></h5></center>
            <br>
            @if (Auth::check())
                <div class="text-center" style="margin-top: -20px;">
                    <p><span class="loginregister" onclick="gotoHome()"><i>DASHBOARD</i></span></p>
                </div>
            @else
                <div class="text-lg text-center" style="margin-top: -20px;">
                    <p><span class="loginregister" onclick="gotoLogin()"><i>LOGIN</i></span> | <span class="loginregister" onclick="gotoRegister()"><i>REGISTER</i></span></p>
                </div>
            @endif
            <hr>
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
        {{--</div>--}}
    </div>

    @include('pages.public_access.modals.preview')
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
                    console.log(data);
                    $("#modal_id_preview").empty().append(`
                        <h4><b>Title: ${data.title}</b></h4>
                        <p>Abstract: ${data.abstract}</p>

                        <center><a href="${data.file}" target="_blank" class="text-blue-600">View Paper</a></center>
                    `);
                    $("#previewModal").modal("show");
                }
            });
        }

        const gotoLogin = () => {
            window.location.assign("./login");
        }

        const gotoRegister = () => {
            window.location.assign("./register");
        }

        const gotoHome = () => {
            window.location.assign("./home");
        }
    </script>

</body>
</html>