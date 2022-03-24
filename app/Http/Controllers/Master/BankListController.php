<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB,Auth,Validator,Redirect,Response;

class BankListController extends Controller
{
    public function index(){
        $data = DB::table('bank_lists')->get();
        return view('master.banklist.index', ['data' => $data]);
    }

    public function create(){
        return view('master.banklist.create');
    }

    public function edit($id){
        $data = DB::table('bank_lists')->where('bankid', $id)->first();
        return view('master.banklist.edit', ['data' => $data]);
    }

    public function save(Request $request){
        DB::beginTransaction();
        try{
            $input = array();
            $insertData = array(
                'bankid'           => $request['kodebank'],
                'deskripsi'        => $request['namabank'],
                'createdby'        => Auth::user()->name,
                'createdon'        => now()
            );
            array_push($input, $insertData);
            insertOrUpdate($input,'bank_lists');

            DB::commit();
            return Redirect::to("/master/banklist")->withSuccess('Master List Bank ditambahkan');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/banklist")->withError($e->getMessage());
        }
    }

    public function update(Request $request){
        DB::beginTransaction();
        try{
            DB::table('bank_lists')->where('bankid', $request['kodebank'])->update([
                'deskripsi'        => $request['namabank']
            ]);
            
            DB::commit();
            return Redirect::to("/master/banklist")->withSuccess('Master List Bank diubah');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/banklist")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            DB::table('bank_lists')->where('bankid', $id)->delete();
            DB::commit();
            return Redirect::to("/master/banklist")->withSuccess('Master List Bank dihapus');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/banklist")->withError($e->getMessage());
        }
    }
}
