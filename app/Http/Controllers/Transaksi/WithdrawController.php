<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Imports\WithdrawImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
use Validator,Redirect,Response;
use DB;
use Auth;
use Excel;

class WithdrawController extends Controller
{
    public function index(){
        $bank = DB::table('v_banks')->where('bank_wd','Y')->get();
        return view('transactions.withdraw.withdraw', ['bank' => $bank]);
    }

    public function upload(){
        return view('transactions.withdraw.upload');
    }

    public function verify(){
        $data = DB::table('v_withdraws')->where('wd_status', 'Open')->get();
        return view('transactions.withdraw.verify', ['data' => $data]);
    }

    public function save(Request $request){
        DB::beginTransaction();
        try{
            
            $latestSaldo = 0;
            $saldo = DB::table('cashflows')->where('to_acc',$request['rekening'])->limit(1)->orderBy('id','DESC')->first();
            if($saldo){
                $latestSaldo = $saldo->balance;
            }

            if($latestSaldo < ($request['jmlwd']+$request['biaya_adm'])){
                DB::rollBack();
                return Redirect::to("/transaksi/withdraw")->withError('Saldo Rek '. $request['rekening'] . ' tidak mencukupi');
            }

            $output = array();
            $insertData = array(
                'idplayer'     => $request['idplayer'],
                'playername'   => $request['namaplayer'],
                'amount'       => $request['jmlwd'],
                'biaya_adm'    => $request['biaya_adm'],
                'wdpdate'      => $request['tglwd'],
                'wd_status'    => 'Close',
                'rekening_sumber' => $request['rekening'],
                'createdby'    => Auth::user()->name,
                'created_at'   => now()
            );
            array_push($output, $insertData);

            insertOrUpdate($output,'withdraws');


            $castFlow = array();
            $insertcastFlow = array(
                'transdate'     => now(),
                'note'          => 'WD player '. $request['idplayer'],
                'from_acc'      => '',
                'to_acc'        => $request['rekening'],
                'debit'         => $request['jmlwd'],
                'credit'        => 0,
                'balance'       => $latestSaldo-$request['jmlwd'],
                'createdby'     => Auth::user()->name,
                'created_at'    => now()
            );
            array_push($castFlow, $insertcastFlow);
            insertOrUpdate($castFlow,'cashflows');

            if($request['biaya_adm'] > 0){
                $castFlow = array();
                $insertcastFlow = array(
                    'transdate'     => now(),
                    'note'          => 'Biaya Admin WD player '. $request['idplayer'],
                    'from_acc'      => '',
                    'to_acc'        => $request['rekening'],
                    'debit'         => $request['biaya_adm'],
                    'credit'        => 0,
                    'balance'       => $latestSaldo-$request['jmlwd']-$request['biaya_adm'],
                    'createdby'     => Auth::user()->name,
                    'created_at'    => now()
                );
                array_push($castFlow, $insertcastFlow);
                insertOrUpdate($castFlow,'cashflows');
            }

            //Update Stock coin
            $stock = DB::table('stock_coins')->where('id', '1')->first();
            $stockCoint = $stock->quantity + $request['jmlwd'] ?? 0;
            DB::table('stock_coins')->where('id', '1')->update([
                'quantity' => $stockCoint,
                'updatedon'=> date('Y-m-d')
            ]);
            // $stockCoint = 0;
            // $bankData = DB::table('banks')->where('bank_accountnumber', $wdData->rekening_sumber)->first();
            // $totalcoin = DB::table('coin_stocks')->where('bankcode', $bankData->bankid)->where('bankacc', $wdData->rekening_sumber)->first();
            // if($totalcoin){
            //     $stockCoint = $totalcoin->totalcoin;
            //     DB::table('coin_stocks')->where('id', $totalcoin->id)->update([
            //         'totalcoin' => $stockCoint + $wdData->amount,
            //         'updated_at'   => date('Y-m-d H:i:s')
            //     ]);
            // }

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
            $wdData = DB::table('v_withdraws')->where('id', $id)->first();

            $latestSaldo = 0;
            $saldo = DB::table('cashflows')->where('to_acc',$wdData->rekening_sumber)->limit(1)->orderBy('id','DESC')->first();
            if($saldo){
                $latestSaldo = $saldo->balance;
            }

            if($latestSaldo < $wdData->amount){
                DB::rollBack();
                return Redirect::to("/transaksi/withdraw")->withError('Saldo Rek '. $wdData->rekening_sumber . ' tidak mencukupi');
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

            if($wdData->biaya_adm > 0){
                $castFlow = array();
                $insertcastFlow = array(
                    'transdate'     => now(),
                    'note'          => 'Biaya Admin WD player '. $wdData->idplayer,
                    'from_acc'      => '',
                    'to_acc'        => $wdData->rekening_sumber,
                    'debit'         => $wdData->biaya_adm,
                    'credit'        => 0,
                    'balance'       => $latestSaldo-$wdData->amount-$wdData->biaya_adm,
                    'createdby'     => Auth::user()->name,
                    'created_at'    => now()
                );
                array_push($castFlow, $insertcastFlow);
                insertOrUpdate($castFlow,'cashflows');
            }

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

            DB::table('withdraws')->where('id', $id)->update([
                'wd_status' => 'Close',
                'updated_at'   => now()
            ]);

            DB::commit();            
            return Redirect::to("/transaksi/withdraw/verify")->withSuccess('Withdraw Berhasil di proses');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/withdraw/verify")->withError($e->getMessage());
        }
    }

    public function importWithdraw(Request $request){
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = $file->hashName();        

        $destinationPath = 'excel/';
        $file->move($destinationPath,$file->getClientOriginalName());

        config(['excel.import.startRow' => 2]);
        // import data
        $import = Excel::import(new WithdrawImport(), 'excel/'.$file->getClientOriginalName());

        //remove from server
		unlink('excel/'.$file->getClientOriginalName());
        return Redirect::to("/transaksi/withdraw");
        // if($import) {
        //     return Redirect::to("/transaksi/withdraw")->withSuccess('Data Withdraw Berhasil di Upload');
        // } else {
        //     return Redirect::to("/transaksi/withdraw")->withError('Error');
        // }
    }
}
