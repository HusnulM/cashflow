<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator,Redirect,Response;
use DB;

class MenuGroupController extends Controller
{
    public function index(){
        $data = DB::table('menugroups')->get();
        return view('settings.menugroup.index', ['data' => $data]);
    }

    public function create(){
        return view('settings.menugroup.create');
    }

    public function edit($id){
        $data = DB::table('menugroups')->where('id', $id)->first();
        return view('settings.menugroup.edit', ['data' => $data]);
    }

    public function save(Request $request){
        DB::beginTransaction();
        try{
            DB::table('menugroups')->insert([
                'description'  => $request['groupname'],
                'groupicon'    => $request['groupicon'],
                '_index'       => $request['gropindex']
            ]);

            DB::commit();
            return Redirect::to("/setting/menugroups")->withSuccess('Menu Group ditambahkan');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/setting/menugroups")->withError($e->getMessage());
        }
    }

    public function update(Request $request){
        DB::beginTransaction();
        try{
            DB::table('menugroups')->where('id', $request['idgroup'])->update([
                'description'  => $request['groupname'],
                'groupicon'    => $request['groupicon'],
                '_index'       => $request['gropindex']
            ]);

            DB::commit();
            return Redirect::to("/setting/menugroups")->withSuccess('Menu Group diubah');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/setting/menugroups")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            DB::table('menugroups')->where('id', $id)->delete();

            DB::commit();
            return Redirect::to("/setting/menugroups")->withSuccess('Menu Group dihapus');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/setting/menugroups")->withError($e->getMessage());
        }
    }
}
