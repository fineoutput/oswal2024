<?php

namespace App\Http\Controllers\Frontend\Users;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function index(Request $request)
    {

        return view('Users.dashboard')->with('tittle' , 'Dashboard');
    }

}