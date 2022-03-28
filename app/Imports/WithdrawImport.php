<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
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
                
                $insertData = array();
                $excelData = array(
                    'idplayer'     => $row['id_player'] ?? '',
                    'playername'   => $playerData->playername ?? '',
                    'amount'       => $row['jumlah_withdraw'] ?? '',
                    'biaya_adm'    => $row['biaya_admin'] ?? 0,
                    'wdpdate'      => $row['tanggal_withdraw'] ?? '',
                    'wd_status'    => 'Open',
                    'rekening_sumber' => $row['rekening_sumber_dana'] ?? '',
                    'createdby'    => Auth::user()->name,
                    'created_at'   => now()
                );
                array_push($insertData, $excelData);
                insertOrUpdate($insertData,'withdraws');
                
                DB::commit();     
            }
        }catch(\Exception $e){
            DB::rollBack();
        }
    }
}
