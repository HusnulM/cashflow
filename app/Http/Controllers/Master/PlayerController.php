<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
// use App\Traits\JsonResponser;
use App\Imports\PlayerImport;
use App\Models\Player;
use Validator,Redirect,Response;
use DB;
use Excel;
use Auth;
use DataTables;

class PlayerController extends Controller
{
    // use JsonResponser;

    public function index(){
        // $data = DB::table('v_players')->get();
        // return view('master.player.index', ['data' => $data]);
        return view('master.player.playerlist');
    }

    public function create(){
        $bank = DB::table('bank_lists')->get();
        return view('master.player.create', ['banklist' => $bank]);
    }

    public function upload(){
        return view('master.player.upload');
    }

    public function edit($id){
        $data = DB::table('players')->where('playerid', $id)->first();
        $bank = DB::table('bank_lists')->get();
        $oldbank = DB::table('bank_lists')->where('bankid', $data->bankid)->first();
        return view('master.player.edit', ['data' => $data, 'banklist' => $bank, 'oldbank' => $oldbank]);
    }

    public function searchByname(Request $request){
        // return $request->query('term');
        $data = DB::table('players')
        // ->select('playerid','name1')
        ->distinct()
        ->where('playerid', 'LIKE', '%'. $request->query('term') . '%')
        ->get();

        $output = array(
            'code'    => 200,
            'message' => 'Berhasil mengambil data',
            'data'    => $data
        );

        return $output;
        //return $this->successResponse('Berhasil mengambil data', $data);
        // return ('Berhasil mengambil data', $data);
        // return response('Berhasil mengambil data', 200)->header('Content-Type', 'application/json');
    }

    public function save(Request $request){
        DB::beginTransaction();
        try{
            $output = array();
            $menuroledata = array(
                'playerid'   => $request['idplayer'],
                'playername' => $request['namaplayer'],
                'bankid'     => $request['kodebank'],
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
                'bankid'     => $request['kodebank'],
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

    public function playerlist(Request $request){
        //to use parameter or variable sent from ajax view
        $params = $request->params;
        
        $whereClause = $params['sac'];



        $query = DB::table('v_players')->orderBy('playerid');
        

        return DataTables::queryBuilder($query)->toJson();
    }
}
