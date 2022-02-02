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
        $data = DB::table('cashflows')->orderBy('to_acc','ASC')->orderBy('id','ASC')->get();
        return view('reports.mutasi', ['data' => $data]);
    }

    public function reportPemasukan(){
        $data = DB::table('incomes')->get();
        return view('reports.pemasukan', ['data' => $data]);
    }

    public function reportPengeluaran(){
        $data = DB::table('expenses')->get();
        return view('reports.pengeluaran', ['data' => $data]);
    }

    public function reportDeposit(){
        $data = DB::table('deposits')->get();
        return view('reports.deposit', ['data' => $data]);
    }

    public function reportStockcoin(){
        $data = DB::table('coin_stocks')->get();
        return view('reports.stockcoin', ['data' => $data]);
    }

    public function reportSaldobank(){
        $data = DB::table('v_saldobank')->get();
        return view('reports.saldobank', ['data' => $data]);
    }
}
