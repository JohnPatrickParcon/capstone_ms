<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PublicAccessController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $capstones = DB::table("published_capstone")->where("enabled", 1)->get();
            return datatables()
                ->of($capstones)
                ->addIndexColumn()
                ->addColumn('action', function ($capstone) {
                    $btn = '';
                    $btn .= '<button class="btn" style="background: green; color: white;" onclick="handleViewCapstone(\''.encrypt($capstone->id).'\')">View</button>&nbsp;&nbsp;';
                    return $btn;
                })
                ->rawColumns([
                    'action',
                ])
            ->make(true);
        }
        return view('pages.public_access.index2');
    }

    public function preview($id){
        $id = decrypt($id);
        $capstone = DB::table("published_capstone")->where("id", $id)->where("enabled", 1)->first();
        return $capstone;
    }

    public function public_access(){
        return view('pages.public_access.index2');
    }
}
