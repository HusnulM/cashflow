<?php

namespace App\Imports;

use App\Models\Deposit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
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
        foreach ($rows as $index => $row) {
            $playerData = DB::table('players')->where('playerid', $row['id_player'])->first();

            $insertData = array();
            $excelData = array(
                'idplayer'     => $row['id_player'] ?? '',
                'playername'   => $playerData->playername ?? '',
                // 'playername'   => $row['nama_player'] ?? '',
                'amount'       => $row['jumlah_deposit'] ?? '',
                'topup_bonus'  => $row['bonus_deposit'] ?? 0,
                'topupdate'    => $row['tanggal_deposit'] ?? '',
                'topup_status' => 'Open',
                'rekening_tujuan' => $row['rekening_tujuan_pembayaran'] ?? '',
                'createdby'    => Auth::user()->name,
                'created_at'   => now()
            );
            array_push($insertData, $excelData);
            insertOrUpdate($insertData,'topups');
        }
    }
}
