<?php

namespace App\Http\Controllers\AdviserPageControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class DashboardController extends Controller
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
        return view('pages.adviser.index');
    }

    public function adviserTable(Request $request){
        if($request->ajax()){
            $final_data = [];
            $res = DB::table("advisers")
                ->where("adviser_id", Auth::user()->id)
                ->get();
            foreach ($res as $key => $value) {
                $temp_students = DB::table("groupings")
                    ->select("users.name")
                    ->join("users", "users.id", "=", "groupings.user_id")
                    ->where("group_reference", $value->group_reference)
                    ->get();
                $temp_panels = DB::table("panels")
                    ->select("users.name")
                    ->join("users", "users.id", "=", "panels.panel_id")
                    ->where("group_reference", $value->group_reference)
                    ->get();
                $temp_status = DB::table("groupings_capstone")
                    ->select("status")
                    ->where("group_reference", $value->group_reference)
                    ->get();
                $temp_schedule = DB::table("groupings_capstone")
                    ->select("defense_schedule")
                    ->where("group_reference", $value->group_reference)
                    ->get();
                $final_data[] = ["students" => $temp_students, "panels" => $temp_panels, "status" => $temp_status, "schedule" => $temp_schedule, "group_reference" => $value->group_reference];
            }
                
            return datatables()
                ->of($final_data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '';
                    $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleViewCapstone(\''.$data["group_reference"].'\')">View</button>&nbsp;&nbsp;';
                    // $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleScoreCapstoneAdviser(\''.$data["group_reference"].'\')"><i class="mdi mdi-pen" title="Score Capstone"></i></button>&nbsp;&nbsp;';
                    //$btn .= "<button class='btn btn-primary' onclick='scoreCapstone(\"".$data["group_reference"]."\")'>Score</button>&nbsp;";
                    // $res = DB::table("capstone_grading_forms")->where("group_reference", $data["group_reference"])->get();
                    // if(count($res) > 0){
                    //     $my_data = json_decode($res[0]->responses);
                    //     if($my_data != NULL){
                    //         if(!array_key_exists(Auth::user()->id, $my_data)){
                    //             $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleGradingForm(\''.$data["group_reference"].'\')">Grading Form</button>&nbsp;&nbsp;';
                    //         }
                    //     } else {
                    //         $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleGradingForm(\''.$data["group_reference"].'\')">Grading Form</button>&nbsp;&nbsp;';
                    //     }
                    // }
                    return $btn;
                })
                ->addColumn('stundens_name', function ($data) {
                    $name = "";
                    foreach ($data["students"] as $key => $value) {
                        if($name != ""){
                            $name .= ", " . $value->name;
                        } else {
                            $name .= $value->name;
                        }
                    }
                    return $name;
                })
                ->addColumn('panels_name', function ($data) {
                    $name = "";
                    foreach ($data["panels"] as $key => $value) {
                        if($name != ""){
                            $name .= ", " . $value->name;
                        } else {
                            $name .= $value->name;
                        }
                    }
                    return $name;
                })
                ->addColumn('status', function ($data) {
                    $status = "";
                    if(count($data["status"]) == 0){
                        $status = "No Status Yet";
                    } else {
                        $status = $data["status"][0]->status;
                    }
                    return $status;
                })
                ->addColumn('schedule', function ($data) {
                    $schedule = "";
                    if(count($data["schedule"]) == 0){
                        $schedule = "No Schedule Yet";
                    } else {
                        $schedule = "";
                        if($data["schedule"][0]->defense_schedule == NULL){
                            $schedule = "No Schedule Yet";
                        } else {
                            $schedule = $data["schedule"][0]->defense_schedule;
                        }
                    }
                    return $schedule;
                })
                ->rawColumns([
                    'action',
                    'name',
                    'panels_name',
                    'status',
                    'schedule'
                ])
            ->make(true);
        }
    }

    public function panelTable(Request $request){
        if($request->ajax()){
            $final_data = [];
            $res = DB::table("panels")
                ->where("panel_id", Auth::user()->id)
                ->get();
            foreach ($res as $key => $value) {
                $temp_students = DB::table("groupings")
                    ->select("users.name")
                    ->join("users", "users.id", "=", "groupings.user_id")
                    ->where("group_reference", $value->group_reference)
                    ->get();
                $temp_panels = DB::table("advisers")
                    ->select("users.name")
                    ->join("users", "users.id", "=", "advisers.adviser_id")
                    ->where("group_reference", $value->group_reference)
                    ->get();
                $temp_status = DB::table("groupings_capstone")
                    ->select("status")
                    ->where("group_reference", $value->group_reference)
                    ->get();
                $temp_schedule = DB::table("groupings_capstone")
                    ->select("defense_schedule")
                    ->where("group_reference", $value->group_reference)
                    ->get();
                $final_data[] = ["students" => $temp_students, "panels" => $temp_panels, "status" => $temp_status, "schedule" => $temp_schedule, "group_reference" => $value->group_reference];
            }

            return datatables()
                ->of($final_data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '';
                    $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleViewCapstone(\''.$data["group_reference"].'\')">View</button>&nbsp;&nbsp;';
                    // $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleScoreCapstonePanel(\''.$data["group_reference"].'\')"><i class="mdi mdi-pen" title="Score Capstone"></i></button>&nbsp;&nbsp;';
                    //$btn .= "<button class='btn btn-primary' onclick='scoreCapstone(\"".$data["group_reference"]."\")'>Score</button>&nbsp;";
                    return $btn;
                })
                ->addColumn('stundens_name', function ($data) {
                    $name = "";
                    foreach ($data["students"] as $key => $value) {
                        if($name != ""){
                            $name .= ", " . $value->name;
                        } else {
                            $name .= $value->name;
                        }
                    }
                    return $name;
                })
                ->addColumn('adviser_name', function ($data) {
                    $name = "";
                    foreach ($data["panels"] as $key => $value) {
                        if($name != ""){
                            $name .= ", " . $value->name;
                        } else {
                            $name .= $value->name;
                        }
                    }
                    return $name;
                })
                ->addColumn('status', function ($data) {
                    $status = "";
                    if(count($data["status"]) == 0){
                        $status = "No Status Yet";
                    } else {
                        $status = $data["status"][0]->status;
                    }
                    return $status;
                })
                ->addColumn('schedule', function ($data) {
                    $schedule = "";
                    if(count($data["schedule"]) == 0){
                        $schedule = "No Schedule Yet";
                    } else {
                        $schedule = "";
                        if($data["schedule"][0]->defense_schedule == NULL){
                            $schedule = "No Schedule Yet";
                        } else {
                            $schedule = $data["schedule"][0]->defense_schedule;
                        }
                    }
                    return $schedule;
                })
                ->rawColumns([
                    'action',
                    'name',
                    'adviser_name',
                    'status',
                    'schedule'
                ])
            ->make(true);
        }
    }

    public function getStudentInfo($reference){
        $res = DB::table("groupings")
            ->select("users.name", "groupings.*")
            ->join("users", "users.id", "groupings.user_id")
            ->where("group_reference", $reference)
            ->get();

        return $res;
    }   

    public function saveScoreAdviser($reference, Request $request){
        $data = $request->json()->all();
        DB::table("advisers")
            ->where("group_reference", $reference)
            ->where("adviser_id", Auth::user()->id)
            ->update([
                "score" => json_encode($data)
            ]);
        return 1;
    }

    public function saveScorePanel($reference, Request $request){
        $data = $request->json()->all();
        DB::table("panels")
            ->where("group_reference", $reference)
            ->where("panel_id", Auth::user()->id)
            ->update([
                "score" => json_encode($data)
            ]);
        return 1;
    }

    public function viewCapstone($reference){
        // $this->checkRole();
        // $final_grades = [];
        $result = DB::table("groupings_capstone")->where("group_reference", "like", $reference)->get();
        if(count($result) == 0){
            return view('pages.errors.no_capstone');
        }
        
        $is_finalized = true;

        $grade_temp = [];
        //$temp = DB::table("advisers")->where("group_reference", "like", $reference)->get();
        //foreach ($temp as $key => $value) {
        //    $user_info = [];
        //    $name = DB::table("users")->where("id", $value->adviser_id)->first()->name . " " . "(Adviser)";
        //    $student_grades = json_decode($value->score);
//
        //    if($student_grades != null){
        //        foreach ($student_grades as $key2 => $value2) {
        //            $student_grades[$key2]->name = DB::table("users")->where("id", "like", $value2->id)->first()->name;
        //        }
        //        $grade_temp[$value->adviser_id] = ["name" => $name, "grades" => $student_grades];
        //    } else {
        //        $grade_temp[$value->adviser_id] = ["name" => $name, "grades" => []];
        //    }
//
        //    // if($value->is_finalized == 0){
        //    //     $is_finalized = false;
        //    // }
        //}
//
        //$temp = DB::table("panels")->where("group_reference", "like", $reference)->get();
        //foreach ($temp as $key => $value) {
        //    $user_info = [];
        //    $name = DB::table("users")->where("id", $value->panel_id)->first()->name . " " . "(Panel)";
        //    $student_grades = json_decode($value->score);
//
        //    if($student_grades != null){
        //        foreach ($student_grades as $key2 => $value2) {
        //            $student_grades[$key2]->name = DB::table("users")->where("id", "like", $value2->id)->first()->name;
        //        }
        //        $grade_temp[$value->panel_id] = ["name" => $name, "grades" => $student_grades];
        //    } else {
        //        $grade_temp[$value->panel_id] = ["name" => $name, "grades" => []];
        //    }
//
        //    // if($value->is_finalized == 0){
        //    //     $is_finalized = false;
        //    // }
        //}
//
        //$grading_form_data = DB::table("capstone_grading_forms")->where("group_reference", $reference)->get();
        //// dd($grading_form_data);
        //if(count($grading_form_data) > 0){
        //    if($grading_form_data[0]->approved != 1){
        //        $is_finalized = false;
        //    }
        //}


        $res = DB::table("published_capstone")->where("group_reference", "like", $reference)->get();
        $is_published = count($res) > 0 ? true : false;


        return view('pages.adviser.viewCapstone', ["capstone_info" => $result, "role" => Auth::user()->role, "grades" => $grade_temp, "is_finalized" => $is_finalized, "is_published" => $is_published]);
    }

    public function saveCapstone(Request $request){
        $data = $request->json()->all();
        DB::table("groupings_capstone_comments")->insert([
            "group_reference" => $data["reference"],
            "comments" => $data["comments"],
            "status" => 1,
            "added_by" => Auth::user()->id,
        ]);
        return 1;
    }

    public function getComments($reference, Request $request){
        if($request->ajax()){
            $res = DB::table("groupings_capstone_comments")
                ->select("users.name", "groupings_capstone_comments.*", "lib_comment_status.status_name")
                ->join("users", "users.id", "=", "groupings_capstone_comments.added_by")
                ->join("lib_comment_status", "lib_comment_status.id", "=", "groupings_capstone_comments.status")
                ->where("group_reference", $reference)
                ->where("groupings_capstone_comments.status", "<>", 4)
                ->orderBy("groupings_capstone_comments.id", "desc")
                ->get();

            return datatables()
                ->of($res)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {

                    $text1 = $data->status == 2 ? '' : 'disabled';
                    $text2 = $data->status == 3 ? 'disabled' : '';
                    $btn = '';
                    $btn .= '<button '. $text1 . ' class="btn" style="background: green; color: white;" onclick="handleUpdateComment(\''.$data->id.'\', \'3\')"><i class="mdi mdi-check" title="Accept"></i></button>&nbsp;&nbsp;';
                    $btn .= '<button '. $text1 . ' class="btn" style="background: green; color: white;" onclick="handleUpdateComment(\''.$data->id.'\', \'1\')"><i class="mdi mdi-reload" title="Revise"></i></button>&nbsp;&nbsp;';
                    $btn .= '<button '. $text2 . ' class="btn" style="background: green; color: white;" onclick="handleUpdateComment(\''.$data->id.'\', \'4\')"><i class="mdi mdi-trash-can" title="Delete"></i></button>&nbsp;&nbsp;';
                    return $btn;
                })
                ->rawColumns([
                    'action',
                ])
            ->make(true);
        }
    }

    public function finalizedGrades(Request $request){
        $data = $request->json()->all();
        $reference = $data["reference"];
        // dd($data["reference"]);

        DB::beginTransaction();
        try {
            DB::table("advisers")->where("group_reference", "like", $reference)->update([
                "is_finalized" => 1
            ]);
            DB::table("panels")->where("group_reference", "like", $reference)->update([
                "is_finalized" => 1
            ]);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollback();
            abort(500);
        }
    }

    public function checkGrades($ref){
        // dd($ref);
        // $guard = true;

        // $res1 = DB::table("advisers")->where("group_reference", $ref)->get();
        // $res2 = DB::table("panels")->where("group_reference", $ref)->get();

        // foreach ($res1 as $key => $value) {
        //     if($value->score == NULL){
        //         $guard = false;
        //     }
        // }

        // foreach ($res2 as $key => $value) {
        //     if($value->score == NULL){
        //         $guard = false;
        //     }
        // }

        // return $guard;

        $guard = true;

        $res1 = DB::table("capstone_grading_forms")->where("group_reference", $ref)->get();
        if(count($res1) > 0){
            $responses = json_decode($res1[0]->responses);
            //dd($responses);
        }

        return $guard;
    }

    public function publishcapstone($reference, Request $request){
        // dd($reference, $request);
        $data = $request->json()->all();
        $capstone_info = DB::table("groupings_capstone")->where("group_reference", $reference)->first();
        $filePath = $capstone_info->file;
        $new_file_path = $filePath;

        if (Storage::exists($filePath)) {
            $destinationPath = 'assets/published_capstone/'.$reference.'.pdf';

            if (!is_dir('assets/published_capstone')) {
                mkdir("assets/published_capstone", 0755, true);
            }
        
            if (Storage::copy($filePath, $destinationPath)) {
                $new_file_path = $destinationPath;
            }
        }

        DB::beginTransaction();
        try {
            DB::table("published_capstone")->insert([
                "group_reference" => $reference,
                "title" => $capstone_info->title,
                "abstract" => $data["abstract"],
                "file" => $filePath,
                "enabled" => 1,
                "created_at" => date('Y-m-d')
            ]);
            DB::commit();
            return 1;
        } catch (\Throwable $th) {
            DB::rollback();
            //dd($th);
            abort(500);
        }
    }
}
