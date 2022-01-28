<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use DB;
use Auth;

class PindahDanaController extends Controller
{
    public function index(){
        $bank = DB::table('banks')->get();
        return view('transactions.transfer.index', ['bank' => $bank]);
    }

    public function save(Request $request){
        // return $request;
        DB::beginTransaction();
        try{
            
            $transferData = array();
            $insertData = array(
                'tgl_transfer'    => $request['tglTransfer'],
                'rekening_asal'   => $request['rekAsal'],
                'rekening_tujuan' => $request['rekTujuan'],
                'jml_transfer'    => $request['jmlTransfer'],
                'biaya_transfer'  => $request['biayaTransfer'],
                'keterangan'      => $request['note'],
                'createdby'       => Auth::user()->name,
                'created_at'      => date('Y-m-d H:i:s')
            );
            array_push($transferData, $insertData);
            insertOrUpdate($transferData,'transfers');

            $rekTujuan = DB::table('banks')->where('bank_accountnumber', $request['rekTujuan'])->first();
            $rekAsal   = DB::table('banks')->where('bank_accountnumber', $request['rekAsal'])->first();

            $latestSaldo = 0;
            $latestSaldoRekTujuan = 0;
            $saldoRekAsal = DB::table('cashflows')->where('to_acc',$request['rekAsal'])->limit(1)->orderBy('id','DESC')->first();
            if($saldoRekAsal){
                $latestSaldo = $saldoRekAsal->balance;
            }

            $saldoRekTujuan = DB::table('cashflows')->where('to_acc',$request['rekTujuan'])->limit(1)->orderBy('id','DESC')->first();
            if($saldoRekTujuan){
                $latestSaldoRekTujuan = $saldoRekTujuan->balance;
            }

            $biayaTransfer = 0;
            if(isset($request['biayaTransfer'])){
                $biayaTransfer = $request['biayaTransfer'];
            }

            // Insert Mutasi Pengeluaran dari rekening asal
            $castFlow = array();
            $insertcastFlow = array(
                'transdate'     => now(),
                'note'          => 'Transfer ke '. $rekTujuan->bankname . ' '. $rekTujuan->bank_accountname . ' - ' . $rekTujuan->bank_accountnumber,
                'from_acc'      => '',
                'to_acc'        => $request['rekAsal'],
                'debit'         => $request['jmlTransfer'],
                'credit'        => 0,
                'balance'       => $latestSaldo-$request['jmlTransfer'],
                'createdby'     => Auth::user()->name,
                'created_at'    => now()
            );
            array_push($castFlow, $insertcastFlow);
            insertOrUpdate($castFlow,'cashflows');

            if($biayaTransfer > 0){
                $castFlow = array();
                $insertcastFlow = array(
                    'transdate'     => now(),
                    'note'          => 'Biaya Transfer ke '. $rekTujuan->bankname . ' '. $rekTujuan->bank_accountname . ' - ' . $rekTujuan->bank_accountnumber,
                    'from_acc'      => '',
                    'to_acc'        => $request['rekAsal'],
                    'debit'         => $biayaTransfer,
                    'credit'        => 0,
                    'balance'       => $latestSaldo-$request['jmlTransfer']-$biayaTransfer,
                    'createdby'     => Auth::user()->name,
                    'created_at'    => now()
                );
                array_push($castFlow, $insertcastFlow);
                insertOrUpdate($castFlow,'cashflows');
            }

            // Insert Mutasi Pemasukan ke rekening Tujuan
            $castFlow = array();
            $insertcastFlow = array(
                'transdate'     => now(),
                'note'          => 'Terima dari '. $rekAsal->bankname . ' '. $rekAsal->bank_accountname . ' - ' . $rekAsal->bank_accountnumber,
                'from_acc'      => '',
                'to_acc'        => $request['rekTujuan'],
                'debit'         => 0,
                'credit'        => $request['jmlTransfer'],
                'balance'       => $latestSaldoRekTujuan+$request['jmlTransfer'],
                'createdby'     => Auth::user()->name,
                'created_at'    => now()
            );
            array_push($castFlow, $insertcastFlow);
            insertOrUpdate($castFlow,'cashflows');

            DB::commit();
            
            return Redirect::to("/transaksi/transfer")->withSuccess('Transfer Dana Berhasil');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/transfer")->withError($e->getMessage());
        }
    }
}
