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
        $banklist = DB::table('bank_lists')->get();
        return view('master.bank.index', ['data' => $data, 'banklist' => $banklist]);
    }

    public function create(){
        $banklist = DB::table('bank_lists')->get();
        return view('master.bank.create', ['banklist' => $banklist]);
    }

    public function edit($id){
        $data = DB::table('v_banks')->where('id', $id)->first();
        $banklist = DB::table('bank_lists')->get();
        $currenctBank = DB::table('bank_lists')->where('bankid', $data->bankid)->first();
        return view('master.bank.edit', ['data' => $data, 'banklist' => $banklist, 'currenctBank' => $currenctBank]);
    }

    public function save(Request $request){
        // return $request;
        DB::beginTransaction();
        try{

            $bankWD = 'N';
            $bankDepo = 'N';
            $bankPenampung = 'N';

            if(isset($request->cbWD)){
                $bankWD = 'Y';
            }

            if(isset($request->cbDepo)){
                $bankDepo = 'Y';
            }

            if(isset($request->cbPenampung)){
                $bankPenampung = 'Y';
            }

            $output = array();
            $menuroledata = array(
                'bankid'             => $request['kodebank'],
                'bankname'           => $request['namabank'],
                'bank_accountnumber' => $request['norek'],
                'bank_accountname'   => $request['atasnama'],
                'bank_type'          => null,
                'opening_balance'    => $request['saldoawal'],
                'bank_wd'            => $bankWD,
                'bank_depo'          => $bankDepo,
                'bank_penampung'     => $bankPenampung,
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
            $bankWD = 'N';
            $bankDepo = 'N';
            $bankPenampung = 'N';

            if(isset($request->cbWD)){
                $bankWD = 'Y';
            }

            if(isset($request->cbDepo)){
                $bankDepo = 'Y';
            }

            if(isset($request->cbPenampung)){
                $bankPenampung = 'Y';
            }
            DB::table('banks')->where('id', $request['idbank'])->update([
                'bankid'             => $request['kodebank'],
                'bankname'           => $request['namabank'],
                'bank_accountnumber' => $request['norek'],
                'bank_accountname'   => $request['atasnama'],
                'bank_type'          => null,
                // 'opening_balance'    => $request['saldoawal'],
                'bank_wd'            => $bankWD,
                'bank_depo'          => $bankDepo,
                'bank_penampung'     => $bankPenampung,
                'updated_at'         => now()
            ]);

            // $saldoAwal = array();
            // $insertSaldo = array(
            //     'transdate'     => now(),
            //     'note'          => 'Saldo awal',
            //     'from_acc'      => '',
            //     'to_acc'        => $request['norek'],
            //     'debit'         => 0,
            //     'credit'        => $request['saldoawal'],
            //     'balance'       => $request['saldoawal'],
            //     'createdby'     => Auth::user()->name,
            //     'created_at'    => now()
            // );
            // array_push($saldoAwal, $insertSaldo);
            // insertOrUpdate($saldoAwal,'cashflows');
            
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

    public function detail($bankacc){
        $data = DB::table('v_banks')->where('bank_accountnumber', $bankacc)->first();
        return $data;
    }

    public function getbiayaadm($from, $to){
        $data = DB::table('biaya_adm_tf')
                ->where('bank_asal',$from)
                ->where('bank_tujuan',$to)
                ->first();
        return $data;
    }
}
