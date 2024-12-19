<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoordinatorRegisterController extends Controller
{
    public function coordinatorRegister()
    {
        return view("pages.coordinator.register");
    }

    public function adviserRegister()
    {
        return view("pages.adviser.register");
    }
}
