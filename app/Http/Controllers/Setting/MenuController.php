<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use DB;

class MenuController extends Controller
{
    public function index(){
        $data = DB::table('v_menus')->get();
        return view('settings.menu.index', ['data' => $data]);
    }

    public function create(){
        $data = DB::table('menugroups')->get();
        return view('settings.menu.create', ['groups' => $data]);
    }

    public function list(){
        $data['data'] = DB::table('v_menus')
                        ->get();
        return json_encode($data);
    }

    public function save(Request $request){
        DB::beginTransaction();
        try{
            DB::table('menus')->insert([
                'name'      => $request['menuname'],
                'route'     => $request['menuroute'],
                'menugroup' => $request['menugroup']
            ]);

            DB::commit();
            return Redirect::to("/setting/menus")->withSuccess('Menu ditambahkan');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/setting/menus")->withError($e->getMessage());
        }
    }

    public function update(Request $request){
        DB::beginTransaction();
        try{
            DB::table('menus')->where('id', $request['menuid'])->update([
                'name'      => $request['menuname'],
                'route'     => $request['menuroute'],
                'menugroup' => $request['menugroup']
            ]);

            DB::commit();
            return Redirect::to("/setting/menus")->withSuccess('Menu diubah');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/setting/menus")->withError($e->getMessage());
        }
    }
}
