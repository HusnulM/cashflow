<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Validator,Redirect,Response;
use DB;

class UserController extends Controller
{
    public function index(){
        $data = DB::table('users')->get();
        return view('settings.user.index', ['datauser' => $data]);
    }

    public function create(){
        return view('settings.user.create');
    }

    public function edit($id){
        $data = DB::table('users')->where('id', $id)->first();
        return view('settings.user.edit', ['datauser' => $data]);
    }

    public function list(){
        $data['data'] = DB::table('users')
                        ->get();
        return json_encode($data);
    }

    public function save(Request $request){
        $validated = $request->validate([
            'email'    => 'required|unique:users|max:255',
            'name'     => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        $options = [
            'cost' => 12,
        ];
        $password = password_hash($request['password'], PASSWORD_BCRYPT, $options);

        $output = array();

        DB::beginTransaction();
        try{
            DB::table('users')->insert([
                'name'        => $request['name'],
                'email'       => $request['email'],
                'username'    => $request['username'],
                'password'    => $password
            ]);

            DB::commit();
            return Redirect::to("/setting/users")->withSuccess('User berhasil ditambahkan');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/setting/users")->withError($e->getMessage());
        }
    }

    public function update(Request $request){
        $validated = $request->validate([
            'email'    => 'required|max:255',
            'name'     => 'required',
            'username' => 'required',
        ]);

        
        DB::beginTransaction();
        try{
            if(isset($request['password'])){
                $options = [
                    'cost' => 12,
                ];
                $password = password_hash($request['password'], PASSWORD_BCRYPT, $options);
        
                $output = array();
    
                DB::table('users')->where('id',$request['iduser'])->update([
                    'name'        => $request['name'],
                    'email'       => $request['email'],
                    'username'    => $request['username'],
                    'password'    => $password
                ]);
            }else{
                DB::table('users')->where('id',$request['iduser'])->update([
                    'name'        => $request['name'],
                    'email'       => $request['email'],
                    'username'    => $request['username']
                ]);
            }

            DB::commit();
            return Redirect::to("/setting/users")->withSuccess('User berhasil ditambahkan');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/setting/users")->withError($e->getMessage());
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            DB::table('users')->where('id', $id)->delete();

            DB::commit();
            return Redirect::to("/setting/users")->withSuccess('User dihapus');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/setting/users")->withError($e->getMessage());
        }
    }
}
