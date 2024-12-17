<?php

namespace App\Http\Controllers\StudentPageControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Storage;

class CapstoneController extends Controller
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
        
        $groupings = DB::table("groupings")->where("user_id", Auth::user()->id)->get();
        $has_groupings = count($groupings) > 0 ? true : false;
        if(!$has_groupings){
            // abort(404);
            return view("pages.errors.no_groupings_error");
        }

        $capstone = DB::table("groupings_capstone")->where("group_reference", $groupings[0]->group_reference)->get();
    
        $is_published = false;
        $asd = DB::table("published_capstone")->where("group_reference", $groupings[0]->group_reference)->where("enabled", 1)->get();
        if(count($asd) > 0){
            $is_published = true;
        }

        return view('pages.student.capstone', ["has_groupings" => $has_groupings, "capstone_info" => $capstone, "is_published" => $is_published]);
    }

    public function updateComments($type, $id){
        if($type == 1){
            // Update to For Revision
            DB::table("groupings_capstone_comments")->where("id", $id)->update([
                "status" => 1
            ]);
        } else if($type == 2){
            // Update into Revised
            DB::table("groupings_capstone_comments")->where("id", $id)->update([
                "status" => 2
            ]);
        } else if($type == 3){
            // Update into Revised
            DB::table("groupings_capstone_comments")->where("id", $id)->update([
                "status" => 3
            ]);
        } else if ($type == 4) {
            // Update into Deleted
            DB::table("groupings_capstone_comments")->where("id", $id)->update([
                "status" => 4
            ]);
        }

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

                // dd($res);

            return datatables()
                ->of($res)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    
                    $text = "";
                    if($data->status != 1){
                        $text .= "disabled";
                    }
                    $btn = '';
                    $btn .= '<button '. $text .' class="btn" style="background: green; color: white;" onclick="handleUpdateComment(\''.$data->id.'\')"><i class="mdi mdi-check" title="Update"></i></button>&nbsp;&nbsp;';
                    // $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleScoreCapstonePanel(\''.$data["group_reference"].'\')"><i class="mdi mdi-pen" title="Score Capstone"></i></button>&nbsp;&nbsp;';
                    return $btn;
                })
                ->rawColumns([
                    'action',
                ])
            ->make(true);
        }
    }

    public function uploadCapstone(Request $request){
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $res = DB::table("groupings")->where("user_id", Auth::user()->id)->get();
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $file_name = $res[0]->group_reference . "." . $extension;
        $main_path = public_path('assets/capstone');
        
        //dd($request);
        if(!is_dir("assets")){
            mkdir("assets/", 0777);
        }
        if(!is_dir("assets/capstone")){
            mkdir("assets/capstone", 0777);
        }

        DB::beginTransaction();
        try {
            DB::table("groupings_capstone")->insert([
                "group_reference" => $res[0]->group_reference,
                "title" => $request->get("title"),
                "abstract" => $request->get("abstract"),
                "file" => "assets/capstone/".$file_name,
            ]);
            $file->move($main_path, $file_name);
            DB::commit();
            //$request->file('file')->store('assets/capstone');
            return back()->with('success', 1);
        } catch (\Throwable $th) {
            DB::rollback();
            //dd($th);
            return back()->with('error', 0);
        }
    }

    public function removeMyCapstone($reference){
        $res = DB::table("groupings_capstone")->where("group_reference", $reference)->get();
        unlink($res[0]->file);
        DB::table("groupings_capstone")->where("group_reference", $reference)->delete();
        return 1;
    }

    public function updateCapstoneDesc(Request $request){
        DB::table("groupings_capstone")->where("group_reference", $request->get("reference"))->update([
            "title" => $request->get("title"),
            "abstract" => $request->get("abstract")
        ]);

        return back()->with('success', 1);
    }

    public function updateCapstoneFile(Request $request){
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $res = DB::table("groupings_capstone")->where("group_reference", $request->get("reference"))->get();
        unlink($res[0]->file);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $file_name = $res[0]->group_reference . "." . $extension;
        $main_path = public_path('assets/capstone');

        if(!is_dir("assets")){
            mkdir("assets/", 0777);
        }
        if(!is_dir("assets/capstone")){
            mkdir("assets/capstone", 0777);
        }

        DB::beginTransaction();
        try {
            DB::table("groupings_capstone")->where("group_reference", $request->get("reference"))->update([
                "file" => "assets/capstone/".$file_name,
            ]);
            $file->move($main_path, $file_name);
            DB::commit();
            return back()->with('success', 1);
        } catch (\Throwable $th) {
            DB::rollback();
            //dd($th);
            return back()->with('error', 0);
        }
    }
}
