<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\InviteAdviser;
use App\Mail\SendCredentials;
use Mail;
use DB;
use Illuminate\Support\Facades\Hash;

class InviteAdviserController extends Controller
{
    public function send(Request $request){
        $data = $request->json()->all();
        $token = $this->generateRandomString();

        $email_checker = DB::table("users")->where("email", $data["email"])->get();
        if(count($email_checker) > 0){
            return json_encode(["status_int" => 0, "status" => "error", "message" => "Email Already Registered"]);
        }

        DB::beginTransaction();
        try {
            $temp = DB::table("invitation_token")->where("email", $data["email"])->where("enabled", 1)->update([
                "enabled" => 0
            ]);
            //dd($temp);
            $check = DB::table("invitation_token")->insert([
                "token" => $token,
                "email" => $data["email"]
            ]);
            //dd($check);
            $this->compose_email($token, $data["email"]);
            DB::commit();
            return json_encode(["status_int" => 1, "status" => "success", "message" => "Invitation Sent"]);
        } catch (\Throwable $th) {
            DB::rollback();
            //dd($th);
        }
    }

    public function generateRandomString($length = 50) {
        return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / 62))), 0, $length);
    }

    public function compose_email($token, $email){
        $domain = request()->getHost();
        $fullUrl = request()->fullUrl();
        $trimmedUrl = substr($fullUrl, 0, strrpos($fullUrl, '/')) . "/acceptInvitation" . "/" . $token;

        $data = [
            "url" => $trimmedUrl,
        ];

        Mail::to($email)->send(new InviteAdviser($data));
    }

    public function accept($token){

        $fullUrl = request()->fullUrl();
        $parts = explode('/', rtrim($fullUrl, '/'));
        $trimmedParts = array_slice($parts, 0, -2);
        $newUrl = implode('/', $trimmedParts);
        $res = DB::table("invitation_token")->where("token", $token)->where("enabled", 1)->get();
        if(count($res) == 0){
            return view("pages.errors.invitation_errors", ["message" => "Invalid Token | Token Not Found"]);
        }

        DB::beginTransaction();
        try {
            $pass = $this->generateRandomString(8);
            DB::table("users")->insert([
                "name" => "Adviser " . time(),
                "email" => $res[0]->email,
                "role" => 3,
                "is_verified" => 1,
                "password" => Hash::make($pass),
            ]);

            $data = [
                "email" => $res[0]->email,
                "password" => $pass,
                "site" => $newUrl,
            ];

            Mail::to($res[0]->email)->send(new SendCredentials($data));
            DB::table("invitation_token")->where("token", $token)->where("enabled", 1)->update([
                'enabled' => 0
            ]);

            DB::commit();
            $message1 = "Account Registered, Please check your email for the credentials";
            $message2 = "Thank you for accepting the invitation!";
            return view("mails.inviteSuccess", ["message1" => $message1, "message2" => $message2]);
        } catch (\Throwable $th) {
            DB::rollback();
            //dd($th);
            return 0;
        }

        //dd($res);
    }
}
