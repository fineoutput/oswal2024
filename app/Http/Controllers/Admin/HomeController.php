<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function career()
    {

        $careers = DB::table('careers')->orderby('id', 'desc')->get();

        return view('admin.career', compact('careers'));
    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

            if (DB::table('careers')->where('id', $id)->delete()) {

                return  redirect()->route('home.index')->with('success', 'Message Deleted Successfully.');

            } else {

                return redirect()->route('home.index')->with('error', 'Some Error Occurred.');

            }

        // } else {

        // 	return  redirect()->route('contact-us.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}