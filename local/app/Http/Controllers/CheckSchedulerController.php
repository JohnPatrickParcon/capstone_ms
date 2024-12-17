<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;

class CheckSchedulerController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index() {
        $groupings = $this->checkGroupings();

        if(count($groupings) > 0){
            $ref = $groupings[0]->group_reference;
            $res = DB::table("scheduler")->where("group_reference", $ref)->orderBy("schedule_id", "desc")->get();
            // dd($res);

            $all = [];
            $advisers = DB::table("advisers")->where("group_reference", $ref)->get();
            foreach ($advisers as $key => $value) {
                $all[] = ["user_id" => $value->adviser_id, "role" => "adviser"];
            }
            
            $panels = DB::table("panels")->where("group_reference", $ref)->get();
            foreach ($panels as $key => $value) {
                $all[] = ["user_id" => $value->panel_id, "role" => "panel"];
            }

            foreach ($res as $key => $value) {
                $status = "";
                $approved_by = json_decode($value->approved_by);
                $approver_id = [];
                foreach ($approved_by as $key2 => $value2) {
                    $approver_id[] = $key2;
                }

                if($value->purpose == "Defense"){
                    foreach ($all as $key2 => $value2) {
                        $result = DB::table("users")->where("id", $value2["user_id"])->get();
                        if(in_array($value2["user_id"], $approver_id)){
                            $id = $value2["user_id"];
                            $value3 = (int)$approved_by->$id;
                            if($value3 == 1){
                                $status .= "<u>" . $result[0]->name . ": Approved</u> "; 
                            } else if($value3 == 0){
                                $status .= "<u>" . $result[0]->name . ": Pending</u> ";                         
                            } else if($value3 == 3){
                                $status .= "<u>" . $result[0]->name . ": Reschedule</u> ";                         
                            } else {
                                $status .= "<u>" . $result[0]->name . ": Rejected</u> ";                         
                            }
                        } else {
                            $status .= "<u>" . $result[0]->name . ": Pending</u> ";   
                        }
                    }
                } else {
                    $result = DB::table("users")->where("id", $value->user_id)->get();
                    if($value->is_approved == 1){
                        $status .= "<u> ".$result[0]->name.": Approved</u> ";     
                    } else if($value->is_approved == 2) {
                        $status .= "<u> ".$result[0]->name.": Rejected</u> ";     
                    } else {
                        $status .= "<u> ".$result[0]->name.": Pending</u> ";     
                    }
                }

                $value->status_final = $status;
                $date = Carbon::createFromFormat('Y-m-d', $value->date);
                $value->final_date = $date->format('M d, Y');
                $dateTime = Carbon::createFromFormat('H:i:s', $value->time_requested);
                $value->final_time = $dateTime->format('h:i A');
            }

            return $res;
        } else {
            return [];
        }
    }

    public function checkGroupings() {
        $res = DB::table("groupings")->where("user_id",Auth::user()->id)->get();
        return count($res) > 0 ? $res : [];
    }
}
