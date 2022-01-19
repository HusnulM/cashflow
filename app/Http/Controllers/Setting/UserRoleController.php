<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator,Redirect,Response;
use DB;

class UserRoleController extends Controller
{
    public function index(){
        $data = DB::table('v_userroles')->get();
        return view('settings.userrole.index', ['data' => $data]);
    }

    public function create(){
        $data = DB::table('menugroups')->get();
        return view('settings.userrole.create', ['groups' => $data]);
    }

    public function save(Request $request){
        // return $request;
        DB::beginTransaction();
        try{
            $output = array();
            $roleid = $request['itm_roleid'];
            for($i = 0; $i < sizeof($roleid); $i++){
                $menuroledata = array(
                    'userid'    => $request['userid'],
                    'roleid'    => $roleid[$i]
                );
                array_push($output, $menuroledata);
            }
            insertOrUpdate($output,'userroles');
            DB::commit();
            return Redirect::to("/setting/userroles")->withSuccess('New User Role Created');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/setting/userroles")->withError($e->getMessage());
        }
    }

    public function delete($id, $role){
        DB::beginTransaction();
        try{
            DB::table('userroles')->where('userid', $id)->where('roleid', $role)->delete();
            DB::commit();
            return Redirect::to("/setting/userroles")->withSuccess('User Role Deleted');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/setting/userroles")->withError($e->getMessage());
        }
    }
}
