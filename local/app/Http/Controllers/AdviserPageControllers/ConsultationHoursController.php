<?php

namespace App\Http\Controllers\AdviserPageControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use DateTime;

class ConsultationHoursController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function checkRole(){
        if(Auth::user()->role != 3){
            abort(404);
        }
    }

    public function index(Request $request){
        $this->checkRole();
        if($request->ajax()){
            $schedules = DB::table("consultation_hours")->where("user_id", Auth::user()->id)->orderBy("day")->get();
            return datatables()
                ->of($schedules)
                ->addIndexColumn()
                ->addColumn('action', function ($sched) {
                    $btn = '';
                    $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleEditSchedule(\''.encrypt($sched->id).'\')"><i class="mdi mdi-calendar-edit" title="Edit Schedule"></i></button>&nbsp;&nbsp;';
                    $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleDeleteSchedule(\''.encrypt($sched->id).'\')"><i class="mdi mdi-calendar-remove-outline" title="Delete Schedule"></i></button>&nbsp;&nbsp;';
                    return $btn;
                })
                ->addColumn('day', function ($sched) {
                    $day = '';
                    if($sched->day == 1){ $day = "Monday"; } 
                    else if($sched->day == 2) { $day = "Tuesday"; }
                    else if($sched->day == 3) { $day = "Wednesday"; } 
                    else if($sched->day == 4) { $day = "Thursday"; } 
                    else if($sched->day == 5) { $day = "Friday"; } 
                    else if($sched->day == 6) { $day = "Saturday"; } 
                    else { $day = "Sunday"; }
                    return $day;
                })
                ->addColumn('time', function ($sched) {
                    $start = DateTime::createFromFormat('H:i:s', $sched->start_time);
                    $end = DateTime::createFromFormat('H:i:s', $sched->end_time);
                    $time =  $start->format('h:i A') . " - " . $end->format('h:i A');
                    return $time;
                })
                ->rawColumns([
                    'action',
                    'day',
                    'time'
                ])
            ->make(true);
        }
        return view('pages.adviser.consultation');
    }

    public function addSchedule(Request $request){
        $data = $request->json()->all();
        DB::beginTransaction();
        try {
            DB::table("consultation_hours")->insert([
                "user_id" => Auth::user()->id,
                "day" => $data["day"],
                "start_time" => $data["start_time"],
                "end_time" => $data["end_time"],
            ]);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollback();
            return false;
        }
    }

    public function getMySchedule($id){
        $id = decrypt($id);
        $res = DB::table("consultation_hours")->where("id", $id)->first();
        return $res;
    }

    public function updateMySchedule($id, Request $request){
        $data = $request->json()->all();
        DB::beginTransaction();
        try {
            DB::table("consultation_hours")->where("id", $id)->update([
                "day" => $data["day"],
                "start_time" => $data["start_time"],
                "end_time" => $data["end_time"],
            ]);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollback();
            return false;
        }
    }

    public function deleteMySchedule($id){
        $id = decrypt($id);
        DB::table("consultation_hours")->where("id", $id)->delete();
        return 1;
    } 
}
