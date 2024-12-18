<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoordinatorRegisterController extends Controller
{
    public function index()
    {
        return view("pages.coordinator.register");
    }
}
