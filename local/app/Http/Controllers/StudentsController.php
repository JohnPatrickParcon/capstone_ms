<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class StudentsController extends Controller
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
            $users = DB::table("users")->where("role", 4)->get();
            return datatables()
                ->of($users)
                ->addIndexColumn()
                ->addColumn('status', function ($user) {
                    $stat = '';
                    $stat = $user->enabled == 1 ? "Enabled" : "Disabled"; 
                    return $stat;
                })
                ->addColumn('action', function ($user) {
                    $btn = '';
                    if($user->enabled == 1){
                        $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleDisableAccount(\''.encrypt($user->id).'\')"><i class="mdi mdi-block-helper" title="Disable account"></i></button>&nbsp;&nbsp;';
                    } else {
                        $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleEnableAccount(\''.encrypt($user->id).'\')"><i class="mdi mdi-key-variant" title="Enable account"></i></button>&nbsp;&nbsp;';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'action',
                    'status'
                ])
            ->make(true);
        }
        return view('pages.coordinator.students');
    }

    public function updateEnable($id){
        $id = decrypt($id);
        DB::table("users")->where("id", $id)->update(["enabled" => 1]);
        return 1;
    }

    public function updateDisable($id){
        $id = decrypt($id);
        DB::table("users")->where("id", $id)->update(["enabled" => 0]);
        return 1;
    }
}
