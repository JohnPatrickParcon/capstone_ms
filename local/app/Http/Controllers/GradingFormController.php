<?php

namespace App\Http\Controllers;
use Auth;
use DB;

use Illuminate\Http\Request;

class GradingFormController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function checkRole(){
        if(Auth::user()->role != 2){
            abort(404);
        }
    }

    public function index($group_reference){
        $this->checkRole();
        // dd($group_reference);
        return view("pages.grading_form.index", ["reference" => $group_reference]);
    }

    public function save(Request $request){
        $data = $request->json()->all();
        // dd($data);
        DB::table("capstone_grading_forms")->insert([
            "group_reference" => $data["group_reference"],
            "data" => json_encode($data["evaluation_criteria"]),
            "coordinator_info" => json_encode([
                "chair" => $data["chair"],
                "rne" => $data["rne"],
            ])
        ]);

        return 1;
    }

    public function view($reference){
        if(Auth::user()->role == 4){
            abort(404);
        }

        $res = DB::table("groupings_capstone")->where("group_reference", $reference)->get();
        $members = DB::table("groupings")
            ->join("users", "users.id", "user_id")
            ->where("group_reference", $reference)
            ->get();

        if(count($res) > 0){
            $res = $res[0];
        } else {
            $res = ["title" => "No Capstone Title"];
        }

        if(count($members) > 0){
            $members = $members;
        } else {
            $members = [];
        }

        $grading_form_data = DB::table("capstone_grading_forms")->where("group_reference", $reference)->get();
        if(count($grading_form_data) == 0){
            return view('pages.errors.no_grading_form');
        }

        $myResponse = $grading_form_data[0]->responses;
        $chair = "";
        $rne = "";
        $grading_form_id = 0;
        $myResponse = [];
        $responseSummary = [];
        $all_response = [];

        if(count($grading_form_data) > 0){
            if($grading_form_data[0]->responses != NULL){
                $result = json_decode($grading_form_data[0]->responses);
                $my_id = Auth::user()->id;
                // dd($result->$my_id);
                if(!property_exists($result, Auth::user()->id)){
                    $myResponse = [];
                } else {
                    $myResponse = $result->$my_id;
                }

                // dd($result);

                if(!array_key_exists(Auth::user()->id, $result)){
                    $responseSummary = [];
                } else {
                    if(array_key_exists('summary_ratings', $result)){
                        $responseSummary = $result["summary_ratings"];
                    } else {
                        $responseSummary = [];
                    }
                }

                $all_response = $result;
            }
            

            $temp = json_decode($grading_form_data[0]->coordinator_info);
            $chair = $temp->chair;
            $rne = $temp->rne;
            $grading_form_id = $grading_form_data[0]->form_id;
            $grading_form_data = json_decode($grading_form_data[0]->data);
            // $myResponse = json_decode($grading_form_data[0]->responses)[Auth::];
        } else {
            $grading_form_data = [];
            $grading_form_id = 0;
            $chair = "No Chair Panel";
            $rne = "No R & E Coordinator";
            $myResponse = [];
        }

        $grader = [];
        $temp = DB::table("advisers")
            ->select("users.*")
            ->join("users", "users.id", "advisers.adviser_id")
            ->where("group_reference", $reference)
            ->get();

        foreach ($temp as $key => $value) {
            $value->role = "Adviser";
            $grader[] = $value;
        }

        $temp = DB::table("panels")
        ->select("users.*")
            ->join("users", "users.id", "panels.panel_id")
            ->where("group_reference", $reference)
            ->get();

        foreach ($temp as $key => $value) {
            $value->role = "Critic";
            $grader[] = $value;
        }

        $temp = DB::table("users")
            ->where("id", Auth::user()->id)
            ->get();

        foreach ($temp as $key => $value) {
            $value->role = "Chair";
            $grader[] = $value;
        }

        //$date;
        $defense_date = DB::table("scheduler")->where("group_reference", $reference)->where("is_approved", 1)->get();
        if(count($defense_date) > 0){
            $date = $defense_date[0]->date;
        } else {
            $date = "No Date";
        };

        // dd([
        //     "capstone_data" => $res, 
        //     "members" => $members, 
        //     "presentation_date" => $date, 
        //     "grading_form" => $grading_form_data,
        //     "grader" => $grader,
        //     "chair_panel" => $chair,
        //     "enr_coordinator" => $rne,
        //     "grading_form_id" => $grading_form_id,
        //     "myResponse" => $myResponse,
        //     "responseSummary" => $responseSummary,
        //     "all_response" => $all_response,
        // ]);

        // dd($all_response);

        $is_published = false;
        $asd = DB::table("published_capstone")->where("group_reference", $reference)->where("enabled", 1)->get();
        if(count($asd) > 0){
            $is_published = true;
        }

        return view("pages.grading_form.view", [
            "capstone_data" => $res, 
            "members" => $members, 
            "presentation_date" => $date, 
            "grading_form" => $grading_form_data,
            "grader" => $grader,
            "chair_panel" => $chair,
            "enr_coordinator" => $rne,
            "grading_form_id" => $grading_form_id,
            "myResponse" => $myResponse,
            "responseSummary" => $responseSummary,
            "all_response" => $all_response,
            "is_published" => $is_published,
        ]);
    }

    public function saveResponse(Request $request){
        $data = $request->json()->all();

        $user_response = [];

        $res = DB::table("capstone_grading_forms")->where("form_id", $data["form_id"])->get();
        if($res[0]->responses == NULL){
            $user_response[Auth::user()->id] = [];
            $user_response[Auth::user()->id][$data["criteria"]] = [];
            $user_response[Auth::user()->id][$data["criteria"]]["scores"] = [];
        } else {
            $response_json = json_decode($res[0]->responses, true);
            if(!array_key_exists(Auth::user()->id, $response_json)){
                $response_json[Auth::user()->id] = [];
                $response_json[Auth::user()->id][$data["criteria"]] = [];
                $response_json[Auth::user()->id][$data["criteria"]]["scores"] = [];
            } else {
                if(!array_key_exists($data["criteria"], $response_json[Auth::user()->id])){
                    $response_json[Auth::user()->id][$data["criteria"]] = [];
                    $response_json[Auth::user()->id][$data["criteria"]]["scores"] = [];
                } else {
                    if(!array_key_exists("scores", $response_json[Auth::user()->id][$data["criteria"]])){
                        $response_json[Auth::user()->id][$data["criteria"]]["scores"] = [];
                    }
                }
            }
            $user_response = $response_json;
        }

        $existed = false;
        $member_id = 0;

        $user_response[Auth::user()->id][$data["criteria"]]["scores"][$data["member"]] = ["member_id" => $data["member"], "score" => $data["score"]];

        DB::table("capstone_grading_forms")->where("form_id", $data["form_id"])->update([
            "responses" => json_encode($user_response)
        ]);

        return 1;
    }

    public function saveResponseSummary(Request $request){
        $data = $request->json()->all();
        // dd($data);

        $user_response = [];

        $res = DB::table("capstone_grading_forms")->where("form_id", $data["form_id"])->get();
        if($res[0]->responses == NULL){
            $user_response[$data["type"]] = [];
            $user_response[$data["type"]][$data["panel_id"]] = [];
            $user_response[$data["type"]][$data["panel_id"]][$data["member"]] = [];
        } else {
            $response_json = json_decode($res[0]->responses, true);
            if(!array_key_exists($data["type"], $response_json)){
                $response_json[$data["type"]] = [];
                $response_json[$data["type"]][$data["panel_id"]] = [];
                $response_json[$data["type"]][$data["panel_id"]][$data["member"]] = [];
            } else {
                if(!array_key_exists($data["panel_id"], $response_json[$data["type"]])){
                    $response_json[$data["type"]][$data["panel_id"]] = [];
                    $response_json[$data["type"]][$data["panel_id"]][$data["member"]] = [];
                } else {
                    if(!array_key_exists($data["member"], $response_json[$data["type"]][$data["panel_id"]])){
                        $response_json[$data["type"]][$data["panel_id"]][$data["member"]] = [];
                    }
                }
            }
            $user_response = $response_json;
        }

        $user_response[$data["type"]][$data["panel_id"]][$data["member"]] = ["member" => $data["member"], "score" => $data["summary_score"]];

        DB::table("capstone_grading_forms")->where("form_id", $data["form_id"])->update([
            "responses" => json_encode($user_response)
        ]);
        return 1;
    }

    public function finalizedScores(Request $request){
        $data = $request->json()->all();
        // dd($data);

        DB::table("capstone_grading_forms")->where("form_id", $data["form_id"])->update([
            "approved" => 1,
            "project_status" => $data["selectedValue"]
        ]);

        return 1;
    }
}
