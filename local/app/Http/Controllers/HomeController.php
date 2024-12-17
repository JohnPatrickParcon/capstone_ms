<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        if(Auth::check()){
            if(Auth::user()->role == 1){
                // Admins
                // return redirect('home');
            } else if(Auth::user()->role == 2){
                // Coordinators
                return redirect()->route('coordinator.dashboard');
            } else if(Auth::user()->role == 3){
                // Advisers/Panels
                return redirect()->route('adviser.dashboard');
            } else {
                // Students
                return redirect()->route('student.dashboard');
            }
        }
    }
}
