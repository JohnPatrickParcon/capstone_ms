<?php

namespace App\Http\Controllers\StudentPageControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Auth;
use DB;

class ConsultationController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function checkRole(){
        if(Auth::user()->role != 4){
            abort(404);
        }
    }

    public function index(){
        $this->checkRole();
        $has_groupings = $this->getGroupings();
        if(!$has_groupings){
            // abort(404);
            return view("pages.errors.no_groupings_error");
        }
        // $schedules = $this->getMyAdviserConsultationHours();
        $schedules = $this->getMyAdviserConsultationHoursDistinct();
        // dd($schedules);
        return view('pages.student.consultation', ["has_groupings" => $has_groupings, "schedules" => $schedules]);
    }

    public function getGroupings(){
        $res = DB::table("groupings")->where("user_id", Auth::user()->id)->get();
        return count($res) > 0 ? true : false;
    }

    public function getMyAdviserConsultationHours(){
        $res = DB::table("groupings")->where("user_id", Auth::user()->id)->first();
        $group_reference = $res->group_reference;

        $current_date = date("Y-m-d H:m:s");
        $altered_date = date("Y-m-d H:i:s", strtotime('+8 hours', strtotime($current_date)));
        $day = $this->dayToInt(date("l", strtotime($altered_date)));

        $adviser = DB::table("advisers")->where("group_reference", $group_reference)->get();
        $panels = DB::table("panels")->where("group_reference", $group_reference)->get();

        $user_id_list = [];

        foreach ($adviser as $key => $value) {
            $user_id_list[] = $value->adviser_id;
        }
        foreach ($panels as $key => $value) {
            $user_id_list[] = $value->panel_id;
        }

        $schedules = DB::table("consultation_hours")
            ->select("users.name", "consultation_hours.*")
            ->join("users", "users.id", "consultation_hours.user_id")
            ->whereIn("user_id", $user_id_list)
            ->get();

        $alter_schedule = [];
        foreach ($schedules as $key => $value) {
            $start = DateTime::createFromFormat('H:i:s', $value->start_time);
            $end = DateTime::createFromFormat('H:i:s', $value->end_time);
            $time =  $start->format('h:i A') . " - " . $end->format('h:i A');
            $alter_schedule[] = [
                "name" => $value->name,
                "day" => $this->dayToString($value->day),
                "time" => $time,
            ];
        }
        return $alter_schedule;
    }

    public function getMyAdviserConsultationHoursDistinct(){
        $res = DB::table("groupings")->where("user_id", Auth::user()->id)->first();
        $group_reference = $res->group_reference;
        $adviser = DB::table("advisers")->where("group_reference", $group_reference)->get();
        $panels = DB::table("panels")->where("group_reference", $group_reference)->get();
        $user_id_list = [];
        $schedules = [];
        foreach ($adviser as $key => $value) {
            $user_id_list[] = $value->adviser_id;
        }
        foreach ($panels as $key => $value) {
            $user_id_list[] = $value->panel_id;
        }
        foreach ($user_id_list as $key => $value) {
            $temp = [];
            $user = DB::table("users")->select("name")->where("id", $value)->get();
            $res = DB::table("consultation_hours")
                ->select("consultation_hours.*")
                ->where("user_id", $value)
                ->orderBy("day", "asc")
                ->orderBy("start_time", "asc")
                ->get();
            foreach ($res as $key2 => $value2) {
                $start = DateTime::createFromFormat('H:i:s', $value2->start_time);
                $end = DateTime::createFromFormat('H:i:s', $value2->end_time);
                $time =  $start->format('h:i A') . " - " . $end->format('h:i A');
                $temp[] = [
                    "day" => $this->dayToString($value2->day),
                    "time" => $time,
                ];
            }
            if(count($res) > 0){
                $schedules[$value] = ["name" => $user[0]->name, "schedules" => $temp];
            } else {
                $schedules[$value] = ["name" => $user[0]->name, "schedules" => []];
            }
        }

        // dd($schedules);
        return $schedules;
    }

    public function dayToInt($day){
        $new_day = 0;
        if($day == "Monday"){ $new_day = 1; } 
        else if($day == "Tuesday") { $new_day = 2; }
        else if($day == "Wednesday") { $new_day = 3; } 
        else if($day == "Thursday") { $new_day = 4; } 
        else if($day == "Friday") { $new_day = 5; } 
        else if($day == "Saturday") { $new_day = 6; } 
        else { $new_day = 7; }
        return $new_day;
    }

    public function dayToString($day){
        $new_day = "";
        if($day == 1){ $new_day = "Monday"; } 
        else if($day == 2) { $new_day = "Tuesday"; }
        else if($day == 3) { $new_day = "Wednesday"; } 
        else if($day == 4) { $new_day = "Thursday"; } 
        else if($day == 5) { $new_day = "Friday"; } 
        else if($day == 6) { $new_day = "Saturday"; } 
        else { $new_day = "Sunday"; }
        return $new_day;
    }
}
