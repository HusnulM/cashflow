<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use DB;
use Auth;

class BankController extends Controller
{
    public function index(){
        $data = DB::table('v_banks')->get();
        return view('master.bank.index', ['data' => $data]);
    }

    public function create(){
        return view('master.bank.create');
    }

    public function edit($id){
        $data = DB::table('v_banks')->where('id', $id)->first();
        return view('master.bank.edit', ['data' => $data]);
    }

    public function save(Request $request){
        DB::beginTransaction();
        try{
            $output = array();
            $menuroledata = array(
                'bankid'             => $request['kodebank'],
                'bankname'           => $request['namabank'],
                'bank_accountnumber' => $request['norek'],
                'bank_accountname'   => $request['atasnama'],
                'bank_type'          => $request['tipebank'],
                'opening_balance'    => $request['saldoawal'],
                'createdby'          => Auth::user()->name,
                'created_at'         => now()
            );
            array_push($output, $menuroledata);
            insertOrUpdate($output,'banks');

            $saldoAwal = array();
            $insertSaldo = array(
                'transdate'     => now(),
                'note'          => 'Saldo awal',
                'from_acc'      => '',
                'to_acc'        => $request['norek'],
                'debit'         => 0,
                'credit'        => $request['saldoawal'],
                'balance'       => $request['saldoawal'],
                'createdby'     => Auth::user()->name,
                'created_at'    => now()
            );
            array_push($saldoAwal, $insertSaldo);
            insertOrUpdate($saldoAwal,'cashflows');

            DB::commit();
            return Redirect::to("/master/bank")->withSuccess('Master Bank ditambahkan');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/bank")->withError($e->getMessage());
        }
    }

    public function createSaldoAwal($data){

    }

    public function update(Request $request){
        DB::beginTransaction();
        try{
            DB::table('banks')->where('id', $request['idbank'])->update([
                'bankid'             => $request['kodebank'],
                'bankname'           => $request['namabank'],
                'bank_accountnumber' => $request['norek'],
                'bank_accountname'   => $request['atasnama'],
                'bank_type'          => $request['tipebank'],
                'opening_balance'    => $request['saldoawal'],
                'updated_at'         => now()
            ]);

            $saldoAwal = array();
            $insertSaldo = array(
                'transdate'     => now(),
                'note'          => 'Saldo awal',
                'from_acc'      => '',
                'to_acc'        => $request['norek'],
                'debit'         => 0,
                'credit'        => $request['saldoawal'],
                'balance'       => $request['saldoawal'],
                'createdby'     => Auth::user()->name,
                'created_at'    => now()
            );
            array_push($saldoAwal, $insertSaldo);
            insertOrUpdate($saldoAwal,'cashflows');
            
            DB::commit();
            return Redirect::to("/master/bank")->withSuccess('Master Bank diubah');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/bank")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            DB::table('banks')->where('id', $id)->delete();
            DB::commit();
            return Redirect::to("/master/bank")->withSuccess('Master Bank dihapus');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/bank")->withError($e->getMessage());
        }
    }
}
