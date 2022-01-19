<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator,Redirect,Response;
use DB;

class MenuRoleController extends Controller
{
    public function index(){
        $data = DB::table('v_menuroles')
        ->orderBy('roleid',    'asc')
        ->orderBy('menugroup', 'asc')
        ->orderBy('menuid',    'asc')
        ->get();
        return view('settings.menurole.index', ['data' => $data]);
    }

    public function create(){
        $data = DB::table('menugroups')->get();
        return view('settings.menurole.create', ['groups' => $data]);
    }

    public function save(Request $request){
        DB::beginTransaction();
        try{
            $output = array();
            $menuid = $request['itm_idmenu'];
            for($i = 0; $i < sizeof($menuid); $i++){
                $menuroledata = array(
                    'menuid'    => $menuid[$i],
                    'roleid'    => $request['roleid']
                );
                array_push($output, $menuroledata);
            }
            insertOrUpdate($output,'menuroles');
            DB::commit();
            return Redirect::to("/setting/menuroles")->withSuccess('New Menu Role Created');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/setting/menuroles")->withError($e->getMessage());
        }
    }
}
