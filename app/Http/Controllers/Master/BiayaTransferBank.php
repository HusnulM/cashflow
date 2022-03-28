<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB,Auth,Validator,Redirect,Response;

class BiayaTransferBank extends Controller
{
    public function index(){
        $data = DB::table('biaya_adm_tf')->get();
        return view('master.biaya_transfer.index', ['data' => $data]);
    }

    public function create(){
        $data = DB::table('bank_lists')->get();
        return view('master.biaya_transfer.create', ['data' => $data]);
    }

    public function edit($p1, $p2){
        $biaya = DB::table('biaya_adm_tf')->where('bank_asal', $p1)->where('bank_tujuan', $p2)->first();
        
        $bankAsal   = DB::table('bank_lists')->where('bankid', $p1)->first();
        $bankTujuan = DB::table('bank_lists')->where('bankid', $p2)->first();
        // return $biaya;
        $data = DB::table('bank_lists')->get();
        return view('master.biaya_transfer.edit', ['data' => $data, 'asal' => $bankAsal, 'tujuan' => $bankTujuan, 'biaya' => $biaya]);
    }

    public function save(Request $request){
        DB::beginTransaction();
        try{
            $input = array();
            $insertData = array(
                'bank_asal'        => $request['bank_asal'],
                'bank_tujuan'      => $request['bank_tujuan'],
                'biaya_adm'        => $request['biaya_adm']
                // 'createdby'        => Auth::user()->name,
                // 'createdon'        => now()
            );
            array_push($input, $insertData);
            insertOrUpdate($input,'biaya_adm_tf');

            DB::commit();
            return Redirect::to("/master/biayaadmin")->withSuccess('Master Biaya Transfer Bank ditambahkan');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/biayaadmin")->withError($e->getMessage());
        }
    }

    public function update(Request $request){
        DB::beginTransaction();
        try{
            DB::table('biaya_adm_tf')
                ->where('bank_asal',   $request['bank_asal'])
                ->where('bank_tujuan', $request['bank_tujuan'])
            ->update([
                'biaya_adm'        => $request['biaya_adm']
            ]);
            
            DB::commit();
            return Redirect::to("/master/biayaadmin")->withSuccess('Master Biaya Transfer Bank diubah');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/biayaadmin")->withError($e->getMessage());
        }
    }

    public function delete($p1,$p2){
        DB::beginTransaction();
        try{
            DB::table('biaya_adm_tf')->where('bank_asal', $p1)->where('bank_tujuan', $p2)->delete();
            DB::commit();
            return Redirect::to("/master/biayaadmin")->withSuccess('Master Biaya Transfer Bank dihapus');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/biayaadmin")->withError($e->getMessage());
        }
    }
}
