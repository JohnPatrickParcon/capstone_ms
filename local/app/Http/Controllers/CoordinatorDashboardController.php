<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class CoordinatorDashboardController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function checkRole(){
        if(Auth::user()->role != 2){
            abort(404);
        }
    }

    public function index(){
        $this->checkRole();
        return view('pages.coordinator.index');
    }
}
