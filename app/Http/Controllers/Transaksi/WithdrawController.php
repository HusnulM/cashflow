<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
use Validator,Redirect,Response;
use DB;
use Auth;
use Excel;

class WithdrawController extends Controller
{
    public function index(){
        $bank = DB::table('v_banks')->get();
        return view('transactions.withdraw.withdraw', ['bank' => $bank]);
    }

    public function verify(){
        $data = DB::table('withdraws')->where('wd_status', 'Open')->get();
        return view('transactions.withdraw.verify', ['data' => $data]);
    }

    public function save(Request $request){
        DB::beginTransaction();
        try{
            // $destinationPath = 'efiles/topupfiles';
            // if(!File::exists($destinationPath)) {
            //     File::makeDirectory($destinationPath, 0755, true, true);
            // }

            $output = array();
            $playerid = $request['itm_idplayer'];
            $nmayerid = $request['itm_nmplayer'];
            $jmltopup = $request['itm_jmltopup'];
            $tgltopup = $request['itm_tgltopup'];
            $rekening = $request['itm_rekening'];
            // $xfile    = $request->file('itm_efile');
            
            for($i = 0; $i < sizeof($playerid); $i++){
                // $file = $xfile[$i];
                
                $insertData = array(
                    'idplayer'     => $playerid[$i],
                    'playername'   => $nmayerid[$i],
                    'amount'       => $jmltopup[$i],
                    'wdpdate'      => $tgltopup[$i],
                    'wd_status'    => 'Open',
                    'rekening_sumber' => $rekening[$i],
                    // 'efile'        => $file->getClientOriginalName(),
                    'createdby'    => Auth::user()->name,
                    'created_at'   => now()
                );
                array_push($output, $insertData);

                // if(!empty($file)){
                //     $file->move($destinationPath,$file->getClientOriginalName());
                // }
            }
            insertOrUpdate($output,'withdraws');
            DB::commit();

            
            return Redirect::to("/transaksi/withdraw")->withSuccess('Data Withdraw Berhasil di input');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/withdraw")->withError($e->getMessage());
        }
    }

    public function close($id){
        DB::beginTransaction();
        try{
            DB::table('withdraws')->where('id', $id)->update([
                'wd_status' => 'Close',
                'updated_at'   => now()
            ]);

            $wdData = DB::table('withdraws')->where('id', $id)->first();

            $latestSaldo = 0;
            $saldo = DB::table('cashflows')->where('to_acc',$wdData->rekening_sumber)->limit(1)->orderBy('id','DESC')->first();
            if($saldo){
                $latestSaldo = $saldo->balance;
            }

            $castFlow = array();
            $insertcastFlow = array(
                'transdate'     => now(),
                'note'          => 'WD player '. $wdData->idplayer,
                'from_acc'      => '',
                'to_acc'        => $wdData->rekening_sumber,
                'debit'         => $wdData->amount,
                'credit'        => 0,
                'balance'       => $latestSaldo-$wdData->amount,
                'createdby'     => Auth::user()->name,
                'created_at'    => now()
            );
            array_push($castFlow, $insertcastFlow);
            insertOrUpdate($castFlow,'cashflows');

            //Update Stock coin
            $stockCoint = 0;
            $bankData = DB::table('banks')->where('bank_accountnumber', $wdData->rekening_sumber)->first();
            $totalcoin = DB::table('coin_stocks')->where('bankcode', $bankData->bankid)->where('bankacc', $wdData->rekening_sumber)->first();
            if($totalcoin){
                $stockCoint = $totalcoin->totalcoin;
                DB::table('coin_stocks')->where('id', $totalcoin->id)->update([
                    'totalcoin' => $stockCoint + $wdData->amount,
                    'updated_at'   => date('Y-m-d H:i:s')
                ]);
            }
            // $coinData = DB::table('coin_stocks')->where('id',$wdData->idplayer)->first();

            DB::commit();            
            return Redirect::to("/transaksi/withdraw/verify")->withSuccess('Withdraw Berhasil di proses');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/withdraw/verify")->withError($e->getMessage());
        }
    }
}
