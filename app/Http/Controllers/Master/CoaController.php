<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use DB;
use Auth;

class CoaController extends Controller
{
    public function index(){
        $data = DB::table('chart_of_accounts')->orderBy('account', 'ASC')->get();
        return view('master.coa.index', ['data' => $data]);
    }

    public function create(){
        return view('master.coa.create');
    }

    public function edit($id){
        $data = DB::table('chart_of_accounts')->where('id', $id)->first();
        return view('master.coa.edit', ['data' => $data]);
    }

    public function save(Request $request){

        $validated = $request->validate([
            'account'      => 'required|unique:chart_of_accounts',
            'account_name' => 'required|unique:chart_of_accounts'
        ]);

        DB::beginTransaction();
        try{
            $output = array();
            $insertData = array(
                'account'          => $request['account'],
                'account_name'     => $request['account_name'],
                'account_ind'      => $request['account_ind'],
                'createdby'        => Auth::user()->name,
                'created_at'       => now()
            );
            array_push($output, $insertData);
            insertOrUpdate($output,'chart_of_accounts');
            DB::commit();
            return Redirect::to("/master/coa")->withSuccess('Master Coa ditambahkan');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/coa")->withError($e->getMessage());
        }
    }

    public function update(Request $request){
        DB::beginTransaction();
        try{
            DB::table('chart_of_accounts')->where('id', $request['idcoa'])->update([
                'account'          => $request['account'],
                'account_name'     => $request['account_name'],
                'account_ind'      => $request['account_ind']
            ]);
            DB::commit();
            return Redirect::to("/master/coa")->withSuccess('Master COA diubah');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/coa")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            DB::table('chart_of_accounts')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/master/coa")->withSuccess('Master COA dihapus');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/coa")->withError($e->getMessage());
        }
    }
}
