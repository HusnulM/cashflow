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
        // $data = DB::table('players')->get();
        $bank = DB::table('banks')->get();
        return view('transactions.topupcoin.index', ['bank' => $bank]);
    }

    public function verify(){
        $data = DB::table('topups')->where('topup_status', 'Open')->get();
        return view('transactions.topupcoin.verify', ['data' => $data]);
    }

    public function save(Request $request){
        // return $request;
        DB::beginTransaction();
        try{
            // $destinationPath = 'efiles/topupfiles';
            // if(!File::exists($destinationPath)) {
            //     File::makeDirectory($destinationPath, 0755, true, true);
            // }

            // $output = array();
            // $playerid = $request['itm_idplayer'];
            // $nmayerid = $request['itm_nmplayer'];
            // $jmltopup = $request['itm_jmltopup'];
            // $tgltopup = $request['itm_tgltopup'];
            // $bontopup = $request['itm_jmlbonus'];
            // // $xfile    = $request->file('itm_efile');
            
            // for($i = 0; $i < sizeof($playerid); $i++){
            //     // $file = $xfile[$i];
                
            //     $insertData = array(
            //         'idplayer'     => $playerid[$i],
            //         'playername'   => $nmayerid[$i],
            //         'amount'       => $jmltopup[$i],
            //         'topup_bonus'  => $bontopup[$i],
            //         'topupdate'    => $tgltopup[$i],
            //         'topup_status' => 'Open',
            //         // 'efile'        => $file->getClientOriginalName(),
            //         'createdby'    => Auth::user()->name,
            //         'created_at'   => now()
            //     );
            //     array_push($output, $insertData);

            //     // if(!empty($file)){
            //     //     $file->move($destinationPath,$file->getClientOriginalName());
            //     // }
            // }
            // insertOrUpdate($output,'topups');
            
            $topupData = array();
            $insertData = array(
                'idplayer'     => $request->idplayer,
                'playername'   => $request->namaplayer,
                'amount'       => $request->jmltopup,
                'topup_bonus'  => $request->bonustopup,
                'topupdate'    => $request->tgltopup,
                'topup_status' => 'Open',
                'rekening_tujuan' => $request->rekening,
                'createdby'    => Auth::user()->name,
                'created_at'   => now()
            );
            array_push($topupData, $insertData);
            insertOrUpdate($topupData,'topups');

            $playerdata = array();
            $insertPlayer = array(
                'playerid'   => $request['idplayer'],
                'playername' => $request['namaplayer'],
                'bankname'   => $request['namabank'],
                'bankacc'    => $request['nomor_rek']
            );
            array_push($playerdata, $insertPlayer);
            insertOrUpdate($playerdata,'players');

            DB::commit();

            
            return Redirect::to("/transaksi/topup")->withSuccess('Data Topup Berhasil di input');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/topup")->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }

    public function close($id){
        DB::beginTransaction();
        try{
            DB::table('topups')->where('id', $id)->update([
                'topup_status' => 'Close',
                'updated_at'   => now()
            ]);

            $topupdata = DB::table('topups')->where('id', $id)->first();

            $latestSaldo = 0;
            $saldo = DB::table('cashflows')->where('to_acc',$topupdata->rekening_tujuan)->limit(1)->orderBy('id','DESC')->first();
            if($saldo){
                $latestSaldo = $saldo->balance;
            }

            $castFlow = array();
            $insertcastFlow = array(
                'transdate'     => now(),
                'note'          => 'Topup player '. $topupdata->idplayer,
                'from_acc'      => '',
                'to_acc'        => $topupdata->rekening_tujuan,
                'debit'         => 0,
                'credit'        => $topupdata->amount,
                'balance'       => $topupdata->amount+$latestSaldo,
                'createdby'     => Auth::user()->name,
                'created_at'    => now()
            );
            array_push($castFlow, $insertcastFlow);
            insertOrUpdate($castFlow,'cashflows');

            DB::commit();            
            return Redirect::to("/transaksi/topup/verify")->withSuccess('Topup di web game berhasil');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/topup/verify")->withError($e->getMessage());
        }
    }
}