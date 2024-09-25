<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Recent;

class RecentController extends Controller
{

    public function index()
    {

        $recents = Recent::with('product')->orderBy('id', 'desc')->get();

        return view('admin.Recent.view-recent', compact('recents'));
    }

    public function create(Request $request, $id = null)

    {
        $recent = null;

        $products   = sendProduct();
      
        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('recent.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $recent = Recent::find(base64_decode($id));

        }

        return view('admin.Recent.add-recent', compact('recent' ,'products'));
    }

    public function store(Request $request)

    {
        // dd($request->all());

        $rules = [
            'product_id'              => 'required|string',
            'recent'              => 'required|integer',
        ];

        if (!isset($request->recent_id)) {

            $recent = new Recent;

        } else {

            $recent = Recent::find($request->recent_id);
            
            if (!$recent) {
                
                return redirect()->route('recent.index')->with('error', 'Recent not found.');
                
            }

        }

        $request->validate($rules);

        $recent->fill($request->all());

        $recent->ip = $request->ip();

        $recent->date = now();

        $recent->added_by = Auth::user()->id;

        $recent->is_active = 1;

        if ($recent->save()) {

            $message = isset($request->recent_id) ? 'Recent updated successfully.' : 'Recent inserted successfully.';

            return redirect()->route('recent.index')->with('success', $message);

        } else {

            return redirect()->route('recent.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $recent = Recent::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $recent->updateStatus(strval(1));
        } else {

            $recent->updateStatus(strval(0));
        }

        return  redirect()->route('recent.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('recent.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Recent::where('id', $id)->delete()) {

            return  redirect()->route('recent.index')->with('success', 'recent Deleted Successfully.');
        } else {
            return redirect()->route('recent.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('recent.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}