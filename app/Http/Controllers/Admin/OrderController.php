<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\UserDeviceToken;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{

    public function index()

    {
        $routeName = Route::currentRouteName();
     
        switch ($routeName) {

            case 'order.new-order':

                $status = [1, 2]; 

                $pageTitle = 'New Orders';

                break;

            case 'order.dispatched-order':

                $status = 3;

                $pageTitle = 'Dispatched Orders';

                break;

            case 'order.completed-order':

                $status = 4;

                $pageTitle = 'Completed Orders';

                break;

            case 'order.rejected-order':

                $status = 5;

                $pageTitle = 'Rejected Orders';

                break;

            default:

                abort(404, 'Order status not found');
        }

        $orders = is_array($status)? Order::whereIn('order_status', $status)->orderBy('id', 'desc'): Order::where('order_status', $status)->orderBy('id', 'desc');

        $orders->with('orderDetails' ,'user' , 'address' , 'gift' , 'gift1' ,'invoices')->get();

        return view('admin.Orders.view_all_orders', compact('orders', 'pageTitle'));
       
    }

    // public function create($id = null, Request $request)

    // {
    //     $achievements = null;

    //     if ($id !== null) {

    //         $admin_position = $request->session()->get('position');

    //         if ($admin_position !== "Super Admin") {

    //             return redirect()->route('achievements.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

    //         }

    //         $achievements = Achievement::find(base64_decode($id));
            
    //     }

    //     return view('admin.Achievements.add-achievements', compact('achievements'));
    // }

    // public function store(Request $request)

    // {
    //     // dd($request->all());

    //     $rules = [
    //         'title'        => 'required|string',
    //         'short_desc'    => 'required|string',
    //         'long_desc'     => 'required|string',
          
    //     ];

    //     $request->validate($rules);

    //     if (!isset($request->achievements_id)) {

    //         $rules['img'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

    //         $achievements = new Achievement;

    //     } else {

    //         $rules['img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

    //         $achievements = Achievement::find($request->achievements_id);
            
    //         if (!$achievements) {
                
    //             return redirect()->route('achievements.index')->with('error', 'Achievements not found.');
                
    //         }

    //     }
        
    //     $achievements->fill($request->all());

    //     if ($request->hasFile('img')) {
    //         $achievements->image = uploadImage($request->file('img'), 'achievements', 'img');
    //     }

    //     $achievements->ip = $request->ip();

    //     $achievements->url = str_replace( ' ', '-',trim($request->title));

    //     $achievements->date = now();

    //     $achievements->added_by = Auth::user()->id;

    //     $achievements->is_active = 1;

    //     if ($achievements->save()) {

    //         $message = isset($request->achievements_id) ? 'Achievements updated successfully.' : 'Achievements inserted successfully.';

    //         return redirect()->route('achievements.index')->with('success', $message);

    //     } else {

    //         return redirect()->route('achievements.index')->with('error', 'Something went wrong. Please try again later.');

    //     }
    // }

    // public function update_status(Request $request, $status, $id)

    // {

    //     $id = base64_decode($id);

    //     $admin_position = $request->session()->get('position');

    //     $achievements = Order::find($id);

    //     // if ($admin_position == "Super Admin") {

    //     if ($status == "active") {

    //         $achievements->updateStatus(strval(1));
    //     } else {

    //         $achievements->updateStatus(strval(0));
    //     }

    //     return  redirect()->route('achievements.index')->with('success', 'Status Updated Successfully.');

    //     // } else {

    //     // 	return  redirect()->route('achievements.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

    //     // }

    // }

    // public function destroy($id, Request $request)

    // {

    //     $id = base64_decode($id);

    //     $admin_position = $request->session()->get('position');

    //     // if ($admin_position == "Super Admin") {

    //     if (Achievement::where('id', $id)->delete()) {

    //         return  redirect()->route('achievements.index')->with('success', 'Blog Deleted Successfully.');
    //     } else {
    //         return redirect()->route('achievements.index')->with('error', 'Some Error Occurred.');

    //     }

    //     // } else {

    //     // 	return  redirect()->route('achievements.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

    //     // }

    // }

}