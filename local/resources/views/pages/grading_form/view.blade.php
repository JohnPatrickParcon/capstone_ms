<?php 
    $grades_checker = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Grading For Preview</title>
    @include('pages.includes.styles')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <div class="p-5">
        @include('pages.grading_form.header')
        {{-- <center>
            <p style="margin-top: -16px">
                <i>({{ Auth::user()->name }})</i>
            </p>
        </center> --}}
        <hr>
        <p>
            Capstone Project Title: <u>{{ $capstone_data->title }}</u><br>
            Semester and School Year: <u>{{ date("Y") }}</u><br>
            Name of Student/s:<br>
            <?php 
                if(count($members) > 0){
                    $index = 1;
                    foreach ($members as $key => $value) {
                        echo "&emsp;&emsp;Member " . $index . " (<b>M".$index."</b>): <u>" .$value->name. "</u><br>";
                        $index++;
                    }
                } else {
                    echo "No Members";
                }
            ?>
            Adviser's Name: <u>{{ Auth::user()->name }}</u><br>
            Presentation Date: <u>{{ $presentation_date }}</u>
        </p>
        <hr>
            <center><p><b>CAPSTONE PROJECT 2 EVALUATIVE CRITERIA for the BSIT PROGRAM</b></p></center>
            <table>
                <?php 
                    foreach ($grading_form as $key => $value) {
                        if($value->type == "section"){
                            echo "<tr>";
                            echo "<td rowspan='2'>" . $value->content . "</td>";
                            echo "<td colspan='".count($members)."'><center><b>Rating</b></center></td>";
                            echo "</tr>";
                            echo "<tr>";
                            $index = 1;
                            foreach ($members as $member_key => $member_value) {
                                echo "<td><center><b>M".$index."</b></center></td>";
                                $index++;
                            }
                            echo "</tr>";
                        } else if($value->type == "criteria") {
                            echo "<tr>";
                            echo "<td>" . $value->content . "</td>";
                            $index = 1;
                            foreach ($members as $member_key => $member_value) {
                                $val = 0;
                                // if(array_key_exists($key, $myResponse)){
                                //     if(array_key_exists($member_key, $myResponse[$key]["scores"])){
                                //         $val = $myResponse[$key]["scores"][$member_key]["score"];
                                //     }
                                // }

                                if(is_object($myResponse)){
                                    if(property_exists($myResponse, $key)){
                                        if(is_object($myResponse->$key->scores)){
                                            if(property_exists($myResponse->$key->scores, $member_key)){
                                                $val = $myResponse->$key->scores->$member_key->score;
                                            }
                                        } else if (is_array($myResponse->$key->scores)) {
                                            if(array_key_exists($member_key, $myResponse->$key->scores)){
                                                $val = $myResponse->$key->scores[$member_key]->score;
                                            }
                                        }
                                    }
                                }
                                echo "<td id='".$member_key."_".$key."' ondblclick='enterEditMode(\"".$member_key."\", \"".$key."\")' style=\"cursor: text;\">".$val."</td>";
                                $index++;
                            }
                            echo "</tr>";
                        } else if($value->type == "ratings"){
                            echo "<tr>";
                            echo "<td>" . $value->content . "</td>";
                            $index = 1;
                            foreach ($members as $member_key => $member_value) {
                                $val = 0;
                                // if(array_key_exists($key, $myResponse)){
                                //     if(array_key_exists($member_key, $myResponse[$key]["scores"])){
                                //         $val = $myResponse[$key]["scores"][$member_key]["score"];
                                //     }
                                // }
                                if(is_object($myResponse)){
                                    if(property_exists($myResponse, $key)){
                                        if(is_object($myResponse->$key->scores)){
                                            if(property_exists($myResponse->$key->scores, $member_key)){
                                                $val = $myResponse->$key->scores->$member_key->score;
                                            }
                                        } else if (is_array($myResponse->$key->scores)) {
                                            if(array_key_exists($member_key, $myResponse->$key->scores)){
                                                $val = $myResponse->$key->scores[$member_key]->score;
                                            }
                                        }
                                    }
                                }
                                echo "<td id='".$member_key."_".$key."' ondblclick='enterEditMode(\"".$member_key."\", \"".$key."\")'>".$val."</td>";
                                $index++;
                            }
                            echo "</tr>";
                        }
                    }
                    echo "<tr>";
                    echo "<td style='text-align: right;'><b>Overall Rating</b></td>";
                    $index = 1;
                    foreach ($members as $member_key => $member_value) {
                        $val = 0;

                        // if(is_object($myResponse)){
                        //     if(property_exists($myResponse, "OverAllRatingPanel")){
                        //         if(array_key_exists($member_key, $myResponse->OverAllRatingPanel->scores)){
                        //             $val = $myResponse->OverAllRatingPanel->scores[$member_key]->score;
                        //         }
                        //     }
                        // }

                        if(is_object($myResponse)){
                            if(property_exists($myResponse, "OverAllRatingPanel")){
                                if(is_object($myResponse->OverAllRatingPanel->scores)){
                                    if(property_exists($myResponse->OverAllRatingPanel->scores, $member_key)){
                                        $val = $myResponse->OverAllRatingPanel->scores->$member_key->score;
                                    }
                                } else if (is_array($myResponse->OverAllRatingPanel->scores)) {
                                    if(array_key_exists($member_key, $myResponse->OverAllRatingPanel->scores)){
                                        $val = $myResponse->OverAllRatingPanel->scores[$member_key]->score;
                                    }
                                }
                            }
                        }
                        
                        // if(array_key_exists("OverAllRatingPanel", $myResponse)){
                        //     if(array_key_exists($member_key, $myResponse["OverAllRatingPanel"]["scores"])){
                        //         $val = $myResponse["OverAllRatingPanel"]["scores"][$member_key]["score"];
                        //     }
                        // }
                        echo "<td id='".$member_key."' ondblclick='enterEditModeOverAll(\"".$member_key."\")'>".$val."</td>";
                        $index++;
                    }
                    echo "</tr>";
                ?>
            </table>
            <p>
                Name and Signature of Panel Member: <u>{{ Auth::user()->name }}</u><br>
                Date Signed: <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u>
            </p>
            <hr>
            @if (Auth::user()->role == 2)
                <center><b>Summary of Student Rating</b></center>
                <br>
                <center>
                <table style="width: 80%;">
                    <?php 
                        echo "<tr>";
                        echo "<td rowspan='2'><center><b>Panel Members</b></center></td>";
                        echo "<td colspan='".count($members)."'><center><b>Rating</b></center></td>";
                        echo "</tr>";
                        echo "<tr>";
                        $index = 1;
                        foreach ($members as $member_key => $member_value) {
                            echo "<td><center><b>M".$index."</b></center></td>";
                            $index++;
                        }
                        echo "</tr>";

                        foreach ($grader as $key => $value) {
                            
                            echo "<tr>";
                            echo "<td><center>".$value->name."</center></td>";
                            $index = 1;
                            foreach ($members as $member_key => $member_value) {
                                $val = 0;

                                if(is_object($all_response)){
                                    if(property_exists($all_response, $value->id)){
                                        $val_id = $value->id;
                                        if(property_exists($all_response->$val_id, "OverAllRatingPanel")){
                                            if(is_object($all_response->$val_id->OverAllRatingPanel->scores)){
                                                if(property_exists($all_response->$val_id->OverAllRatingPanel->scores, $member_key)){
                                                    $val = $all_response->$val_id->OverAllRatingPanel->scores->$member_key->score;
                                                } else {
                                                    $val = "Grades not Settled Yet";
                                                    $grades_checker = false;
                                                }
                                            } else if(is_array($all_response->$val_id->OverAllRatingPanel->scores)){
                                                if(array_key_exists($member_key, $all_response->$val_id->OverAllRatingPanel->scores)){
                                                    $val = $all_response->$val_id->OverAllRatingPanel->scores[$member_key]->score;
                                                } else {
                                                    $val = "Grades not Settled Yet";
                                                    $grades_checker = false;
                                                }
                                            }
                                        } else {
                                            $val = "Grades not Settled Yet";
                                            $grades_checker = false;
                                        }
                                    } else {
                                        $val = "Grades not Settled Yet";
                                        $grades_checker = false;
                                    }
                                }
                                echo "<td>".$val."</td>";
                                $index++;
                            }
                            echo "</tr>";
                        }
                    ?>
                </table>
                </center>

                <hr>
                <center><p><b>Project Status</b> (kindly check one)</p></center>
                <center>
                <table style="border: none; width: 80%;">
                    <tr style="border: none;">
                        <td style="font-size: 12px;border: none; text-align: center;">
                            {{-- &Gamma;&emsp;&emsp;Approved without revision --}}
                            <input checked type="radio" name="proj_status" value="Approved without revision">&emsp;&emsp;Approved without revision
                        </td>
                        <td style="font-size: 12px;border: none; text-align: center;">
                            {{-- &Gamma;&emsp;&emsp;Re-defense with minor revisions --}}
                            <input type="radio" name="proj_status" value="Re-defense with minor revisions">&emsp;&emsp;Re-defense with minor revisions
                        </td>
                    </tr>
                    <tr style="border: none;">
                        <td style="font-size: 12px;border: none; text-align: center;">
                            {{-- &Gamma;&emsp;&emsp;Approved with minor revisions --}}
                            <input type="radio" name="proj_status" value="Approved with minor revisions">&emsp;&emsp;Approved with minor revisions
                        </td>
                        <td style="font-size: 12px;border: none; text-align: center;">
                            {{-- &Gamma;&emsp;&emsp;Re-defense with major revisions --}}
                            <input type="radio" name="proj_status" value="Re-defense with major revisions">&emsp;&emsp;Re-defense with major revisions
                        </td>
                    </tr>
                    <tr style="border: none;">
                        <td style="font-size: 12px;border: none; text-align: center;">
                            {{-- &Gamma;&emsp;&emsp;Approved with major revisions --}}
                            <input type="radio" name="proj_status" value="Approved with major revisions">&emsp;&emsp;Approved with major revisions
                        </td>
                        <td style="font-size: 12px;border: none; text-align: center;">
                            {{-- &Gamma;&emsp;&emsp;Disapproved --}}
                            <input type="radio" name="proj_status" value="Disapproved">&emsp;&emsp;Disapproved
                        </td>
                    </tr>
                </table>
                </center>
                <hr>
                <br>
                <br>
                <center>
                <table style="border: none;">
                    <tr style="border: none;">
                        <td style="font-size: 12px;border: none;">
                            <center><span><b>{{ $chair_panel }}</b></span></center>
                        </td>
                        <td style="font-size: 12px;border: none;">
                            <center><span><b>{{ $enr_coordinator }}</b></span></center>
                        </td>
                    </tr>
                    <tr style="border: none;">
                        <td style="font-size: 12px;border: none;">
                            <center><span style="text-decoration: overline;">Name and Signature of the Chair Panel</span></center>
                        </td>
                        <td style="font-size: 12px;border: none;">
                            <center><span style="text-decoration: overline;">Name and Signature of the R & E Coordinator</span></center>
                        </td>
                    </tr>
                </table>
                </center>
                <hr>
            @endif
        @include('pages.grading_form.set-grade-modal')
        @include('pages.grading_form.set-summary-grade-modal')
        @include('pages.grading_form.footer')

        @if (Auth::user()->role == 2)
            <div class="text-end">
                <button id="finalizedScores" class="btn btn-primary">Finalized Scores</button>
            </div>
        @endif

    </div>
    @include('pages.includes.scripts')

    <script>
        const enterEditMode = (member_key, criteria_key) => {
            if("{{ $is_published }}"){
                Swal.fire({
                    icon: "info",
                    title: "Capstone Already Published",
                }).then(() => {
                    location.reload();
                });
            } else {
                $("#criteria_key").val(criteria_key);
                $("#member_key").val(member_key);
    
                $("#setGrade").modal('show');
            }
        }

        const enterEditModeOverAll = (member_key) => {
            if("{{ $is_published }}"){
                Swal.fire({
                    icon: "info",
                    title: "Capstone Already Published",
                }).then(() => {
                    location.reload();
                });
            } else {
                $("#criteria_key").val("OverAllRatingPanel");
                $("#member_key").val(member_key);

                $("#setGrade").modal('show');
            }
        }

        const enterEditModeSummary = (member_key, criteria_key) => {
            if("{{ $is_published }}"){
                Swal.fire({
                    icon: "info",
                    title: "Capstone Already Published",
                }).then(() => {
                    location.reload();
                });
            } else {
                $("#type").val("summary_ratings");
                $("#panel_id").val(criteria_key);
                $("#member_key").val(member_key);

                $("#setGradeSummary").modal('show');
            }
        }

        const closeModal = (modalName) => {
            $("#criteria_key").val("");
            $("#member_key").val("");
            $("#score").val("");

            $("#type").val("");
            $("#member_key").val("");
            $("#panel_id").val("");
            $("#summary_score").val("");

            $(`#${modalName}`).modal("hide");
        }

        const saveSummary = () => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '../saveResponseSummary',
                type: 'POST',
                data: JSON.stringify({
                    "type" : $("#type").val(),
                    "member" : $("#member_key").val(),
                    "panel_id" : $("#panel_id").val(),
                    "summary_score" : $("#summary_score").val(),
                    "form_id" : "{{ $grading_form_id }}",
                }),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Score Submitted",
                    }).then(() => {
                        location.reload();
                    });
                }
            });


            closeModal("setGradeSummary");
        }

        const save = () => {
            // console.log({
            //     "criteria" : $("#criteria_key").val(),
            //     "member" : $("#member_key").val(),
            //     "score" : $("#score").val(),
            // });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '../saveResponse',
                type: 'POST',
                data: JSON.stringify({
                    "criteria" : $("#criteria_key").val(),
                    "member" : $("#member_key").val(),
                    "score" : $("#score").val(),
                    "form_id" : "{{ $grading_form_id }}",
                }),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Score Submitted",
                    }).then(() => {
                        location.reload();
                    });
                }
            });


            closeModal("setGrade");

        }

        $("#finalizedScores").click(() =>{
            var selectedValue = $('input[name="proj_status"]:checked').val();
            let grades_checker = "{{ $grades_checker }}";
            
            if(grades_checker){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '../finalizedScores',
                    type: 'POST',
                    data: JSON.stringify({
                        selectedValue,
                        "form_id" : "{{ $grading_form_id }}",
                    }),
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
    
                    success: function(data) {
                        Swal.fire({
                            icon: "success",
                            title: "Score Finalized",
                        }).then(() => {
                            // location.reload();
                            history.back();
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Missing Grades/Grades Not Settled",
                }).then(() => {
                    location.reload();
                });
            }
        })

    </script>
</body>
</html>