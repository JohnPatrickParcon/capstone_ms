<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\GroupingsEmailer;
use Mail;
use DB;
use Auth;

class GroupingsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function checkRole(){
        if(Auth::user()->role != 2){
            abort(404);
        }
    }

    public function index(Request $request){
        $this->checkRole();
        if($request->ajax()){
            $data = [];
            $distinct_group_code = DB::table("groupings")->distinct("group_reference")->get();
            foreach ($distinct_group_code as $key => $value) {
                // get all students in the group.
                $student_list = DB::table("groupings")
                    ->select("users.name", "groupings.*")
                    ->join("users", "users.id", "groupings.user_id")
                    ->where("group_reference", "like", $value->group_reference)
                    ->get();

                // get adviser/s 
                $adviser_list = DB::table("advisers")
                    ->select("users.name", "advisers.*")
                    ->join("users", "users.id", "advisers.adviser_id")
                    ->where("group_reference", "like", $value->group_reference)
                    ->get();

                // get panel/s 
                $panel_list = DB::table("panels")
                    ->select("users.name", "panels.*")
                    ->join("users", "users.id", "panels.panel_id")
                    ->where("group_reference", "like", $value->group_reference)
                    ->get();

                $data[$value->group_reference] = ["students" => $student_list, "advisers" => $adviser_list, "panels" => $panel_list];
            }

            return datatables()
                ->of($data)
                ->addIndexColumn()
                ->addColumn('names', function ($row) {
                    $names = "";
                    foreach ($row["students"] as $key => $value) {
                        if($names != ""){
                            $names .= ", ".$value->name;
                        } else {
                            $names .= $value->name;
                        }
                    }
                    return $names;
                })
                ->addColumn('adviser', function ($row) {
                    $adviser = $row["advisers"][0]->name;
                    return $adviser;
                })
                ->addColumn('panels', function ($row) {
                    $panels = "";
                    foreach ($row["panels"] as $key => $value) {
                        if($panels != ""){
                            $panels .= ", ".$value->name;
                        } else {
                            $panels .= $value->name;
                        }
                    }
                    return $panels ;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    // $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleEditGroup(\''.$row["advisers"][0]->group_reference.'\')"><i class="mdi mdi-file-edit-outline" title="Edit Group"></i></button>&nbsp;&nbsp;';
                    $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleDeleteGroup(\''.$row["advisers"][0]->group_reference.'\')"><i class="mdi mdi-trash-can-outline" title="Delete Group"></i></button>&nbsp;&nbsp;';
                    //$btn .= '<button class="btn" style="background: green; color: white;" onclick="handleGradeGroup(\''.$row["advisers"][0]->group_reference.'\')"><i class="mdi mdi-book" title="Create Grading Form"></i></button>&nbsp;&nbsp;';
                    return $btn;
                })
                ->rawColumns([
                    'action',
                    'names',
                    'adviser',
                    'panels'
                ])
            ->make(true);
        }

        $final_student_list = $this->getAllAvailableStudents();
        $final_panel_adviser_list = $this->getAllAvailablePanelsAndAdvisers();

        return view('pages.coordinator.groupings', ["student_list" => $final_student_list, "panels_and_adviser_list" => $final_panel_adviser_list]);
    }

    public function getAllAvailableStudents(){
        $final_list = [];
        $students_temp = DB::table("users")->where("enabled", 1)->where("role", 4)->get();
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

    public function getAllAvailablePanelsAndAdvisers(){
        $final_list = [];
        $panles_temp = DB::table("users")->where("enabled", 1)->where("role", 3)->get();
        return $panles_temp;
    }

    public function save(Request $request){
        $data = $request->json()->all();
        $reference = $this->generateReference();

        // dd($data);

        DB::beginTransaction();
        try {
            foreach ($data["final_student_list"] as $key => $value) {
                DB::table("groupings")->insert([
                    "group_reference" => $reference,
                    "user_id" => $data["final_student_list"][$key],
                ]);
            }

            foreach ($data["final_panel_list"] as $key => $value) {
                DB::table("panels")->insert([
                    "group_reference" => $reference,
                    "panel_id" => $data["final_panel_list"][$key],
                ]);
            }

            foreach ($data["final_adviser_list"] as $key => $value) {
                DB::table("advisers")->insert([
                    "group_reference" => $reference,
                    "adviser_id" => $data["final_adviser_list"][$key],
                ]);
            }

            // Mailer
            $to_mail_adviser = [];
            $to_mail_panel = [];
            $to_mail_students = [];
//
            foreach ($data["final_panel_list"] as $key => $value) {
                $to_mail_panel[] = DB::table("users")->where("id", $data["final_panel_list"][$key])->first();
            }
//
            foreach ($data["final_student_list"] as $key => $value) {
                $to_mail_students[] = DB::table("users")->where("id", $data["final_student_list"][$key])->first();
            }
//
            foreach ($data["final_adviser_list"] as $key => $value) {
                $to_mail_adviser[] = DB::table("users")->where("id", $data["final_adviser_list"][$key])->first();
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
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return false;
        }
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

    public function delete($reference){

        $students = DB::table("groupings")
            ->select("users.*")
            ->join("users", "users.id", "groupings.user_id")
            ->where("group_reference", "like", $reference)
            ->get();
        $advisers = DB::table("advisers")
            ->select("users.*")
            ->join("users", "users.id", "advisers.adviser_id")
            ->where("group_reference", "like", $reference)
            ->get();
        $panels = DB::table("panels")
            ->select("users.*")
            ->join("users", "users.id", "panels.panel_id")
            ->where("group_reference", "like", $reference)
            ->get();

        $data = [
            "adviser" => $advisers, 
            "panel" => $panels, 
            "students" => $students, 
            "message" => "This email is to inform you that a Capstone Groupings has been Removed/Deleted.",
        ];

        foreach ($advisers as $key => $value) {
            Mail::to($value->email)->send(new GroupingsEmailer($data));
        }
        foreach ($panels as $key => $value) {
            Mail::to($value->email)->send(new GroupingsEmailer($data));
        }
        foreach ($students as $key => $value) {
            Mail::to($value->email)->send(new GroupingsEmailer($data));
        }


        DB::table("groupings")->where("group_reference", "like", $reference)->delete();
        DB::table("advisers")->where("group_reference", "like", $reference)->delete();
        DB::table("panels")->where("group_reference", "like", $reference)->delete();
        return 1;
    }
}
