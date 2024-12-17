<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class NotificationController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function getNotifications(){
        $res = DB::table("notifications")
            ->where("users", "like", '%"'.Auth::user()->id.'"%')
            ->where("seen_by", "not like", '%"'.Auth::user()->id.'"%')
            ->orderBy("created_at", "desc")
            ->get();

        return $res;
    }

    public function getAllNotifications(){
        $res = DB::table("notifications")
            ->where("users", "like", '%"'.Auth::user()->id.'"%')
            ->orderBy("created_at", "desc")
            ->get();
        return $res;
    }

    public function view(Request $request){
        if($request->ajax()){
            $notifications = $this->getAllNotifications();
            return datatables()
                ->of($notifications)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= "<button class='btn btn-primary' onclick='viewNotifications(\"".encrypt($row->notification_id)."\")'>View</button>&nbsp;";
                    return $btn;
                })
                ->addColumn('topic', function ($row) {
                    return $row->targets;
                })
                ->addColumn('status', function ($row) {
                    $users = json_decode($row->seen_by);
                    $status = in_array(Auth::user()->id, $users) ? "<span style='color: gray;'><i>Seen</i></span>" : "<span style='color: green;'><i>New</i></span>";
                    return $status;
                })
                ->rawColumns([
                    'action', 'topic', 'status'
                ])
            ->make(true);
        }

        return view("pages.notifications.index");
    }
}
