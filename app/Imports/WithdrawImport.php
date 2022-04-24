<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Validator,Redirect,Response;
use App\Models\Withdraw;
use DB;
use Auth;

class WithdrawImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try{
            foreach ($rows as $index => $row) {
                $playerData = DB::table('players')->where('playerid', $row['id_player'])->first();

                $latestSaldo = 0;
                $saldo = DB::table('cashflows')->where('to_acc',$row['rekening_sumber_dana'])->limit(1)->orderBy('id','DESC')->first();
                if($saldo){
                    $latestSaldo = $saldo->balance;
                }

                if($latestSaldo < ($row['jumlah_withdraw']+$row['biaya_admin'] ?? 0)){
                    DB::rollBack();
                    return Redirect::to("/transaksi/withdraw")->withError('Saldo Rek '. $row['rekening_sumber_dana'] . ' tidak mencukupi');
                }
                
                $insertData = array();
                $excelData = array(
                    'idplayer'     => $row['id_player'] ?? '',
                    'playername'   => $playerData->playername ?? '',
                    'amount'       => $row['jumlah_withdraw'] ?? '0',
                    'biaya_adm'    => $row['biaya_admin'] ?? 0,
                    'wdpdate'      => $row['tanggal_withdraw'] ?? '',
                    'wd_status'    => 'Close',
                    'rekening_sumber' => $row['rekening_sumber_dana'] ?? '',
                    'createdby'    => Auth::user()->name,
                    'created_at'   => now()
                );
                array_push($insertData, $excelData);
                insertOrUpdate($insertData,'withdraws');

                $castFlow = array();
                $insertcastFlow = array(
                    'transdate'     => now(),
                    'note'          => 'WD player '. $row['id_player'],
                    'from_acc'      => '',
                    'to_acc'        => $row['rekening_sumber_dana'],
                    'debit'         => $row['jumlah_withdraw'],
                    'credit'        => 0,
                    'balance'       => $latestSaldo-$row['jumlah_withdraw'],
                    'createdby'     => Auth::user()->name,
                    'created_at'    => now()
                );
                array_push($castFlow, $insertcastFlow);
                insertOrUpdate($castFlow,'cashflows');

                if($row['biaya_admin'] ?? 0 > 0){
                    $castFlow = array();
                    $insertcastFlow = array(
                        'transdate'     => now(),
                        'note'          => 'Biaya Admin WD player '. $row['id_player'],
                        'from_acc'      => '',
                        'to_acc'        => $row['rekening_sumber_dana'],
                        'debit'         => $row['biaya_admin'] ?? 0,
                        'credit'        => 0,
                        'balance'       => $latestSaldo-$row['jumlah_withdraw']-$row['biaya_admin'] ?? 0,
                        'createdby'     => Auth::user()->name,
                        'created_at'    => now()
                    );
                    array_push($castFlow, $insertcastFlow);
                    insertOrUpdate($castFlow,'cashflows');
                }

                //Update Stock coin
                $stock = DB::table('stock_coins')->where('id', '1')->first();
                $stockCoint = $stock->quantity + $row['jumlah_withdraw'] ?? 0;
                DB::table('stock_coins')->where('id', '1')->update([
                    'quantity' => $stockCoint,
                    'updatedon'=> date('Y-m-d')
                ]);
                
                DB::commit();     
            }
            return Redirect::to("/transaksi/withdraw")->withSuccess('Data Withdraw Berhasil di Upload');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/withdraw")->withError($e->getMessage());
        }
    }
}
