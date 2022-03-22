<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Imports\PlayerImport;
use App\Models\Player;
use Validator,Redirect,Response;
use DB;
use Excel;
use Auth;

class PlayerController extends Controller
{
    public function index(){
        $data = DB::table('v_players')->get();
        return view('master.player.index', ['data' => $data]);
    }

    public function create(){
        return view('master.player.create');
    }

    public function upload(){
        return view('master.player.upload');
    }

    public function edit($id){
        $data = DB::table('players')->where('playerid', $id)->first();
        return view('master.player.edit', ['data' => $data]);
    }

    public function save(Request $request){
        DB::beginTransaction();
        try{
            $output = array();
            $menuroledata = array(
                'playerid'   => $request['idplayer'],
                'playername' => $request['namaplayer'],
                'bankname'   => $request['namabank'],
                'bankacc'    => $request['norek'],
                'afiliator'  => $request['afiliator']
            );
            array_push($output, $menuroledata);
            insertOrUpdate($output,'players');
            DB::commit();
            return Redirect::to("/master/player")->withSuccess('Master player ditambahkan');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/player")->withError($e->getMessage());
        }
    }

    public function update(Request $request){
        DB::beginTransaction();
        try{
            DB::table('players')->where('playerid', $request['idplayer'])->update([
                'playername' => $request['namaplayer'],
                'bankname'   => $request['namabank'],
                'bankacc'    => $request['norek'],
                'afiliator'  => $request['afiliator']
            ]);
            DB::commit();
            return Redirect::to("/master/player")->withSuccess('Master Player diubah');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/player")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            DB::table('players')->where('playerid', $id)->delete();
            DB::commit();
            return Redirect::to("/master/player")->withSuccess('Master Player dihapus');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/master/player")->withError($e->getMessage());
        }
    }

    public function importPlayer(Request $request){
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = $file->hashName();        

        $destinationPath = 'excel/';
        $file->move($destinationPath,$file->getClientOriginalName());

        config(['excel.import.startRow' => 2]);
        // import data
        $import = Excel::import(new PlayerImport(), 'excel/'.$file->getClientOriginalName());

        //remove from server
		unlink('excel/'.$file->getClientOriginalName());

        if($import) {
            return Redirect::to("/master/player")->withSuccess('Data Player Berhasil di Upload');
        } else {
            return Redirect::to("/master/player")->withError('Error');
        }
    }
}
