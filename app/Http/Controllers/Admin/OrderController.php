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
use App\Mail\OrderStatusMail;
use App\Models\OrderDetail;

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

        $orders = $orders->with('orderDetails' ,'user' , 'address.citys' ,'address.states' , 'gift' , 'gift1' , 'promocodes' ,'invoices' )->get();

        // dd($orders);
        return view('admin.Orders.view_all_orders', compact('orders', 'pageTitle'));
       
    }

    public function update_status(Request $request, $id , $status)

    {

        $id = base64_decode($id);

        $order_status = base64_decode($status);

        $addedBy = Auth::user()->id;

        $curDate = now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');;

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        $order = Order::find($id);

        $order->order_status = $order_status;

        $order->last_update_date = $curDate;

        if($order_status == 5){
            
            $order->rejected_by = 2;
            
            $order->rejected_by_id =  $addedBy;
            
        }

        $order->save();

        if ($order_status == 2 || $order_status == 3 || $order_status == 4) {

            $user = User::find($order->user_id);

            $deviceToken = UserDeviceToken::where('user_id', $order->user_id)->first();
            
            if ($user && $deviceToken) {

                $this->sendPushNotification($deviceToken->device_token, $order_status);

                $this->sendEmailNotification($user, $order, $order_status);

            }

        }

        return redirect()->route('order.new-order')->with('success', 'Order status updated successfully');

        // } else {

        // 	return  redirect()->route('achievements.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    private function sendPushNotification($deviceToken, $type)
    {
        $title = '';
        $message = '';

        switch ($type) {
            case 2:
                $title = 'Order Accepted';
                $message = 'Your order has been accepted.';
                break;
            case 3:
                $title = 'Order Dispatched';
                $message = 'Your order has been dispatched.';
                break;
            case 4:
                $title = 'Order Delivered';
                $message = 'Your order has been delivered successfully.';
                break;
            case 5:
                $title = 'Order Cancelled';
                $message = 'Your order has been cancelled.';
                break;
            // Add cases for other types if needed
        }

        $url = 'https://fcm.googleapis.com/fcm/send';
        $msg = [
            'body' => $message,
            'title' => $title,
            'sound' => 'default',
        ];

        $fields = [
            'to' => $deviceToken,
            'notification' => $msg,
            'priority' => 'high',
        ];

        $headers = [
            'Authorization: key=' . env('FCM_SERVER_KEY'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_exec($ch);
        curl_close($ch);
    }

    private function sendEmailNotification($user, $order, $type)
    {
        $data = [
            'name' => $user->first_name,
            'order_id' => $order->id,
        ];

        switch ($type) {
            case 2:
                Mail::to('abhi242singh@gmail.com')->send(new OrderStatusMail($data, 'admin.Emails.email-container.orderaccepted', 'Order Accepted'));
                break;
            case 3:
                Mail::to('abhi242singh@gmail.com')->send(new OrderStatusMail($data, 'admin.Emails.email-container.dispatch', 'Order Dispatched'));
                break;
            case 4:
                Mail::to('abhi242singh@gmail.com')->send(new OrderStatusMail($data, 'admin.Emails.email-container.order-delivered', 'Order Successfully Delivered'));
                break;
            case 5:
                Mail::to('abhi242singh@gmail.com')->send(new OrderStatusMail($data, 'admin.Emails.email-container.order-cancelled', 'Order Cancelled'));
                break;
        }
    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Order::where('id', $id)->delete()) {

            return  redirect()->route('order.new-order')->with('success', 'Order Deleted Successfully.');

        } else {

            return redirect()->route('order.new-order')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('achievements.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

    public function view_product($id, Request $request) {

        $id = base64_decode($id);

        $orders = OrderDetail::where('main_id' , $id)->get();

        $pageTitle ='Products Details';

        return view('admin.Orders.view_product_details', compact('orders', 'pageTitle'));

    }

    public function view_bill($id, Request $request) {
        $id = base64_decode($id);
        $order = Order::with(['user', 'address.citys', 'address.states','orderDetails.product', 'orderDetails.type', 'invoices', 'gift'])->findOrFail( $id );
        $user = $order->user;
        $address = $order->address;
        $city = $address->city ? $address->citys->name : '';
        $state = $address->state ? $address->states->name : '';
        $zipcode = $address->zipcode;
        $orderItems = $order->orderDetails;
        $invoice = $order->invoices;
        $giftCard = $order->gift;
        $promocode = $order->promocodes;

        return view('admin.Orders.view_order_bill', compact('order', 'user', 'address', 'city', 'state', 'zipcode', 'orderItems', 'invoice', 'giftCard' ,'promocode'));
    }
    public function deliveryChallan($id, Request $request)  {
           
           $id = base64_decode($id);
           $order1_data = Order::with(['user', 'address.citys', 'address.states'])->findOrFail( $id );

           $user = $order1_data->user;
           
           return view('admin.Orders.view_delivery_challan', compact('order1_data', 'user'));
    }
}