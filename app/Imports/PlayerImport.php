<?php

namespace App\Imports;

use App\Models\Player;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PlayerImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     $data = Player::updateOrCreate(
    //         [
    //             'playerid'      => $row[0] ?? '',
    //             'playername'    => $row[1] ?? '',
    //             'bankname'      => $row[2] ?? '',
    //             'bankacc'       => $row[3] ?? '',
    //             'afiliator'     => $row[4] ?? '',
    //             'created_at'    => date('Y-m-d H:i:s')
    //         ]
    //     );

    //     return $data;
    // }
    public function collection(Collection $rows)
    {
        
        foreach ($rows as $index => $row) {
            // dd($row);
            // Player::updateOrCreate(
            //     // [
            //     // ],
            //     [
            //         'playerid'      => $row['id_player'] ?? '',
            //         'playername'    => $row['nama_player'] ?? '',
            //         'bankname'      => $row['nama_bank'] ?? '',
            //         'bankacc'       => $row['nomor_rekening'] ?? '',
            //         'afiliator'     => $row['player_afiliator'] ?? '',
            //         'created_at'    => date('Y-m-d H:i:s')
            //     ]
            // );
            $insertData = array();
            $excelData = array(
                'playerid'      => $row['id_player'] ?? '',
                'playername'    => $row['nama_player'] ?? '',
                'bankid'        => $row['kode_bank'] ?? '',
                'bankname'      => $row['nama_bank'] ?? '',
                'bankacc'       => $row['nomor_rekening'] ?? '',
                'afiliator'     => $row['player_afiliator'] ?? '',
                'created_at'    => date('Y-m-d H:i:s')
            );
            array_push($insertData, $excelData);
            insertOrUpdate($insertData,'players');
        }
    }

    // public function headingRow(): int
    // {
    //     return 2;
    // }
}
