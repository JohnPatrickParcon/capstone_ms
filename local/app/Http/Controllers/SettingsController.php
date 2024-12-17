<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;

class SettingsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        return view('pages.components.settings');
    }

    public function update(Request $request){
        $data = $request->json()->all();

        $guard = true;
        $full_name = $data["full_name"];
        $password = $data["password"];
        $confirm_password = $data["password_confirmation"];

        $error_list = [];
        if($full_name == ""){
            $guard = false;
            $error_list[] = "Name should not be empty";
        }
        if(strlen($password) < 8){
            $guard = false;
            $error_list[] = "Password should be 8 characters and up";
        }
        if($password != $confirm_password){
            $guard = false;
            $error_list[] = "Password not Matched";
        }


        if($guard){
            DB::beginTransaction();
            try {
                DB::table("users")->where("id", Auth::user()->id)->update([
                    "name" => $full_name,
                    "password" => Hash::make($password)
                ]);
                DB::commit();
                return json_encode([
                    "result" => 1,
                    "title" => "Success",
                    "icon" => "success",
                    "message" => "Account Successfully Updated!"
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                return json_encode([
                    "result" => 1,
                    "title" => "Error",
                    "icon" => "error",
                    "message" => "Something went wrong",
                ]);
            }
        }

        return json_encode([
            "result" => 0,
            "errors" => $error_list
        ]);


    }
}
