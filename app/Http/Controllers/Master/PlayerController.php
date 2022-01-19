<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use DB;

class PlayerController extends Controller
{
    public function index(){
        $data = DB::table('players')->get();
        return view('master.player.index', ['data' => $data]);
    }

    public function create(){
        return view('master.player.create');
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
                'bankacc'    => $request['norek']
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
                'bankacc'    => $request['norek']
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
}
