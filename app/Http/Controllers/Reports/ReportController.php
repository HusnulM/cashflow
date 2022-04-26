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
        return view('reports.topupsel');
    }

    public function reportTopupView($strdate, $enddate){
        // $data = DB::table('deposits')->get();
        $query = DB::table('deposits');

        if($strdate != 'null' && $enddate != 'null'){
            $query->whereBetween('tgl_deposit', [$strdate, $enddate]);
        }elseif($strdate != 'null' && $enddate == 'null'){
            $query->where('tgl_deposit', $strdate);
        }elseif($strdate == 'null' && $enddate != 'null'){
            $query->where('tgl_deposit', $enddate);
        }

        // if(Auth::user()->usertype <> 'Owner'){
        //     $query->where('bank_type', '!=','Penampung');
        // }

        $data = $query
                ->orderBy('id','ASC')
                ->get();
        return view('reports.topup', ['data' => $data]);
    }

    public function reportWithdraw(){
        if(Auth::user()->usertype == 'Owner'){
            $data = DB::table('v_banks')->get();
        }else{
            $data = DB::table('v_banks')->where('bank_penampung','!=','Y')->get();
        }
        return view('reports.wdsel', ['bank' => $data]);
    }

    public function reportWithdrawView($strdate, $enddate){
        $query = DB::table('v_withdraws');
        if($strdate != 'null' && $enddate != 'null'){
            $query->whereBetween('wdpdate', [$strdate, $enddate]);
        }elseif($strdate != 'null' && $enddate == 'null'){
            $query->where('wdpdate', $strdate);
        }elseif($strdate == 'null' && $enddate != 'null'){
            $query->where('wdpdate', $enddate);
        }

        if(Auth::user()->usertype <> 'Owner'){
            $query->where('bank_penampung', '!=','Y');
        }

        $data = $query
                ->orderBy('id','ASC')
                ->get();
        return view('reports.wd', ['data' => $data]);
    }

    public function reportMutasi(){
        if(Auth::user()->usertype == 'Owner'){
            $data = DB::table('v_banks')->get();
        }else{
            $data = DB::table('v_banks')->where('bank_penampung','N')->get();
        }
        
        return view('reports.mutasisel', ['bank' => $data]);
    }

    public function reportMutasiView($bankid, $strdate, $enddate){
        $query = DB::table('v_cashflows');
        if($bankid != 'all'){
            $query->where('to_acc', $bankid);
        }

        if($strdate != 'null' && $enddate != 'null'){
            $query->whereBetween('transdate', [$strdate, $enddate]);
        }elseif($strdate != 'null' && $enddate == 'null'){
            $query->where('transdate', $strdate);
        }elseif($strdate == 'null' && $enddate != 'null'){
            $query->where('transdate', $enddate);
        }

        if(Auth::user()->usertype <> 'Owner'){
            $query->where('bank_penampung','N');
        }

        $data = $query
                ->orderBy('to_acc','ASC')
                ->orderBy('id','ASC')
                ->get();
        return view('reports.mutasi', ['data' => $data]);
    }

    public function reportPemasukan(){
        if(Auth::user()->usertype == 'Owner'){
            $data = DB::table('v_banks')->get();
        }else{
            $data = DB::table('v_banks')->where('bank_penampung','!=','Y')->get();
        }
        return view('reports.pemasukansel', ['bank' => $data]);
    }

    public function reportPemasukanView($strdate = null, $enddate = null){
        // $data = DB::table('incomes')->get();
        $query = DB::table('v_incomes');

        if($strdate != 'null' && $enddate != 'null'){
            $query->whereBetween('tgl_pemasukan', [$strdate, $enddate]);
        }elseif($strdate != 'null' && $enddate == 'null'){
            $query->where('tgl_pemasukan', $strdate);
        }elseif($strdate == 'null' && $enddate != 'null'){
            $query->where('tgl_pemasukan', $enddate);
        }

        if(Auth::user()->usertype <> 'Owner'){
            $query->where('bank_penampung', '!=','Y');
        }

        $data = $query
                ->orderBy('bank_account','ASC')
                ->orderBy('id','ASC')
                ->get();

        return view('reports.pemasukan', ['data' => $data]);
    }

    public function reportPengeluaran(){
        if(Auth::user()->usertype == 'Owner'){
            $data = DB::table('v_banks')->get();
        }else{
            $data = DB::table('v_banks')->where('bank_penampung','!=','Y')->get();
        }
        return view('reports.pengeluaransel', ['bank' => $data]);
    }

    public function reportPengeluaranView($strdate = null, $enddate = null){
        $query = DB::table('v_expenses');

        if($strdate != 'null' && $enddate != 'null'){
            $query->whereBetween('tgl_pengeluaran', [$strdate, $enddate]);
        }elseif($strdate != 'null' && $enddate == 'null'){
            $query->where('tgl_pengeluaran', $strdate);
        }elseif($strdate == 'null' && $enddate != 'null'){
            $query->where('tgl_pengeluaran', $enddate);
        }

        if(Auth::user()->usertype <> 'Owner'){
            $query->where('bank_penampung', '!=','Y');
        }

        $data = $query
                ->orderBy('bank_account','ASC')
                ->orderBy('id','ASC')
                ->get();
        return view('reports.pengeluaran', ['data' => $data]);
    }

    public function reportDeposit(){
        if(Auth::user()->usertype == 'Owner'){
            $data = DB::table('v_banks')->get();
        }else{
            $data = DB::table('v_banks')->where('bank_penampung','!=','Y')->get();
        }
        return view('reports.depositsel', ['bank' => $data]);
    }

    public function reportDepositView($strdate = null, $enddate = null){
        $query = DB::table('v_deposit_players');
        // if($bankid != 'all'){
        //     $query->where('rekening_tujuan', $bankid);
        // }

        if($strdate != 'null' && $enddate != 'null'){
            $query->whereBetween('topupdate', [$strdate, $enddate]);
        }elseif($strdate != 'null' && $enddate == 'null'){
            $query->where('topupdate', $strdate);
        }elseif($strdate == 'null' && $enddate != 'null'){
            $query->where('topupdate', $enddate);
        }

        if(Auth::user()->usertype <> 'Owner'){
            $query->where('bank_penampung', '!=','Y');
        }

        $data = $query
                ->orderBy('id','ASC')
                ->get();
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
