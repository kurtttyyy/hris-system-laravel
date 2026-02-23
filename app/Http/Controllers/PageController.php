<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function display_login(){
        return view('login');
    }

    public function display_register(){
        return view('register');
    }
}
