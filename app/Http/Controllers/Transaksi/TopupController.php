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
        return view('transactions.topupcoin.index');
    }

    public function verify(){
        $data = DB::table('topups')->where('topup_status', 'Open')->get();
        return view('transactions.topupcoin.verify', ['data' => $data]);
    }

    public function save(Request $request){
        DB::beginTransaction();
        try{
            $destinationPath = 'efiles/topupfiles';
            if(!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $output = array();
            $playerid = $request['itm_idplayer'];
            $nmayerid = $request['itm_nmplayer'];
            $jmltopup = $request['itm_jmltopup'];
            $tgltopup = $request['itm_tgltopup'];
            $xfile    = $request->file('itm_efile');
            
            for($i = 0; $i < sizeof($playerid); $i++){
                $file = $xfile[$i];
                
                $insertData = array(
                    'idplayer'     => $playerid[$i],
                    'playername'   => $nmayerid[$i],
                    'amount'       => $jmltopup[$i],
                    'topupdate'    => $tgltopup[$i],
                    'topup_status' => 'Open',
                    'efile'        => $file->getClientOriginalName(),
                    'createdby'    => Auth::user()->name,
                    'created_at'   => now()
                );
                array_push($output, $insertData);

                if(!empty($file)){
                    $file->move($destinationPath,$file->getClientOriginalName());
                }
            }
            insertOrUpdate($output,'topups');
            DB::commit();

            
            return Redirect::to("/transaksi/topup")->withSuccess('Data Topup Berhasil di input');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/topup")->withError($e->getMessage());
        }
    }

    public function close($id){
        DB::beginTransaction();
        try{
            DB::table('topups')->where('id', $id)->update([
                'topup_status' => 'Close',
                'updated_at'   => now()
            ]);
            DB::commit();            
            return Redirect::to("/transaksi/topup/verify")->withSuccess('Topup di web game berhasil');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/topup/verify")->withError($e->getMessage());
        }
    }
}
