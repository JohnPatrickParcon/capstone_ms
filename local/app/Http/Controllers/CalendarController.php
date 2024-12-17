<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use DateTime;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function getTakenDate(){
        $adviser_list = [];
        $final_schedules = [];

        $result = DB::table("groupings")->where("user_id", "like", Auth::user()->id)->get();
        $my_group_ref = count($result) > 0 ? $result[0]->group_reference : "";

        $result = DB::table("advisers")->where("group_reference", "like", $my_group_ref)->get();
        foreach ($result as $key => $value) {
            $adviser_list[] = $value->adviser_id;
        }

        $result = DB::table("panels")->where("group_reference", "like", $my_group_ref)->get();
        foreach ($result as $key => $value) {
            $adviser_list[] = $value->panel_id;
        }

        $schedules = DB::table("scheduler")
            ->join("users", "users.id", "=", "scheduler.user_id")
            ->whereIn("user_id", $adviser_list)
            ->where("is_approved", 1)
            ->get();
        // dd($schedules);
        foreach ($schedules as $key => $value) {
            $final_schedules[] = [
                "title" => $value->name. "( " .$value->purpose." )",
                "start" => $value->date . "T" . $value->time,
                "end" => $value->date . "T" . $value->time,
            ];
        }

        // dd($schedules);
        return $final_schedules;
    }

    public function getAllDate(){
        $dates = [];
        $schedules = DB::table("scheduler")
            ->join("users", "users.id", "=", "scheduler.user_id")
            ->where("is_approved", 1)
            ->where("purpose", "Defense")
            ->get();
        foreach ($schedules as $key => $value) {
            $dates[] = [
                "title" => $value->name. "( " .$value->purpose." )",
                "start" => $value->date . "T" . $value->time,
                "end" => $value->date . "T" . $value->time,
            ];
        }
        return $dates;
    }

    public function getAllDateCoordinator(){
        $dates = [];
        $schedules = DB::table("scheduler")
            ->join("users", "users.id", "=", "scheduler.user_id")
            ->where("is_approved", 0)
            ->where("purpose", "Defense")
            ->get();

        // dd($schedules);

        // foreach ($schedules as $key => $value) {
        //     $dates[] = [
        //         "title" => $value->name. "( " .$value->purpose." )",
        //         "start" => $value->date . "T" . $value->time,
        //         "end" => $value->date . "T" . $value->time,
        //     ];
        // }


        foreach ($schedules as $key => $value) {
            $all = $this->getAllPanelsAndAdvisers($value->group_reference);
            $approved = json_decode($value->approved_by);
            $approved_id = [];
            $approved_count = 0;

            foreach ($approved as $key2 => $value2) {
                $approved_id[] = $key2;
            }

            foreach ($all as $key2 => $value2) {
                if(in_array($value2["user_id"], $approved_id)){
                    $my_id = $value2["user_id"];
                    if($approved->$my_id == "1"){
                        $approved_count++;
                    }
                }
            }

            if($approved_count == count($all)){
                $dates[] = [
                    "title" => $value->name. "( " .$value->purpose." )",
                    "start" => $value->date . "T" . $value->time,
                    "end" => $value->date . "T" . $value->time,
                    "id" => $value->schedule_id
                ];   
            }
        }

        // dd($dates);

        return $dates;
    }

    public function getAllPanelsAndAdvisers($ref){
        $all = [];
        $advisers = DB::table("advisers")->where("group_reference", $ref)->get();
        foreach ($advisers as $key => $value) {
            $all[] = ["user_id" => $value->adviser_id, "role" => "adviser"];
        }
        
        $panels = DB::table("panels")->where("group_reference", $ref)->get();
        foreach ($panels as $key => $value) {
            $all[] = ["user_id" => $value->panel_id, "role" => "panel"];
        }

        return $all;
    }


    public function checkdate($date){

        $adviser_list = [];
        $adviser_list_with_consultation_today = [];
        $result = DB::table("groupings")->where("user_id", "like", Auth::user()->id)->get();
        $my_group_ref = count($result) > 0 ? $result[0]->group_reference : "";
        $result = DB::table("advisers")->where("group_reference", "like", $my_group_ref)->get();
        foreach ($result as $key => $value) {
            $adviser_list[] = $value->adviser_id;
        }
        $result = DB::table("panels")->where("group_reference", "like", $my_group_ref)->get();
        foreach ($result as $key => $value) {
            $adviser_list[] = $value->panel_id;
        }

        $res = DB::table("scheduler")
            ->select("users.name", "scheduler.*")
            ->join("users", "users.id", "scheduler.user_id")
            ->whereIn("user_id", $adviser_list)
            ->where("date", "like", $date)
            ->where("is_approved", 1)
            ->orderBy("time", "asc")
            ->get();
        foreach ($res as $key => $value) {
            $groupings = DB::table("groupings")
                ->select("users.name", "groupings.*")
                ->join("users", "users.id", "groupings.user_id")
                ->where("group_reference", "like", $value->group_reference)
                ->get();
            $final_groupings = "";
            foreach ($groupings as $key2 => $value2) {
                if($final_groupings != ""){
                    $final_groupings .= ",";
                }
                $final_groupings .= " " .$value2->name; 
            }
            $value->groupings = $final_groupings .= " @ " . $value->time;
        }

        // $distinct_user_id = [];
        // foreach ($res as $key => $value) {
        //     if(!in_array($value->user_id, $distinct_user_id)){
        //         $distinct_user_id[] = $value->user_id;
        //     }
        // }

        $day_number = $this->dayToInt(date('l', strtotime($date)));
        $has_consultation = DB::table("consultation_hours")
            ->select("users.name", "consultation_hours.*")
            ->join("users", "users.id", "consultation_hours.user_id")
            ->where("day", $day_number)
            ->whereIn("user_id", $adviser_list)
            ->get();

        return json_encode(["result" => $res, "has_consultation" => $has_consultation]);
    }

    public function checkDateActions($date){

        $data = [];
        $res = DB::table("scheduler")
            ->where("scheduler.date", $date)
            ->where("scheduler.purpose", "Defense")
            // ->where("scheduler.is_approved", 1)
            ->get();

        foreach ($res as $key => $value) {

            $checker = json_decode($value->approved_by);
            if($checker != null){
                $approver1 = DB::table("advisers")->where("group_reference", $value->group_reference)->get();
                $approver2 = DB::table("panels")->where("group_reference", $value->group_reference)->get();
                $checker_id = [];
                foreach ($approver1 as $key1 => $value1) { $checker_id[] = $value1->adviser_id; }
                foreach ($approver2 as $key2 => $value2) { $checker_id[] = $value2->panel_id; }

                $guard = true;
                foreach ($checker_id as $key1 => $value1) {
                    if(property_exists($checker, $value1)){
                        if($checker->$value1 != 1){
                            $guard = false;
                        }
                    } else {
                        $guard = false;
                    }
                }
                
                if($guard){
                    $students = DB::table("groupings")
                        ->select("groupings.*", "users.name")
                        ->join("users", "users.id", "groupings.user_id")
                        ->where("groupings.group_reference", $value->group_reference)
                        ->get();

                    // $res[$key]->students = $students;
                    $data[$key] = new \stdClass();
                    $data[$key] = $res[$key];
                    $data[$key]->students = $students;
                }   
            }     
        }

        return $data;
    }

    public function dayToInt($day){
        if($day == "Monday"){
            return 1;
        } else if($day == "Tuesday"){
            return 2;
        } else if($day == "Wednesday"){
            return 3;
        } else if($day == "Thursday"){
            return 4;
        } else if($day == "Friday"){
            return 5;
        } else if($day == "Saturday"){
            return 6;
        } else {
            return 7;
        }
    }

    public function requestDefense($date, Request $request){

        $data = $request->json()->all();
        // dd($data);

        $my_group = DB::table("groupings")
            ->select("groupings.*", "advisers.adviser_id")
            ->join("advisers", "advisers.group_reference", "groupings.group_reference")
            ->where("user_id", Auth::user()->id)
            ->get();

        if(count($my_group) == 0){
            abort(500);
        }

        DB::beginTransaction();
        try {
            DB::table("scheduler")->insert([
                "user_id" => $my_group[0]->adviser_id,
                "group_reference" => $my_group[0]->group_reference,
                "purpose" => "Defense",
                "date" => $date,
                "time" => "08:00:00",
                "time_requested" => $data["time"],
            ]);

            DB::table("notifications")->insert([
                "targets" => "Defense Request",
                "content" => "Defense Request",
                "users" => json_encode([]),
                "seen_by" => json_encode([]),
                "added_by" => Auth::user()->id
            ]);

            DB::commit();
            return 1;
        } catch (\Throwable $th) {
            DB::rollback();
            abort(500);
        }
    }

    public function requestConsultation($date, $user_id){
        $my_group = DB::table("groupings")
            ->where("user_id", Auth::user()->id)
            ->get();

        if(count($my_group) == 0){
            abort(500);
        }

        $new_date = Carbon::createFromFormat('Y-m-d', $date);
        $day_in_number = $new_date->format('N');

        $res = DB::table("consultation_hours")->where("user_id", $user_id)->where("day", $day_in_number)->get();

        DB::beginTransaction();
        try {
            DB::table("scheduler")->insert([
                "user_id" => $user_id,
                "group_reference" => $my_group[0]->group_reference,
                "purpose" => "Consultation",
                "date" => $date,
                "time" => "08:00:00",
                "time_requested" => $res[0]->start_time,
            ]);
            DB::commit();
            return 1;
        } catch (\Throwable $th) {
            DB::rollback();
            abort(500);
        }
    }
}


