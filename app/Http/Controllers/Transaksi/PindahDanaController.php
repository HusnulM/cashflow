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
        $bank = DB::table('v_banks')->get();
        return view('transactions.transfer.index', ['bank' => $bank]);
    }

    public function save(Request $request){
        // return $request;
        DB::beginTransaction();
        try{

            $rekTujuan = DB::table('banks')->where('bank_accountnumber', $request['rekTujuan'])->first();
            $rekAsal   = DB::table('banks')->where('bank_accountnumber', $request['rekAsal'])->first();

            $latestSaldo = 0;
            $latestSaldoRekTujuan = 0;
            $saldoRekAsal = DB::table('cashflows')->where('to_acc',$request['rekAsal'])->limit(1)->orderBy('id','DESC')->first();
            if($saldoRekAsal){
                $latestSaldo = $saldoRekAsal->balance;
            }

            if($latestSaldo < ($request['jmlTransfer'] + $request['biayaTransfer'])){
                DB::rollBack();
                return Redirect::to("/transaksi/transfer")->withError('Saldo Rek '. $request['rekAsal'] . ' tidak mencukupi');
            }

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

            // if(!isset($request['biayaTransfer'])){
            //     $request['biayaTransfer'] = 0;
            // }

            // if($request['biayaTransfer'] > 0){
            //     $castFlow = array();
            //     $insertcastFlow = array(
            //         'transdate'     => now(),
            //         'note'          => 'Biaya Admin Pindah Dana Ke '. $rekAsal->bankname . ' '. $rekAsal->bank_accountname . ' - ' . $rekAsal->bank_accountnumber,
            //         'from_acc'      => '',
            //         'to_acc'        => $rekAsal->bank_accountnumber,
            //         'debit'         => $request['biayaTransfer'],
            //         'credit'        => 0,
            //         'balance'       => $latestSaldo-( $request['jmlTransfer'] + $request['biayaTransfer'] ),
            //         'createdby'     => Auth::user()->name,
            //         'created_at'    => now()
            //     );
            //     array_push($castFlow, $insertcastFlow);
            //     insertOrUpdate($castFlow,'cashflows');
            // }

            // Update Stock Coin Dari Rekening Asal
            $stockCointAsal = 0;
            $bankAsalData  = DB::table('banks')->where('bank_accountnumber', $rekAsal->bank_accountnumber)->first();
            $totalcoinAsal = DB::table('coin_stocks')->where('bankcode', $bankAsalData->bankid)->where('bankacc', $rekAsal->bank_accountnumber)->first();
            if($totalcoinAsal){
                $stockCointAsal = $totalcoinAsal->totalcoin;
                DB::table('coin_stocks')->where('id', $totalcoinAsal->id)->update([
                    'totalcoin' => $stockCointAsal - ( $request['jmlTransfer'] + $request['biayaTransfer'] ),
                    'updated_at'   => date('Y-m-d H:i:s')
                ]);
            }

            // Update Stock Coin Ke Rekening Tujuan
            $stockCoint = 0;
            $bankData  = DB::table('banks')->where('bank_accountnumber', $rekTujuan->bank_accountnumber)->first();
            $totalcoin = DB::table('coin_stocks')->where('bankcode', $bankData->bankid)->where('bankacc', $rekTujuan->bank_accountnumber)->first();
            if($totalcoin){
                $stockCoint = $totalcoin->totalcoin;
                DB::table('coin_stocks')->where('id', $totalcoin->id)->update([
                    'totalcoin' => $stockCoint + $request['jmlTransfer'],
                    'updated_at'   => date('Y-m-d H:i:s')
                ]);
            }

            DB::commit();
            
            return Redirect::to("/transaksi/transfer")->withSuccess('Transfer Dana Berhasil');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/transfer")->withError($e->getMessage());
        }
    }
}
