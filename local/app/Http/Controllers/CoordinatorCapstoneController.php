<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth; 
use DB;
use App\Mail\RequestMailer;
use Mail;
use Redirect;

class CoordinatorCapstoneController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function checkRole(){
        if(Auth::user()->role != 2){
            abort(404);
        }
    }

    public function publishedCapstone(Request $request){
        if($request->ajax()){
            $capstones = DB::table("published_capstone")->where("enabled", 1)->get();
            return datatables()
                ->of($capstones)
                ->addIndexColumn()
                ->addColumn('action', function ($capstone) {
                    $btn = '';
                    $btn .= '<button class="btn" style="background: green; color: white;" onclick="unpublishCapstone(\''.$capstone->group_reference.'\')">Unpublish</button>&nbsp;&nbsp;';
                    return $btn;
                })
                ->rawColumns([
                    'action',
                ])
            ->make(true);
        }
        return view('pages.coordinator.published');
    }

    public function unpublished_capstone($reference){
        $published = DB::table('published_capstone')->where('group_reference', $reference)->delete();
        return view('pages.coordinator.published');
    }

    public function index(Request $request){
        $this->checkRole();
        if($request->ajax()){

            $capstones = [];
            $groups = DB::table("groupings")
                ->select("group_reference")
                ->distinct()
                ->get();

            foreach ($groups as $key => $value) {
                $temp_advisers = [];
                $temp_panels = [];
                $temp_capstone = [];
                $temp_names = [];

                $res_capstone = DB::table("groupings_capstone")
                    ->where("group_reference", "like", $value->group_reference)
                    ->get();

                foreach ($res_capstone as $key2 => $value2) {
                    $temp_capstone[] = $value2;
                }
                
                $res_adviser = DB::table("advisers")
                    ->select("users.name", "users.id")
                    ->join("users", "users.id", "advisers.adviser_id")
                    ->where("advisers.group_reference", "like", $value->group_reference)
                    ->get();

                foreach ($res_adviser as $key2 => $value2) {
                    $temp_advisers[] =["name" => $value2->name, "id" => $value2->id];
                }

                $res_panel = DB::table("panels")
                ->select("users.name", "users.id")
                    ->join("users", "users.id", "panels.panel_id")
                    ->where("panels.group_reference", "like", $value->group_reference)
                    ->get();

                foreach ($res_panel as $key2 => $value2) {
                    $temp_panels[] =["name" => $value2->name, "id" => $value2->id];
                }

                $res_students = DB::table("groupings")
                    ->select("users.name", "users.id")
                    ->join("users", "users.id", "groupings.user_id")
                    ->where("groupings.group_reference", "like", $value->group_reference)
                    ->get();

                $caps_status = DB::table("capstone_grading_forms")
                    ->select("project_status")
                    ->where("group_reference", "like", $value->group_reference)
                    ->get();

                foreach ($res_students as $key2 => $value2) {
                    $temp_names[] =["name" => $value2->name, "id" => $value2->id];
                }

                $capstones[] = ["capstone" => $temp_capstone, "advisers" => $temp_advisers, "panels" => $temp_panels, "names" => $temp_names, "reference" => $value->group_reference, "caps_status" => $caps_status];
            }

            // dd($capstones);

            return datatables()
                ->of($capstones)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if(count($row["capstone"]) > 0){
                        $btn .= "<button class='btn btn-primary' onclick='viewCapstone(\"".$row['reference']."\")'>View</button>&nbsp;";
                        $btn .= "<button class='btn btn-primary' onclick='scoreCapstone(\"".$row['reference']."\")'>Score</button>&nbsp;";
                    } else {
                        $btn .= "No valid action";
                        // $btn .= "<button class='btn btn-primary' onclick='viewCapstone(\"".$row['reference']."\")'>View</button>&nbsp;";
                        // $btn .= "<button class='btn btn-primary' onclick='scoreCapstone(\"".$row['reference']."\")'>Score</button>&nbsp;";
                    }
                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    $stat = '';
                    if(count($row["caps_status"]) > 0){
                        // if($row['capstone'][0]->statsu){

                        // }
                        $stat .= $row['caps_status'][0]->project_status;
                    } else {
                        $stat .= "No Status yet";
                    }
                    return $stat;
                })
                ->addColumn('adviser', function ($row) {
                    $adv = '';
                    if(count($row["advisers"]) > 0){
                        $adv .= $row['advisers'][0]['name'];
                    } else {
                        $adv .= "No adviser yet";
                    }
                    return $adv;
                })
                ->addColumn('name', function ($row) {
                    $name = '';
                    if(count($row["names"]) > 0){
                        foreach ($row["names"] as $key => $value) {
                            if($name != ""){
                                $name .= ", ";
                            }
                            $name .= $row['names'][0]['name'];
                        }
                    } else {
                        $name .= "No adviser yet";
                    }
                    return $name;
                })
                ->rawColumns([
                    'action', 'status', 'adviser', 'name'
                ])
            ->make(true);
        }
        return view("pages.coordinator.capstones");
    }

    public function coordinator_transaction($id, Request $request){
        // dd($reference);
        $data = $request->json()->all();
        // dd($data);

        DB::beginTransaction();
        try {
            if($data["purpose"] == "approve"){
                //$this->sendEmail("approve", $id);
                DB::table("scheduler")->where("schedule_id", $id)->update([
                    "is_approved" => 1
                ]);
            } else if ($data["purpose"] == "remove"){
                //$this->sendEmail("remove", $id);
                DB::table("scheduler")->where("schedule_id", $id)->update([
                    "is_approved" => 2
                ]);
            } else if ($data["purpose"] == "reschedule"){
                //$this->sendEmail("reschedule", $id);
                DB::table("scheduler")->where("schedule_id", $id)->update([
                    "is_approved" => 3
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            //dd($th);
        }

        return 1;
    }

    public function sendEmail($purpose, $id){

        $request_info = DB::table("scheduler")->where("schedule_id", $id)->get();
        if(count($request_info) > 0){
            $to_mail_adviser = DB::table("advisers")
                ->select("users.*")
                ->join("users", "users.id", "advisers.adviser_id")
                ->where("group_reference", $request_info[0]->group_reference)
                ->get();
            $to_mail_panel = DB::table("panels")
                ->select("users.*")
                ->join("users", "users.id", "panels.panel_id")
                ->where("group_reference", $request_info[0]->group_reference)
                ->get();
            $to_mail_students = DB::table("groupings")
                ->select("users.*")
                ->join("users", "users.id", "groupings.user_id")
                ->where("group_reference", $request_info[0]->group_reference)
                ->get();

            $message = "";

            if($purpose == "approve"){
                $message = "This email is to inform you that a Capstone Defense Schedule has been Settled.";
            } else if ($purpose == "remove"){
                $message = "This email is to inform you that a Capstone Defense Schedule has been Rejected.";
            } else if ($purpose == "reschedule"){
                $message = "This email is to inform you that the Capstone Defense Schedule needs to be rescheduled.";
            }

            $dateString = $request_info[0]->date . " " . $request_info[0]->time_requested;
            $date = new \DateTime($dateString);

            $data = [
                "adviser" => $to_mail_adviser, 
                "panel" => $to_mail_panel, 
                "students" => $to_mail_students, 
                "message" => $message,
                "date" => $date->format('l, F j, Y, g:i A')
            ];

            // dd($data);

            foreach ($to_mail_adviser as $key => $value) {
                Mail::to($value->email)->send(new RequestMailer($data));
            }
            foreach ($to_mail_panel as $key => $value) {
                Mail::to($value->email)->send(new RequestMailer($data));
            }
            foreach ($to_mail_students as $key => $value) {
                Mail::to($value->email)->send(new RequestMailer($data));
            }
        }
    }
}
