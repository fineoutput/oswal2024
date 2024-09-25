<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Promocode;

class PromocodeController extends Controller
{

    public function index()
    {

        $promocodes = Promocode::orderby('id', 'desc')->get();

        return view('admin.Promocode.view-promocode', compact('promocodes'));
    }

    public function create(Request $request, $id = null)

    {
        $promocode = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('promocode.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $promocode = Promocode::find(base64_decode($id));
            
        }

        return view('admin.Promocode.add-promocode', compact('promocode'));
    }

    public function store(Request $request)

    {
        // dd($request->for_admin);

        $rules = [
            'promocode'             => 'required|string',
            'percent'               => 'required|integer',
            'type'                  => 'required|integer',
            'minimum_amount'        => 'required|integer',
            'maximum_gift_amount'   => 'required|integer',
            'for_admin'             => 'nullable|boolean',
            'expiry_date'           => 'required|date',
        ];
        
        $request->validate($rules);

        if (!isset($request->promocode_id)) {

            $promocode = new Promocode;

        } else {

            $promocode = Promocode::find($request->promocode_id);
            
            if (!$promocode) {
                
                return redirect()->route('promocode.index')->with('error', 'promocode not found.');
                
            }

        }
        
        $promocode->fill($request->all());

        if($request->for_admin == null){

            $promocode->for_admin = 0;

        }else{

            $promocode->for_admin = $request->for_admin;
        }

        $promocode->ip = $request->ip();

        $promocode->date = now();

        $promocode->added_by = Auth::user()->id;

        $promocode->is_active = 1;

        if ($promocode->save()) {

            $message = isset($request->promocode_id) ? 'promocode updated successfully.' : 'promocode inserted successfully.';

            return redirect()->route('promocode.index')->with('success', $message);

        } else {

            return redirect()->route('promocode.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $promocode = Promocode::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $promocode->updateStatus(strval(1));
        } else {

            $promocode->updateStatus(strval(0));
        }

        return  redirect()->route('promocode.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('promocode.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Promocode::where('id', $id)->delete()) {

            return  redirect()->route('promocode.index')->with('success', 'promocode Deleted Successfully.');
        } else {
            return redirect()->route('promocode.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('promocode.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}