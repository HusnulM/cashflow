<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use DB;
use Auth;

class PengeluaranController extends Controller
{
    public function index(){
        $bank = DB::table('v_banks')->get();
        return view('transactions.pengeluaran.index', ['bank' => $bank]);
    }

    public function save(Request $request){
        // return $request;
        DB::beginTransaction();
        try{
            
            $dataPemasukan = array();
            $insertData = array(
                'tgl_pengeluaran' => $request['tglPengeluaran'],
                'amount'          => $request['jmlPengeluaran'],
                'keterangan'      => $request['note'],
                'bank_account'    => $request['rekening'],
                'createdby'       => Auth::user()->name,
                'created_at'      => date('Y-m-d H:i:s')
            );
            array_push($dataPemasukan, $insertData);
            insertOrUpdate($dataPemasukan,'expenses');

            $rekTujuan = DB::table('banks')->where('bank_accountnumber', $request['rekening'])->first();

            $latestSaldo = 0;

            $saldoRek = DB::table('cashflows')->where('to_acc',$request['rekening'])->limit(1)->orderBy('id','DESC')->first();
            if($saldoRek){
                $latestSaldo = $saldoRek->balance;
            }

            // Insert Mutasi Pengeluaran dari rekening sumber
            $castFlow = array();
            $insertcastFlow = array(
                'transdate'     => now(),
                'note'          => $request['note'],
                'from_acc'      => '',
                'to_acc'        => $request['rekening'],
                'debit'         => $request['jmlPengeluaran'],
                'credit'        => 0,
                'balance'       => $latestSaldo-$request['jmlPengeluaran'],
                'createdby'     => Auth::user()->name,
                'created_at'    => now()
            );
            array_push($castFlow, $insertcastFlow);
            insertOrUpdate($castFlow,'cashflows');

            DB::commit();
            
            return Redirect::to("/transaksi/pengeluaran")->withSuccess('Data Pengeluaran Berhasil di Input');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/pengeluaran")->withError($e->getMessage());
        }
    }
}
