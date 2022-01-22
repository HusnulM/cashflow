<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use DB;
use Auth;

class ReportController extends Controller
{
    public function reportTopup(){
        $data = DB::table('topups')->get();
        return view('reports.topup', ['data' => $data]);
    }

    public function reportWithdraw(){
        $data = DB::table('withdraws')->get();
        return view('reports.wd', ['data' => $data]);
    }

    public function reportMutasi(){
        $data = DB::table('cashflows')->get();
        return view('reports.mutasi', ['data' => $data]);
    }
}
