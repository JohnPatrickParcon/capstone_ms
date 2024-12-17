<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class AdvisersController extends Controller
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
            $users = DB::table("users")->where("role", 3)->get();
            return datatables()
                ->of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($user) {
                    $btn = '';
                    // $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleViewCapstone(\''.encrypt($capstone->id).'\')">View</button>&nbsp;&nbsp;';
                    return $btn;
                })
                ->rawColumns([
                    'action',
                ])
            ->make(true);
        }
        return view('pages.coordinator.advisers');
    }
}
