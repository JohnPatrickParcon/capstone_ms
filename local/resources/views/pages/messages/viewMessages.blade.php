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

    <style>
        .scrollable-container {
            width: 100%;
            height: 70vh;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
            border: none;
        }

        .item {
            padding: 10px;
            margin: 0;
            padding-bottom: 0;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
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
                    <div id="main_section">
                        @include('pages.messages.components.messages')
                    </div>
                </div>
                <footer class="pt-3 footer">
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
        let counter = 0;
        // $(() => {
        //     intervalId = setInterval(function() {
        //         reloadMessages();
        //     }, 2000);
        // })

        window.onload = function() {
            scrollMe();
        };

        const scrollMe = () => {
            var scrollable = document.getElementById('scrollable');
            scrollable.scrollTop = scrollable.scrollHeight;
        }

        const reloadMessages = () => {
            let group_reference = $("#group_reference").val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '../getMyMessages/' + group_reference,
                type: 'GET',

                success: function(data) {
                    // console.log(data);
                    let html = "";
                    data.forEach(element => {
                        if("{{ Auth::user()->id }}" == element.sender){
                            html += `
                                <div class="text-end">
                                    <p style="font-size: 10px; margin-bottom: 0px;">Me</p>
                                    <div class="item text-end" style="color: white; background-color: green; border-radius: 20px 10px 0px 20px; width: 100%;">
                                        <p>${element.message}</p>
                                    </div>
                                    <p style="font-size: 10px;">${element.time}</p>
                                </div>
                            `;
                        } else {
                            html += `
                                <div class="text-start">
                                    <p style="font-size: 10px; margin-bottom: 0px;">${element.sender_name}</p>
                                    <div class="item text-start" style="border-radius: 10px 20px 20px 0px; width: 80%;">
                                        <p>${element.message}</p>
                                    </div>
                                    <p style="font-size: 10px;">${element.time}</p>
                                </div>
                            `;
                        }
                    });

                    $("#scrollable").empty().append(html);
                    scrollMe();
                }
            });
        }

        const handleSendMessage = () => {
            let message = $("#message").val();
            let group_reference = $("#group_reference").val();

            if(message != ""){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '../sendMessage',
                    type: 'POST',
                    data: JSON.stringify({ message, group_reference }),
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        if(data.code == 1){
                            $("#message").val("");
                            reloadMessages();
                            //Swal.fire({
                            //    icon: "success",
                            //    title: "Message sent",
                            //}).then(() => {
                            //    $("#message").val("");
                            //    reloadMessages();
                            //});
                        } else {
                            console.log("Error");
                        }
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Message empty",
                });
            }
        }

    </script>
</body>

</html>
