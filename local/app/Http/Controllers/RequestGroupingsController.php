<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Mail\GroupingsEmailer;
use Mail;

class RequestGroupingsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){

        if(Auth::user()->role != 4){
            abort(404);
        }

        $final_student_list = $this->getAllAvailableStudents();
        $final_panel_adviser_list = $this->getAllAvailablePanelsAndAdvisers();

        $has_request = DB::table("groupings_request")->where("request_by", Auth::user()->id)->where("is_approved", 0)->get();
        if(count($has_request) > 0){
            $data = json_decode($has_request[0]->data);
            $student_list = DB::table("users")->select("name")->whereIn("id", $data->final_student_list)->get();
            $panel_list = DB::table("users")->select("name")->whereIn("id", $data->final_panel_list)->get();
            $adviser_list = DB::table("users")->select("name")->whereIn("id", $data->final_adviser_list)->get();
            // dd($data);

            $students = "";
            foreach ($student_list as $key => $value) {
                if($students != ""){
                    $students .= ", ";
                }
                $students .= $value->name;
            }
            $panels = "";
            foreach ($panel_list as $key => $value) {
                if($panels != ""){
                    $panels .= ", ";
                }
                $panels .= $value->name;
            }
            $adviser = "";
            foreach ($adviser_list as $key => $value) {
                if($adviser != ""){
                    $adviser .= ", ";
                }
                $adviser .= $value->name;
            }

            $has_request[0]->text1 = $students;
            $has_request[0]->text2 = $panels;
            $has_request[0]->text3 = $adviser;
        }

        return view("pages.request_groupings.index", ["student_list" => $final_student_list, "panels_and_adviser_list" => $final_panel_adviser_list, "has_request" => $has_request]);
    }

    public function get_request_info($encrypted_id){
        $id = decrypt($encrypted_id);
        $res = DB::table("groupings_request")->where("id", $id)->get();

        if(count($res) == 0){
            abort(404);
        }

        $data = json_decode($res[0]->data);

        $names = DB::table("users")->select("name")->whereIn("id", $data->final_student_list)->get();
        $adviser = DB::table("users")->select("name")->where("id", $data->final_adviser_list)->get();
        $panels = DB::table("users")->select("name")->whereIn("id", $data->final_panel_list)->get();

        return json_encode([
            "students" => $names, "adviser" => $adviser, "panels" => $panels,
        ]);

    }

    public function reject_request_info($encrypted_id){
        $id = decrypt($encrypted_id);
        DB::table("groupings_request")->where("id", $id)->update([
            "is_approved" => 2
        ]);

        $res = DB::table("groupings_request_update")->where("id", $id)->get();
        $users_list = [];
        foreach (json_decode($res[0]->data)->final_student_list as $key => $value) {
            array_push($users_list, $value);
        }

        DB::table("notifications")->insert([
            "targets" => "Groupings Request",
            "content" => "Groupings has been Rejected!",
            "users" => json_encode($users_list),
            "seen_by" => json_encode([]),
             "added_by" => Auth::user()->id
         ]);

        return 1;
    }

    public function approve_request_info($encrypted_id){

        $reference = $this->generateReference();
        $id = decrypt($encrypted_id);

        DB::beginTransaction();
        try {
            DB::table("groupings_request")->where("id", $id)->update([
                "is_approved" => 1
            ]);
    
            $res = DB::table("groupings_request")->where("id", $id)->get();
            $data = json_decode($res[0]->data);
            $all_user = [];
            foreach ($data as $key => $value) {
                foreach ($value as $index => $user_id) {
                    array_push($all_user, $user_id);
                }
            }
    
            DB::table("notifications")->insert([
                "targets" => "Groupings Request",
                "content" => "Groupings has been approved!",
                "users" => json_encode($all_user),
                "seen_by" => json_encode([]),
                "added_by" => Auth::user()->id
            ]);
    
            $request_by_id = $res[0]->request_by;
    
            $check_groupings = DB::table("groupings")->where("user_id", $request_by_id)->get();
            if(count($check_groupings) > 0){
                DB::table("groupings")->where("group_reference", $check_groupings[0]->group_reference)->delete();
                DB::table("advisers")->where("group_reference", $check_groupings[0]->group_reference)->delete();
                DB::table("panels")->where("group_reference", $check_groupings[0]->group_reference)->delete();

                DB::table("groupings_capstone")->where("group_reference", $check_groupings[0]->group_reference)->delete();
                DB::table("groupings_capstone_comments")->where("group_reference", $check_groupings[0]->group_reference)->delete();

                DB::table("scheduler")->where("group_reference", $check_groupings[0]->group_reference)->delete();
                DB::table("messages")->where("group_reference", $check_groupings[0]->group_reference)->delete();
            }

            // insert new groupings - start
            foreach ($data->final_student_list as $key => $value) {
                DB::table("groupings")->insert([
                    "group_reference" => $reference,
                    "user_id" => $data->final_student_list[$key],
                ]);
            }

            foreach ($data->final_panel_list as $key => $value) {
                DB::table("panels")->insert([
                    "group_reference" => $reference,
                    "panel_id" => $data->final_panel_list[$key],
                ]);
            }

            foreach ($data->final_adviser_list as $key => $value) {
                DB::table("advisers")->insert([
                    "group_reference" => $reference,
                    "adviser_id" => $data->final_adviser_list[$key],
                ]);
            }
            // insert new groupings - end

            // Mailer
            $to_mail_adviser = [];
            $to_mail_panel = [];
            $to_mail_students = [];
//
            foreach ($data->final_panel_list as $key => $value) {
                $to_mail_panel[] = DB::table("users")->where("id", $value)->first();
            }
//
            foreach ($data->final_student_list as $key => $value) {
                $to_mail_students[] = DB::table("users")->where("id", $value)->first();
            }
//
            foreach ($data->final_adviser_list as $key => $value) {
                $to_mail_adviser[] = DB::table("users")->where("id", $value)->first();
            }
//
            //// dd($to_mail_adviser);
//
            $data = [
                "adviser" => $to_mail_adviser, 
                "panel" => $to_mail_panel, 
                "students" => $to_mail_students, 
                "message" => "This email is to inform you that a Capstone Groupings has been Created.",
            ];
//
            foreach ($to_mail_adviser as $key => $value) {
                Mail::to($value->email)->send(new GroupingsEmailer($data));
            }
            foreach ($to_mail_panel as $key => $value) {
                Mail::to($value->email)->send(new GroupingsEmailer($data));
            }
            foreach ($to_mail_students as $key => $value) {
                Mail::to($value->email)->send(new GroupingsEmailer($data));
            }

            DB::commit();
            return true;

            //DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            // abort(500);
            dd($th);
        }

        //return 1;
    }

    public function generateReference(){
        $length = 50;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getAllAvailableStudents(){
        $final_list = [];
        $students_temp = DB::table("users")->where("enabled", 1)->where("role", 4)->where("id", "<>", Auth::user()->id)->get();
        $students_in_group = DB::table("groupings")->select("user_id")->get();

        $list = [];
        foreach ($students_in_group as $key => $value) {
            $list[] = $value->user_id;
        }
        foreach ($students_temp as $key => $value) {
            if(!in_array($value->id, $list)){
                $final_list[] = $value;
            }
        }
        return $final_list;
    }

    public function getAllGroupmates(){
        $res = DB::table("groupings")->where("user_id", Auth::user()->id)->get();
        $list = DB::table("groupings")
            ->join("users", "users.id", "=", "groupings.user_id")
            ->where("groupings.group_reference", $res[0]->group_reference)
            ->where("groupings.user_id", "<>", Auth::user()->id)
            ->get();
            
        return $list;
    }

    public function getAllAvailablePanelsAndAdvisers(){
        $final_list = [];
        $panles_temp = DB::table("users")->where("enabled", 1)->where("role", 3)->get();
        return $panles_temp;
    }

    public function request_my_groupings(Request $request){
        $data = $request->json()->all();
        DB::table("groupings_request")->where("is_approved", 0)->where("request_by", Auth::user()->id)->update([
            "is_approved" => 2,
        ]);

        DB::table("groupings_request")->insert([
            "request_by" => Auth::user()->id,
            "data" => json_encode($data),
        ]);

        $res = DB::table("users")->select("id")->where("role", "2")->get();
        $coordinator_list = [];
        foreach ($res as $key => $value) {
            array_push($coordinator_list, $value->id);
        }

        DB::table("notifications")->insert([
            "targets" => "Groupings Request",
            "content" => "I " .Auth::user()->name. " would like to request a groupings.",
            "users" => json_encode($coordinator_list),
            "seen_by" => "[]",
            "added_by" =>  Auth::user()->id,
        ]);

        return true;
    }

    public function request_list(Request $request){
        if($request->ajax()){
            $res = DB::table("groupings_request")
                ->select("users.name", "groupings_request.*")
                ->join("users", "users.id", "groupings_request.request_by")
                ->where("groupings_request.is_approved", 0)
                ->get();

            return datatables()
                ->of($res)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= "<button class='btn btn-primary' onclick='viewRequest(\"".encrypt($row->id)."\")'>View</button>&nbsp;";
                    // $btn .= "<button class='btn btn-primary' onclick='approveRequest(\"".encrypt($row->id)."\")'>Approve</button>&nbsp;";
                    // $btn .= "<button class='btn btn-primary' onclick='rejectRequest(\"".encrypt($row->id)."\")'>Reject</button>&nbsp;";
                    return $btn;
                })
                ->addColumn('requested_by', function ($row) {
                    return $row->name;
                })
                ->rawColumns([
                    'action', 'requested_by'
                ])
            ->make(true);
        }

        return view("pages.coordinator.index");
    }

    public function edit($option){
        if(!in_array($option, [1, 2])){
            abort(404);
        } 

        $final_student_list = $this->getAllAvailableStudents();
        $final_panel_adviser_list = $this->getAllAvailablePanelsAndAdvisers();
        $final_groupmate_list = $this->getAllGroupmates();

        if($option == 1){
            return view("pages.request_groupings.add", ["student_list" => $final_student_list, "panels_and_adviser_list" => $final_panel_adviser_list]);
        } else {
            return view("pages.request_groupings.kick", ["student_list" => $final_groupmate_list, "panels_and_adviser_list" => $final_panel_adviser_list]);
        }
    }

    public function request_my_groupings_add(Request $request){
        $data = $request->json()->all();

        DB::table("groupings_request_update")->insert([
            "request_by" => Auth::user()->id,
            "data" => json_encode($data),
            "option_value" => "ADD"
        ]);

        $res = DB::table("users")->select("id")->where("role", "2")->get();
        $coordinator_list = [];
        foreach ($res as $key => $value) {
            array_push($coordinator_list, $value->id);
        }

        DB::table("notifications")->insert([
            "targets" => "Groupings Update Request",
            "content" => "I " .Auth::user()->name. " would like to request an additional member for our group.",
            "users" => json_encode($coordinator_list),
            "seen_by" => "[]",
            "added_by" =>  Auth::user()->id,
        ]);
        return true;
    }

    public function request_my_groupings_kick(Request $request){
        $data = $request->json()->all();

        DB::table("groupings_request_update")->insert([
            "request_by" => Auth::user()->id,
            "data" => json_encode($data),
            "option_value" => "KICK"
        ]);

        $res = DB::table("users")->select("id")->where("role", "2")->get();
        $coordinator_list = [];
        foreach ($res as $key => $value) {
            array_push($coordinator_list, $value->id);
        }

        DB::table("notifications")->insert([
            "targets" => "Groupings Update Request",
            "content" => "I " .Auth::user()->name. " would like to request to kick a member for our group.",
            "users" => json_encode($coordinator_list),
            "seen_by" => "[]",
            "added_by" =>  Auth::user()->id,
        ]);
        return true;
    }

    public function request_my_groupings_disband(Request $request){
        DB::table("groupings_request_update")->insert([
            "request_by" => Auth::user()->id,
            "data" => json_encode([]),
            "option_value" => "DISBAND"
        ]);

        $res = DB::table("users")->select("id")->where("role", "2")->get();
        $coordinator_list = [];
        foreach ($res as $key => $value) {
            array_push($coordinator_list, $value->id);
        }

        DB::table("notifications")->insert([
            "targets" => "Groupings Disband Request",
            "content" => "I " .Auth::user()->name. " would like to request to disband our group.",
            "users" => json_encode($coordinator_list),
            "seen_by" => "[]",
            "added_by" =>  Auth::user()->id,
        ]);
        return true;
    }

    public function request_edit_list(Request $request){
        if($request->ajax()){
            $res = DB::table("groupings_request_update")
                ->select("users.name", "groupings_request_update.*")
                ->join("users", "users.id", "groupings_request_update.request_by")
                ->where("groupings_request_update.is_approved", 0)
                ->get();

            return datatables()
                ->of($res)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= "<button class='btn btn-primary' onclick='viewRequestEdit(\"".encrypt($row->id)."\")'>View</button>&nbsp;";
                    return $btn;
                })
                ->addColumn('requested_by', function ($row) {
                    return $row->name;
                })
                ->addColumn('purpose', function ($row) {
                    return $row->option_value;
                })
                ->rawColumns([
                    'action', 'requested_by', 'purpose'
                ])
            ->make(true);
        }

        return view("pages.coordinator.index");
    }

    public function get_request_edit_info($encrypted_id){
        $id = decrypt($encrypted_id);
        $res = DB::table("groupings_request_update")->where("id", $id)->get();

        $messages = "";
        $buttons = "";

        if(count($res) == 0){
            abort(500);
        }

        if($res[0]->option_value == "ADD"){
            $data = json_decode($res[0]->data);
            $tmp = DB::table("users")->select("name")->wherein("id", $data->final_student_list)->get();
            // dd($tmp);

            $messages .= "The group wants to add ";

            $index = 1;
            foreach ($tmp as $key => $value) {
                $messages .= "$value->name ";
                if($index < count($tmp)){
                    $messages .= ", ";
                }
            }

            $messages .= "as a member of their group.";
            $buttons .= "
                <button class='btn btn-primary' onclick='approveEdit(\"".encrypt($res[0]->id)."\")'>Approve</button>
                <button class='btn btn-danger' onclick='declineEdit(\"".encrypt($res[0]->id)."\")'>Decline</button>
            ";
        } else if($res[0]->option_value == "KICK"){
            $data = json_decode($res[0]->data);
            $tmp = DB::table("users")->select("name")->wherein("id", $data->final_student_list)->get();
            // dd($tmp);

            $messages .= "The group wants to kick ";

            $index = 1;
            foreach ($tmp as $key => $value) {
                $messages .= "$value->name ";
                if($index < count($tmp)){
                    $messages .= ", ";
                }
            }

            $messages .= "from their group.";
            $buttons .= "
                <button class='btn btn-primary' onclick='approveEdit(\"".encrypt($res[0]->id)."\")'>Approve</button>
                <button class='btn btn-danger' onclick='declineEdit(\"".encrypt($res[0]->id)."\")'>Decline</button>
            ";
        } else {
            $messages .= "We would like to disband our group.";
            $buttons .= "
                <button class='btn btn-primary' onclick='approveEdit(\"".encrypt($res[0]->id)."\")'>Approve</button>
                <button class='btn btn-danger' onclick='declineEdit(\"".encrypt($res[0]->id)."\")'>Decline</button>
            ";
        }

        return json_encode(["messages" => $messages, "buttons" => $buttons]);
    }

    public function edit_reject_request_info($encrypted_id){
        $id = decrypt($encrypted_id);

        $res = DB::table("groupings_request_update")->where("id", $id)->get();
        $users_list = [];

        DB::table("groupings_request_update")->where("id", $id)->update([
            "is_approved" => 2
        ]);

        if($res[0]->option_value != "DISBAND"){
            foreach (json_decode($res[0]->data)->final_student_list as $key => $value) {
                array_push($users_list, $value);
            }
        }

        DB::table("notifications")->insert([
            "targets" => "Groupings Request",
            "content" => "Groupings has been Rejected!",
            "users" => json_encode($users_list),
            "seen_by" => json_encode([]),
            "added_by" => Auth::user()->id
        ]);

        return 1;
    }

    public function edit_approve_request_info($encrypted_id){
        $id = decrypt($encrypted_id);

        $res = DB::table("groupings_request_update")->where("id", $id)->get();
        $users_list = [];

        DB::table("groupings_request_update")->where("id", $id)->update([
            "is_approved" => 1
        ]);

        if($res[0]->option_value != "DISBAND"){
            foreach (json_decode($res[0]->data)->final_student_list as $key => $value) {
                array_push($users_list, $value);
            }
        }

        DB::table("notifications")->insert([
            "targets" => "Groupings Request",
            "content" => "Request has been Approved!",
            "users" => json_encode($users_list),
            "seen_by" => json_encode([]),
            "added_by" => Auth::user()->id
        ]);

        if($res[0]->option_value == "ADD"){
            $requester_by_id = DB::table("groupings_request_update")->select("request_by")->where("id", $id)->get();
            $requested_by_group_ref = DB::table("groupings")->select("group_reference")->where("user_id", $requester_by_id[0]->request_by)->get();

            foreach (json_decode($res[0]->data)->final_student_list as $key => $value) {
                DB::table("groupings")->where("user_id", $value)->delete();
                DB::table("groupings")->insert([
                    "group_reference" => $requested_by_group_ref[0]->group_reference,
                    "user_id" => $value,
                ]);
            }
        } else if($res[0]->option_value == "KICK"){
            $requester_by_id = DB::table("groupings_request_update")->select("request_by")->where("id", $id)->get();
            $requested_by_group_ref = DB::table("groupings")->select("group_reference")->where("user_id", $requester_by_id[0]->request_by)->get();

            foreach (json_decode($res[0]->data)->final_student_list as $key => $value) {
                DB::table("groupings")->where("user_id", $value)->delete();
            }
        } else {
            $requester_by_id = DB::table("groupings_request_update")->select("request_by")->where("id", $id)->get();
            $requested_by_group_ref = DB::table("groupings")->select("group_reference")->where("user_id", $requester_by_id[0]->request_by)->get();

            DB::table("groupings")->where("group_reference", $requested_by_group_ref[0]->group_reference)->delete();
            DB::table("advisers")->where("group_reference", $requested_by_group_ref[0]->group_reference)->delete();
            DB::table("panels")->where("group_reference", $requested_by_group_ref[0]->group_reference)->delete();

            DB::table("groupings_capstone")->where("group_reference", $requested_by_group_ref[0]->group_reference)->delete();
            DB::table("groupings_capstone_comments")->where("group_reference", $requested_by_group_ref[0]->group_reference)->delete();

            DB::table("scheduler")->where("group_reference", $requested_by_group_ref[0]->group_reference)->delete();
            DB::table("messages")->where("group_reference", $requested_by_group_ref[0]->group_reference)->delete();

        }

        DB::table("notifications")->insert([
            "targets" => "Groupings Request",
            "content" => "Groupings has been Approved!",
            "users" => json_encode($users_list),
            "seen_by" => json_encode([]),
            "added_by" => Auth::user()->id
        ]);



        return 1;
    }

    public function getAdvisersAndPanel($exception){
        // dd($exception);
        $res = DB::table("users")->select("id", "name")->where("id", "<>", $exception)->where("role", 3)->get();
        return $res;
    }
}
