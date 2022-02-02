<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
use Validator,Redirect,Response;
use DB;
use Auth;

class DepositController extends Controller
{
    public function index(){
        $bank = DB::table('banks')->get();
        return view('transactions.deposit.index', ['bank' => $bank]);
    }

    public function save(Request $request){
        // return $request;
        DB::beginTransaction();
        try{
            
            $depoData = array();
            $insertData = array(
                'tgl_deposit'  => $request['tglDepo'],
                'amount'       => $request['jmlDepo'],
                'keterangan'   => $request['note'],
                'bankacc'      => $request['rekening'],
                'createdby'    => Auth::user()->name,
                'created_at'   => date('Y-m-d H:i:s')
            );
            array_push($depoData, $insertData);
            insertOrUpdate($depoData,'deposits');

            $bankData = DB::table('banks')->where('bank_accountnumber', $request['rekening'])->first();

            // Update Stock Coin
            $stockCoint = 0;
            $totalcoin = DB::table('coin_stocks')->where('bankcode', $bankData->bankid)
            ->where('bankacc', $request['rekening'])->first();
            if($totalcoin){
                $stockCoint = $totalcoin->totalcoin + $request['jmlDepo'];
                DB::table('coin_stocks')->where('id', $totalcoin->id)->update([
                    'totalcoin' => $stockCoint,
                    'updated_at'   => date('Y-m-d H:i:s')
                ]);
            }else{
                $stockCoint = $request['jmlDepo'];
                $coinData = array();
                $insertCoin = array(
                    'bankcode'     => $bankData->bankid,
                    'bankacc'      => $request['rekening'],
                    'totalcoin'    => $stockCoint,
                    'createdby'    => Auth::user()->name,
                    'created_at'   => date('Y-m-d H:i:s')
                );
                array_push($coinData, $insertCoin);
                insertOrUpdate($coinData,'coin_stocks');
            }

            // Insert Mutasi Pengeluaran dari rekening pembayaran deposit
            $latestSaldo = 0;
            $saldoRekAsal = DB::table('cashflows')->where('to_acc',$request['rekening'])->limit(1)->orderBy('id','DESC')->first();
            if($saldoRekAsal){
                $latestSaldo = $saldoRekAsal->balance;
            }

            $castFlow = array();
            $insertcastFlow = array(
                'transdate'     => now(),
                'note'          => 'Deposit Coin '. $bankData->bankname . ' '. $bankData->bank_accountname . ' - ' . $bankData->bank_accountnumber,
                'from_acc'      => '',
                'to_acc'        => $request['rekening'],
                'debit'         => $request['jmlDepo'],
                'credit'        => 0,
                'balance'       => $latestSaldo - $request['jmlDepo'],
                'createdby'     => Auth::user()->name,
                'created_at'    => now()
            );
            array_push($castFlow, $insertcastFlow);
            insertOrUpdate($castFlow,'cashflows');

            DB::commit();
            
            return Redirect::to("/transaksi/deposit")->withSuccess('Data Deposit Berhasil di input');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/deposit")->withError($e->getMessage());
        }
    }
}
