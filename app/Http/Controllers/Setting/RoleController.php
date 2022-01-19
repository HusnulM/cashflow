<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Validator,Redirect,Response;
use DB;

class RoleController extends Controller
{
    public function index(){
        $data = DB::table('roles')->get();
        return view('settings.role.index', ['datarole' => $data]);
    }

    public function create(){
        $data = DB::table('roles')->get();
        return view('settings.role.create');
    }

    public function list(){
        if(Auth::check()){
            $data['data'] = DB::table('roles')
                    ->get();
            return json_encode($data);
        }
    }

    public function save(Request $request){
        $validated = $request->validate([
            'rolename' => 'required|unique:roles|max:255'
        ]);

        DB::beginTransaction();
        try{
            DB::table('roles')->insert([
                'rolename' => $request['rolename']
            ]);

            DB::commit();
            return Redirect::to("/setting/roles")->withSuccess('Role ditambahkan');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/setting/roles")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            DB::table('roles')->where('id', $id)->delete();

            DB::commit();
            return Redirect::to("/setting/roles")->withSuccess('Role dihapus');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/setting/roles")->withError($e->getMessage());
        }
    }
}
