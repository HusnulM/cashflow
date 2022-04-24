<?php

namespace App\Imports;

use App\Models\Deposit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Validator,Redirect,Response;
use DB;
use Auth;

class DepositImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        // dd($rows);
        DB::beginTransaction();
        try{
            foreach ($rows as $index => $row) {
                $playerData = DB::table('players')->where('playerid', $row['id_player'])->first();

                $stock = DB::table('stock_coins')->where('id', '1')->first();
                if($stock->quantity < ($row['jumlah_deposit']+$row['bonus_deposit'] ?? 0)){
                    return Redirect::to("/transaksi/deposit")->withError('Stock Coin tidak mencukupi');
                }else{

                    $stockCoint = $stock->quantity - ( $row['jumlah_deposit']+$row['bonus_deposit'] ?? 0 );

                    $insertData = array();
                    $excelData = array(
                        'idplayer'     => $row['id_player'] ?? '',
                        'playername'   => $playerData->playername ?? '',
                        // 'playername'   => $row['nama_player'] ?? '',
                        'amount'       => $row['jumlah_deposit'] ?? '',
                        'topup_bonus'  => $row['bonus_deposit'] ?? 0,
                        'topupdate'    => $row['tanggal_deposit'] ?? '',
                        'topup_status' => 'Close',
                        'rekening_tujuan' => $row['rekening_tujuan_pembayaran'] ?? '',
                        'createdby'    => Auth::user()->name,
                        'created_at'   => now()
                    );
                    array_push($insertData, $excelData);
                    insertOrUpdate($insertData,'topups');

                    DB::table('stock_coins')->where('id', '1')->update([
                        'quantity' => $stockCoint,
                        'updatedon'=> date('Y-m-d')
                    ]);
    
                    $latestSaldo = 0;
                    $saldo = DB::table('cashflows')->where('to_acc',$row['rekening_tujuan_pembayaran'])->limit(1)->orderBy('id','DESC')->first();
                    if($saldo){
                        $latestSaldo = $saldo->balance;
                    }
        
                    $castFlow = array();
                    $insertcastFlow = array(
                        'transdate'     => now(),
                        'note'          => 'Deposit player '. $row['id_player'],
                        'from_acc'      => '',
                        'to_acc'        => $row['rekening_tujuan_pembayaran'],
                        'debit'         => 0,
                        'credit'        => $row['jumlah_deposit'],
                        'balance'       => $row['jumlah_deposit']+$latestSaldo,
                        'createdby'     => Auth::user()->name,
                        'created_at'    => now()
                    );
                    array_push($castFlow, $insertcastFlow);
                    insertOrUpdate($castFlow,'cashflows');

                    DB::commit();  // all good
                }
            }
            
            return Redirect::to("/transaksi/deposit")->withSuccess('Data Deposit Berhasil di Upload');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/transaksi/deposit")->withError($e->getMessage());
        }
        
    }
}
