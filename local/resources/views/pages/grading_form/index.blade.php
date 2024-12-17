<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Capstone Management System | Coordinator</title>
    @include('pages.includes.styles')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tiny.cloud/1/5b43hynscxw6cramft8a2lefwjkh9wmil4iy6ehz3lcs2w87/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: [ 'wordcount'],
            toolbar: 'undo redo | formatselect | bold italic | bullist numlist',
            menubar: 'edit format tools table',
        });
    </script>

    <style>
        table {
            width: 100%;
            height: auto;
        }

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        td {
            padding: 10px;
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
                    <div class="card p-5">
                        @include('pages.grading_form.header')
                        <div id="previewSection"></div>
                        <hr>
                        @include('pages.grading_form.footer')
                    </div>
                    

                    <hr>
                    <center><p><b>CREATE EVALUATIVE CRITERIA</b></p></center>
                    <textarea id="mytextarea"></textarea>
                    <div class="m-3 text-end">
                        <button class="btn btn-primary" id="addSectionButton">Add Section</button>
                        <button class="btn btn-primary" id="addCriteriaButton">Add Criteria</button>
                        <button class="btn btn-primary" id="addRatingButton">Add Ratings</button>
                        <button class="btn btn-primary" id="submitButton">Submit Grading Form</button>
                    </div>
                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block"></span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"><b>Capstone Management
                                System</b></span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    @include('pages.grading_form.add-modal')
    @include('pages.includes.scripts')

    <script>

        let evaluation_criteria = [];
        let eval_data = {};

        $("#submitButton").click(() => {
            $("#AddModalGradingForm").modal("show");
        })

        const save = () => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '../saveEvaluations',
                type: 'POST',
                data: JSON.stringify({
                    evaluation_criteria, 
                    "group_reference": "{{ $reference }}",
                    "chair": $("#chair_panel").val(),
                    "rne": $("#rne_coordinator").val(),
                }),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    closeModal("AddModalGradingForm");
                    Swal.fire({
                        icon: "success",
                        title: "Schedule Added",
                        text: "Successfully added the schedule.",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        $("#addSectionButton").click(() => {
            saveTinyMCE("section");
        })

        $("#addCriteriaButton").click(() => {
            saveTinyMCE("criteria");
        })

        $("#addRatingButton").click(() => {
            saveTinyMCE("ratings");
        })

        const saveTinyMCE = (type) => {
            let content = tinymce.get('mytextarea').getContent();
            eval_data = {
                type,
                content
            };
            evaluation_criteria.push(eval_data);
            tinymce.get('mytextarea').setContent(''); 
            renderTable();
            Swal.fire({
                icon: "success",
                title: "Added",
            });
        }

        const renderTable = () => {
            let html = "<table>";
            evaluation_criteria.forEach(element => {
                html += `<tr>
                        <td>${element.content}</td>
                    <tr>`;
            });
            html += "</table>"
            $("#previewSection").empty().append(html);
        }

        const closeModal = (modalName) => {
            $(`#${modalName}`).modal("hide");
        }
        
    </script>
</body>

</html>
