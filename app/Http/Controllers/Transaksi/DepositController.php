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
        // $data = DB::table('players')->get();
        return view('transactions.deposit.index');
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
                'createdby'    => Auth::user()->name,
                'created_at'   => date('Y-m-d H:i:s')
            );
            array_push($depoData, $insertData);
            insertOrUpdate($depoData,'deposits');

            // Update Stock Coin
            $stockCoint = 0;
            $totalcoin = DB::table('coin_stocks')->first();
            if($totalcoin){
                $stockCoint = $totalcoin->totalcoin + $request['jmlDepo'];
                // $coinData = array();
                // $insertCoin = array(
                //     'totalcoin'    => $stockCoint,
                //     'updated_at'   => date('Y-m-d H:i:s')
                // );
                // array_push($coinData, $insertCoin);
                // insertOrUpdate($coinData,'coin_stocks');
                DB::table('coin_stocks')->where('id', $totalcoin->id)->update([
                    'totalcoin' => $stockCoint,
                    'updated_at'   => date('Y-m-d H:i:s')
                ]);
            }else{
                $stockCoint = $request['jmlDepo'];
                $coinData = array();
                $insertCoin = array(
                    'totalcoin'    => $stockCoint,
                    'created_at'   => date('Y-m-d H:i:s')
                );
                array_push($coinData, $insertCoin);
                insertOrUpdate($coinData,'coin_stocks');
            }

            DB::commit();
            
            return Redirect::to("/transaksi/deposit")->withSuccess('Data Deposit Berhasil di input');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/deposit")->withError($e->getMessage());
        }
    }
}
