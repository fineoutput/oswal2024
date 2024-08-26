<?php
namespace App\Http\Controllers\Admin\Delivery;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\DeliveryBoy;

class DeliveryBoyController extends Controller
{

    public function index()
    {

        $users = DeliveryBoy::orderBy('id', 'desc')->get();

        return view('admin.Delivery.view-user', compact('users'));
    }

    public function create($id = null, Request $request)

    {
        $user = null;
      
        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('delivery.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $user = DeliveryBoy::find(base64_decode($id));
        }

        return view('admin.Delivery.add-user', compact('user'));
    }

    public function store(Request $request)

    {
      
        $rules = [
            'name'              => 'required',
            'pincode'           => 'required',
            'email'             => 'required|email',
            'phone'             => 'required|digits:10',
            'password'          => 'nullable',
            'img'               => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ];

        $request->validate($rules);

        if (!isset($request->user_id)) {

            $user = new DeliveryBoy;

        } else {

            $user = DeliveryBoy::find($request->user_id);
            
            if (!$user) {
                
                return redirect()->route('delivery.index')->with('error', 'User not found.');
                
            }

        }

       
       $user->fill([
        'name'     => $request->name,
        'email'    => $request->email,
        'phone'    => $request->phone,
        'pincode'  => $request->pincode,
        'ip'       => $request->ip(),
        'date'     => now(),
        'added_by' => Auth::user()->id,
        'is_active' => 1,
       ]);

        if ($request->password) {
            $user->password = Hash::make(trim($request->password));
        }

        if ($request->hasFile('img')) {
            $user->image = uploadImage($request->file('img'), 'delivery');
        }

        if ($user->save()) {
            $message = isset($request->user_id) ? 'User updated successfully.' : 'User inserted successfully.';
            return redirect()->route('delivery.index')->with('success', $message);
        } else {
            return redirect()->route('delivery.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $user = DeliveryBoy::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $user->updateStatus(strval(1));
        } else {

            $user->updateStatus(strval(0));
        }

        return  redirect()->route('delivery.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('delivery.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (DeliveryBoy::where('id', $id)->delete()) {

            return  redirect()->route('delivery.index')->with('success', 'User Deleted Successfully.');
        } else {
            return redirect()->route('delivery.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('delivery.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

    public function Order($id, Request $request) {

        $id = base64_decode($id);

        $deliveryUser = DeliveryBoy::with('transferOrders')->findOrFail($id);

        $activeDeliveryUsers = DeliveryBoy::where('is_active', 1)->get();

        return view('admin.Delivery.view_orders', [
            'dbname' => $deliveryUser,
            'transferOrders' => $deliveryUser->transferOrders,
            'dusers' => $activeDeliveryUsers,
            'pageTitle' => 'Delivery Boy Orders',
        ]);
    }
}