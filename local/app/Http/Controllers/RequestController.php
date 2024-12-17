<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class RequestController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function checkRole(){
        if(Auth::user()->role != 3){
            abort(404);
        }
    }

    public function index(Request $request){
        $this->checkRole();
        $groups = $this->getGroupRef();
        if($request->ajax()){
            $schedule_requests_temp = DB::table("scheduler")->whereIn("group_reference", $groups)->get();
            $schedule_requests_main = [];
            $excluded_id = [];

            foreach ($schedule_requests_temp as $key => $value) {
                // if($value->purpose == "Consultation"){
                //     if($value->user_id == Auth::user()->id){
                //         $schedule_requests_main[] = $value;
                //     }
                // } else {
                //     $schedule_requests_main[] = $value;
                // }

                // if($value->user_id == Auth::user()->id){
                    // if($value->purpose == "Consultation"){
                    //     if($value->user_id == Auth::user()->id){
                    //         $schedule_requests_main[] = $value;
                    //     }
                    // } else {
                    //     $schedule_requests_main[] = $value;
                    // }

                    // if($value->user_id == Auth::user()->id){
                    //     $schedule_requests_main[] = $value;
                    // }
                // }

                if($value->purpose == "Consultation"){
                    if($value->user_id == Auth::user()->id){
                        $schedule_requests_main[] = $value;
                    }
                } else {
                    $schedule_requests_main[] = $value;
                }

            }

            return datatables()
                ->of($schedule_requests_main)
                ->addIndexColumn()
                ->addColumn('action', function ($req) {
                    $btn = '';

                    $givenDate = $req->date;
                    $currentDate = date('Y-m-d');

                    if (strtotime($givenDate) < strtotime($currentDate)) {
                        $btn .= "<center><i>Ended</i></center>";
                    } else {

                        $approved = json_decode($req->approved_by);
                        $my_id = Auth::user()->id;
                        if(property_exists($approved, $my_id)){
                            if($approved->$my_id == "1"){
                                $btn .= "<span style='color:green;'>Approved</span>";
                            } else if($approved->$my_id == "2"){
                                $btn .= "<span style='color:red;'>Rejected</span>";
                            } else {
                                $btn .= "<span style='color:yellow;'>Reschedule</span>";
                            }
                        } else {
                            $btn .= "<button class='btn btn-primary' onclick='approveRequest(\"".$req->schedule_id."\")'>Approved</button>&nbsp;";
                            $btn .= "<button class='btn btn-danger' onclick='rejectRequest(\"".$req->schedule_id."\")'>Reject</button>&nbsp;";
                        }

                        // if($req->is_approved == 0){
                        //     $btn .= "<button class='btn btn-primary' onclick='approveRequest(\"".$req->schedule_id."\")'>Approved</button>&nbsp;";
                        //     $btn .= "<button class='btn btn-danger' onclick='rejectRequest(\"".$req->schedule_id."\")'>Reject</button>&nbsp;";
                        // } else {
                        //     $btn .= "<button disabled class='btn btn-primary'>Approved</button>&nbsp;";
                        //     $btn .= "<button disabled class='btn btn-danger'>Reject</button>&nbsp;";
                        // }
                    }

                    return $btn;
                })
                ->addColumn('status', function ($req) {
                    $status = '';
                    if($req->is_approved == 0){
                        $status = "<i style='color: gray;'>Pending Request</i>";
                    } else if ($req->is_approved == 1){
                        $status = "<i style='color: green;'>Approved</i>";
                    } else {
                        $status = "<i style='color: red;'>Rejected</i>";
                    }
                    return $status;
                })
                ->addColumn('students_name', function ($req) {
                    $temp_students = DB::table("groupings")
                        ->select("users.name")
                        ->join("users", "users.id", "=", "groupings.user_id")
                        ->where("group_reference", $req->group_reference)
                        ->get();

                    $students = '';
                    foreach ($temp_students as $key => $value) {
                            if($students != ""){
                                $students .= ", " . $value->name;
                            } else {
                                $students .= $value->name;
                            }
                        }
                    return $students;
                })
                ->rawColumns([
                    'action',
                    'status',
                    'students_name'
                ])
            ->make(true);
        }
        return view('pages.requests.index');
    }

    public function getGroupRef(){
        $group_list = [];
        $res1 = DB::table("advisers")->where("adviser_id", "like", Auth::user()->id)->get();
        foreach ($res1 as $key => $value) {
            $group_list[] = $value->group_reference;
        }

        $res2 = DB::table("panels")->where("panel_id", "like", Auth::user()->id)->get();
        foreach ($res2 as $key => $value) {
            $group_list[] = $value->group_reference;
        }

        return $group_list;
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

    public function approveRequest(Request $request){
        // $data = $request->json()->all();
        // $request_data = DB::table("scheduler")->where("schedule_id", $data["id"])->get();
        // $day = $this->dayToInt(date('l', strtotime($request_data[0]->date)));

        // $mySchedule = DB::table("consultation_hours")->where("day", $day)->where("user_id", Auth::user()->id)->get();

        // DB::beginTransaction();
        // try {
        //     DB::table("scheduler")->where("schedule_id", $data["id"])->update([
        //         "is_approved" => 1,
        //         "time" => $mySchedule[0]->start_time
        //     ]);
        //     DB::commit();
        //     return 1;
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     abort(500);
        // }

        $data = $request->json()->all();
        $request_data = DB::table("scheduler")->where("schedule_id", $data["id"])->get();

        $all = $this->getAllPanelsAndAdvisers($request_data[0]->group_reference);

        $approved = json_decode($request_data[0]->approved_by);
        $my_id = Auth::user()->id;
        $approved->$my_id = "1";
        $approved_id = [];
        $approved_count = 0;
        foreach ($approved as $key => $value) {
            $approved_id[] = $key;
        }

        foreach ($all as $key => $value) {
            if(in_array($value["user_id"], $approved_id)){
                if($approved->$my_id == "1"){
                    $approved_count++;
                }
            }
        }

        DB::table("scheduler")->where("schedule_id", $data["id"])->update([
            "approved_by" => json_encode($approved)
        ]);

        if($request_data[0]->purpose == "Consultation"){
            DB::table("scheduler")->where("schedule_id", $data["id"])->update([
                "is_approved" => 1
            ]);
        }

        

        // if($approved_count == count($all)){
        //     DB::table("scheduler")->where("schedule_id", $data["id"])->update([
        //         "is_approved" => 1
        //     ]);
        // } else {
        //     DB::table("scheduler")->where("schedule_id", $data["id"])->update([
        //         "is_approved" => 0
        //     ]);
        // }

        return 1;
    }

    public function rejectRequest(Request $request){
        // $data = $request->json()->all();
        // $request_data = DB::table("scheduler")->where("schedule_id", $data["id"])->get();
        // $day = $this->dayToInt(date('l', strtotime($request_data[0]->date)));
        // DB::beginTransaction();
        // try {
        //     DB::table("scheduler")->where("schedule_id", $data["id"])->update([
        //         "is_approved" => 2,
        //     ]);
        //     DB::commit();
        //     return 1;
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     abort(500);
        // }

        $data = $request->json()->all();
        $request_data = DB::table("scheduler")->where("schedule_id", $data["id"])->get();
        $all = $this->getAllPanelsAndAdvisers($request_data[0]->group_reference);

        $approved = json_decode($request_data[0]->approved_by);
        $my_id = Auth::user()->id;
        $approved->$my_id = "2";
        $approved_id = [];
        $approved_count = 0;
        foreach ($approved as $key => $value) {
            $approved_id[] = $key;
        }

        foreach ($all as $key => $value) {
            if(in_array($value["user_id"], $approved_id)){
                if($approved->$my_id == "1"){
                    $approved_count++;
                }
            }
        }

        DB::table("scheduler")->where("schedule_id", $data["id"])->update([
            "approved_by" => json_encode($approved)
        ]);

        // if($approved_count == count($all)){
        //     DB::table("scheduler")->where("schedule_id", $data["id"])->update([
        //         "is_approved" => 1
        //     ]);
        // } else {
        //     DB::table("scheduler")->where("schedule_id", $data["id"])->update([
        //         "is_approved" => 0
        //     ]);
        // }

        if($request_data[0]->purpose == "Consultation"){
            DB::table("scheduler")->where("schedule_id", $data["id"])->update([
                "is_approved" => 2
            ]);
        }

        return 1;
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
}
