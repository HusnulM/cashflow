<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Validator,Redirect,Response;

class HomeController extends Controller
{
    public function index(){
        if(Auth::check()){
            return redirect()->intended('dashboard');
        }
        return view('login');
        // return view('dashboard');
    }

    public function dashboard(){
        return view('dashboard');
    }

    public function login(Request $request){
        $input = $request->all();
        $request->validate([
            'username' => 'required',
            'password' => 'required|string'
        ]);

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $options = [
            'cost' => 12,
        ];
        $password = password_hash($request['password'], PASSWORD_BCRYPT, $options);

        // $credentials = $request->only('email', 'password');

        // $dataAttempt = array(
        //     'email'    => strtolower($request['email']),
        //     'password' => $request['password']
        // );

        if (Auth::attempt(array($fieldType => $input['username'], 'password' => $input['password']))) {
            if(Auth::user()->deletion_flag === "X"){
                Auth::logout();
                return Redirect::back()
                ->withErrors(
                    [
                        'error' => 'Opps! Your account is deleted, please contact System Administrator',
                    ]
                );
            }elseif(Auth::user()->request_change_acc === "Y"){
                Auth::logout();
                return Redirect::back()
                ->withErrors(
                    [
                        'error' => 'Your account temporarily is inactive, your data changes are being verified',
                    ]
                );
            }else{
                return redirect()->intended('dashboard');
            }
        }else{
            return Redirect::back()
                ->withErrors(
                    [
                        'error' => 'Opps! You have entered invalid credentials',
                    ]
                );
        }
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function home(){
        if(Auth::check()){
            $nama = $nama = Auth::user()->name;
            return view('home.index',['nama' => $nama]);
        }
        return Redirect::to("login")->withErrors('Opps! You do not have access');
    }

    public function user(){
        return view('configuration.user.index');
    }

    public function createuser(){
        return view('configuration.user.create');
    }
}
