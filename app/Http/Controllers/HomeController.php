<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('dashboard');
    }

    public function user(){
        return view('configuration.user.index');
    }

    public function createuser(){
        return view('configuration.user.create');
    }
}
