<?php

namespace App\Http\Controllers\Admin\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dealer;
use App\Models\DealerEnquiry;

class DealerController extends Controller
{

    public function index()
    {

        $dealers = Dealer::orderby('id', 'desc')->get();

        return view('admin.Dealer.view-dealer', compact('dealers'));
    }

    public function create($id = null, Request $request)

    {
        $dealer = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('dealer.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $dealer = Dealer::find(base64_decode($id));
            
        }

        return view('admin.Dealer.add-dealer', compact('dealer'));
    }

    public function store(Request $request)

    {
       
        $rules = [
            'name'              => 'required|string',
            'pincode'           => 'required|integer',
        ];

        if (!isset($request->dealer_id)) {
            
            $dealer = new Dealer;

        } else {

            $dealer = Dealer::find($request->dealer_id);
            
            if (!$dealer) {
                
                return redirect()->route('dealer.index')->with('error', 'Dealer not found.');
                
            }

        }

        $request->validate($rules);

        $dealer->fill([

            'dealer_name'   => $request->name,

            'pincode'       => $request->pincode,

            'ip'            => $request->ip(),

            'cur_date'      => now(),

            'added_by'      => Auth::user()->id,

            'is_active'     =>1,

        ]);


        if ($dealer->save()) {

            $message = isset($request->dealer_id) ? 'Dealer updated successfully.' : 'Dealer inserted successfully.';

            return redirect()->route('dealer.index')->with('success', $message);

        } else {

            return redirect()->route('dealer.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Dealer::where('id', $id)->delete()) {

            return  redirect()->route('dealer.index')->with('success', 'Dealer Deleted Successfully.');
        } else {
            return redirect()->route('dealer.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('dealer.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

    public function dealer_enquiry() {

       $dealers = DealerEnquiry::OrderBy('id' , "Desc")->get();
     
       return view('admin.Dealer.view-dealer-inquery', compact('dealers'));

    }
}