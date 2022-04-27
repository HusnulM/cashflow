<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use DB;
use Auth;

class PemasukanController extends Controller
{
    public function index(){
        $bank = DB::table('v_banks')->get();
        return view('transactions.pemasukan.index', ['bank' => $bank]);
    }

    public function save(Request $request){
        // return $request;
        DB::beginTransaction();
        try{
            
            $dataPemasukan = array();
            $insertData = array(
                'tgl_pemasukan'   => $request['tglPemasukan'],
                'amount'          => $request['jmlPemasukan'],
                'keterangan'      => $request['note'],
                'bank_account'    => $request['rekening'],
                'createdby'       => Auth::user()->name,
                'created_at'      => date('Y-m-d H:i:s')
            );
            array_push($dataPemasukan, $insertData);
            insertOrUpdate($dataPemasukan,'incomes');

            $rekTujuan = DB::table('banks')->where('bank_accountnumber', $request['rekening'])->first();

            $latestSaldo = 0;

            $saldoRek = DB::table('cashflows')->where('to_acc',$request['rekening'])->limit(1)->orderBy('id','DESC')->first();
            if($saldoRek){
                $latestSaldo = $saldoRek->balance;
            }

            // Insert Mutasi Pemasukan ke rekening Tujuan
            $castFlow = array();
            $insertcastFlow = array(
                'transdate'     => now(),
                'note'          => $request['note'],
                'from_acc'      => '',
                'to_acc'        => $request['rekening'],
                'debit'         => 0,
                'credit'        => $request['jmlPemasukan'],
                'balance'       => $latestSaldo+$request['jmlPemasukan'],
                'createdby'     => Auth::user()->name,
                'created_at'    => now()
            );
            array_push($castFlow, $insertcastFlow);
            insertOrUpdate($castFlow,'cashflows');

            DB::commit();
            
            return Redirect::to("/transaksi/pemasukan")->withSuccess('Data Pemasukan Berhasil di Input');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/pemasukan")->withError($e->getMessage());
        }
    }
}
