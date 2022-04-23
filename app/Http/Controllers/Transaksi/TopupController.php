<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; 
use Validator,Redirect,Response;
use DB;
use Auth;

class TopupController extends Controller
{
    public function index(){
        $bank = DB::table('v_banks')->get();
        return view('transactions.topup.index', ['bank' => $bank]);
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
            $totalcoin = DB::table('stock_coins')->where('id', '1')->first();
            if($totalcoin){
                $stockCoint = $totalcoin->quantity + $request['jmlDepo'];
                DB::table('stock_coins')->where('id', '1')->update([
                    'quantity' => $stockCoint,
                    'updatedon'=> date('Y-m-d')
                ]);
            }else{
                $stockCoint = $request['jmlDepo'];
                $coinData = array();
                $insertCoin = array(
                    'id'        => '1',
                    'quantity'  => $stockCoint,
                    'createdon' => date('Y-m-d'),
                    'updatedon' => date('Y-m-d')
                );
                array_push($coinData, $insertCoin);
                insertOrUpdate($coinData,'stock_coins');
            }

            // $totalcoin = DB::table('coin_stocks')->where('bankcode', $bankData->bankid)
            // ->where('bankacc', $request['rekening'])->first();
            // if($totalcoin){
            //     $stockCoint = $totalcoin->totalcoin + $request['jmlDepo'];
            //     DB::table('coin_stocks')->where('id', $totalcoin->id)->update([
            //         'totalcoin' => $stockCoint,
            //         'updated_at'   => date('Y-m-d H:i:s')
            //     ]);
            // }else{
            //     $stockCoint = $request['jmlDepo'];
            //     $coinData = array();
            //     $insertCoin = array(
            //         'bankcode'     => $bankData->bankid,
            //         'bankacc'      => $request['rekening'],
            //         'totalcoin'    => $stockCoint,
            //         'createdby'    => Auth::user()->name,
            //         'created_at'   => date('Y-m-d H:i:s')
            //     );
            //     array_push($coinData, $insertCoin);
            //     insertOrUpdate($coinData,'coin_stocks');
            // }

            // Insert Mutasi Pengeluaran dari rekening pembayaran deposit
            // $latestSaldo = 0;
            // $saldoRekAsal = DB::table('cashflows')->where('to_acc',$request['rekening'])->limit(1)->orderBy('id','DESC')->first();
            // if($saldoRekAsal){
            //     $latestSaldo = $saldoRekAsal->balance;
            // }

            // $castFlow = array();
            // $insertcastFlow = array(
            //     'transdate'     => now(),
            //     'note'          => 'Top Up Coin '. $bankData->bankname . ' '. $bankData->bank_accountname . ' - ' . $bankData->bank_accountnumber,
            //     'from_acc'      => '',
            //     'to_acc'        => $request['rekening'],
            //     'debit'         => $request['jmlDepo'],
            //     'credit'        => 0,
            //     'balance'       => $latestSaldo - $request['jmlDepo'],
            //     'createdby'     => Auth::user()->name,
            //     'created_at'    => now()
            // );
            // array_push($castFlow, $insertcastFlow);
            // insertOrUpdate($castFlow,'cashflows');

            DB::commit();
            
            return Redirect::to("/transaksi/topup")->withSuccess('TOP Up Berhasil di input');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/topup")->withError($e->getMessage());
        }
    }
}
