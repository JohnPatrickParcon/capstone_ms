<?php

namespace App\Http\Controllers\StudentPageControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class DashboardController extends Controller
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
        $has_groupings = $this->checkGroupings();
        return view('pages.student.index', ["has_groupings" => $has_groupings]);
    }
    
    public function checkGroupings(){
        $res = DB::table("groupings")->where("user_id", Auth::user()->id)->get();
        return count($res) > 0 ? true : false;
    }
}
