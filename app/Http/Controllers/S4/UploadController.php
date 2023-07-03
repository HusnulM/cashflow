<?php

namespace App\Http\Controllers\S4;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class UploadController extends Controller
{
    public function listFile(){
        $data = DB::table('s4doc')->get();
        $result = array(
            'msgtype' => '200',
            'data'    => $data
        ); 

        return $result;
    }

    public function save(Request $req)
    {
        DB::beginTransaction();
        try{
            if(isset($req['efile'])){
                $efile    = $req['efile'];
                $filename = $efile->getClientOriginalName();
    
                // s4doc
                DB::table('s4doc')->insert([
                    'filename'      => $filename,
                    'pathfile'      => '/efiles/S4/'.$filename,
                    'createdby'     => 'sys-admin',
                    'createdon'     => date('Y-m-d H:m:s')
                ]);
                
                $efile->move('efiles/S4/', $filename); 

                DB::commit();
                $result = array(
                    'msgtype' => '200',
                    'message' => 'Berhasil upload file'
                );                
            }else{
                $result = array(
                    'msgtype' => '400',
                    'message' => 'Gagal upload file'
                );
            }
            return $result;
        }catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '400',
                'message' => 'Gagal upload file'
            );
            return $result;
        }
    }
}
