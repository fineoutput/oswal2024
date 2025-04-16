<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RetailShop;

class RetailShopController extends Controller
{

    public function index()
    {

        $retailShops = RetailShop::orderby('id', 'desc')->get();

        return view('admin.RetailShop.view-shop', compact('retailShops'));
    }

    public function create(Request $request, $id = null)

    {
        $retailShop = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin" && $admin_position !== "Admin") {

                return redirect()->route('shop.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $retailShop = RetailShop::find(base64_decode($id));
            
        }

        return view('admin.RetailShop.add-shop', compact('retailShop'));
    }

    public function store(Request $request)

    {
        // dd($request->all());
        $rules = [
            'shop_name'       => 'required|string',
            'person_name'     => 'nullable|string',
            'address'         => 'required|string',
            'state'           => 'required|string',
            'city'            => 'required|string',
            'area'            => 'nullable|string',
            'pincode'         => 'required|integer',
            'phone1'          => 'nullable|integer|digits:10',
            'phone2'          => 'nullable|integer|digits:10',
            'map'             => 'nullable|string',
        ];

        $request->validate($rules);

        if (!isset($request->shop_id)) {
            
            $retailShop = new RetailShop;

        } else {

            $retailShop = RetailShop::find($request->shop_id);
            
            if (!$retailShop) {
                
                return redirect()->route('shop.index')->with('error', 'Shop not found.');
                
            }

        }
        
        $retailShop->fill($request->all());

        $retailShop->ip = $request->ip();

        $retailShop->date = now();

        $retailShop->added_by = Auth::user()->id;

        $retailShop->is_active = 1;

        if ($retailShop->save()) {

            $message = isset($request->shop_id) ? 'Shop updated successfully.' : 'Shop inserted successfully.';

            return redirect()->route('shop.index')->with('success', $message);

        } else {

            return redirect()->route('shop.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $retailShop = RetailShop::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $retailShop->updateStatus(strval(1));
        } else {

            $retailShop->updateStatus(strval(0));
        }

        return  redirect()->route('shop.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('shop.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (RetailShop::where('id', $id)->delete()) {

            return  redirect()->route('shop.index')->with('success', 'Dealer Deleted Successfully.');
        } else {
            return redirect()->route('shop.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('shop.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}