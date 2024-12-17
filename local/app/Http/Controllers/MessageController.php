<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class MessageController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function checkIfStudent(){
        $user_role = Auth::user()->role;
        if($user_role == 4){
            return true;
        } else {
            return false;
        }
    }

    public function index(Request $request){
        $messages = [];
        if($this->checkIfStudent()){
            $myGroup = DB::table("groupings")->where("user_id", "like", Auth::user()->id)->get();
            if(count($myGroup) > 0){
                return redirect("/messages" . "/" . $myGroup[0]->group_reference);
            } else {
                // abort(404);
                return view("pages.errors.no_groupings_error");
            }
        }


        $user_role = Auth::user()->role;
        $temp_messages = [];

        if($user_role == 2){
            $temp_messages = DB::table("messages")
                ->select("group_reference")
                ->distinct()
                ->get();
        } else {
            $groupings_list = [];
            $adviser_groupings = DB::table("advisers")->where("adviser_id", "like", Auth::user()->id)->get();
            foreach ($adviser_groupings as $key => $value) { $groupings_list[] = $value->group_reference; }
            $panel_groupings =  DB::table("panels")->where("panel_id", "like", Auth::user()->id)->get();
            foreach ($panel_groupings as $key => $value) { $groupings_list[] = $value->group_reference; }

            $temp_messages = DB::table("messages")
                ->select("group_reference")
                ->whereIn("group_reference", $groupings_list)
                ->distinct()
                ->get();
        }

        foreach ($temp_messages as $key => $value) {
            $name = "";
            $temp = DB::table("groupings")
                ->select("users.name")
                ->join("users", "users.id", "groupings.user_id")
                ->where("groupings.group_reference", "like", $value->group_reference)
                ->get();
            foreach ($temp as $key2 => $value2) {
                if($name != ""){
                    $name .= ", ";
                }
                $name .= $value2->name;
            }

            $messages[] = ["users" => $name, "reference" => $value->group_reference];
        }


        if($request->ajax()){
            return datatables()
                ->of($messages)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= "<button class='btn btn-primary' onclick='viewMessages(\"".$row['reference']."\")'>View</button>&nbsp;";
                    return $btn;
                })
                ->addColumn('students_name', function ($row) {
                    return $row["users"];
                })
                ->rawColumns([
                    'action', 'students_name'
                ])
            ->make(true);
        }

        return view("pages.messages.index");
    }

    public function view($group_reference){
        $all_users = [];

        $all_users = $this->getAllStudents($group_reference, $all_users);
        $all_users = $this->getAllAdvisers($group_reference, $all_users);
        $all_users = $this->getAllPanels($group_reference, $all_users);
        $messages = $this->getMessages($group_reference);

        $user_role = Auth::user()->role;
        $viewOnly = false;
        if($user_role == 2){
            $viewOnly = true;
        }
          

        return view("pages.messages.viewMessages", ["users" => $all_users, "messages" => $messages, "group_reference" => $group_reference, "viewOnly" => $viewOnly]);
        // dd(json_decode($data));
    }

    public function getMessages($group_reference){
        $messages = DB::table("messages")
            ->select("users.name as sender_name", "messages.*")
            ->join("users", "users.id", "=", "messages.sender")
            ->where("messages.group_reference", $group_reference)
            ->orderBy("messages.id", "asc")
            ->orderBy("messages.time", "asc")
            ->limit(1000)->get();

        return $messages;
    }

    public function getAllStudents($group_reference, $all_users){
        $temp = DB::table("groupings")
        ->select("users.id", "users.name", "users.email", "users.role")
        ->join("users", "users.id", "=", "groupings.user_id")
        ->where("group_reference", "like", $group_reference)
        ->get();

        foreach ($temp as $key => $value) {
            $all_users[] = $value;
        }

        return $all_users;
    }

    public function getAllAdvisers($group_reference, $all_users){
        $temp = DB::table("advisers")
        ->select("users.id", "users.name", "users.email", "users.role")
        ->join("users", "users.id", "=", "advisers.adviser_id")
        ->where("group_reference", "like", $group_reference)
        ->get();

        foreach ($temp as $key => $value) {
            $all_users[] = $value;
        }

        return $all_users;
    }

    public function getAllPanels($group_reference, $all_users){
        $temp = DB::table("panels")
        ->select("users.id", "users.name", "users.email", "users.role")
        ->join("users", "users.id", "=", "panels.panel_id")
        ->where("group_reference", "like", $group_reference)
        ->get();

        foreach ($temp as $key => $value) {
            $all_users[] = $value;
        }

        return $all_users;
    }

    public function send(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data = $request->json()->all();
        $current_time = date('H:i:s');
        $current_date = date('Y-m-d');
        DB::beginTransaction();
        try { 
            DB::table("messages")->insert([
                "message" => $data["message"],
                "sender" => Auth::user()->id,
                "group_reference" => $data["group_reference"],
                "time" => $current_time,
                "created_at" => $current_date,
            ]);
            DB::commit();
            return json_encode(["result" => "Message sent", "code" => 1]);
        } catch (\Throwable $th) {
            DB::rollback();
            return json_encode(["result" => "Something went wrong", "code" => 0]);
        }
    }

    public function getMyMessages($group_reference){
        return $this->getMessages($group_reference);
    }
}
