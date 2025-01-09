<?php

namespace App\Http\Controllers\Admin;

// use App\Services\GoogleAccessTokenService;

use App\Models\VendorReward;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Mail;

use App\Services\FirebaseService;

use App\Models\UserDeviceToken;

use App\Models\TransferOrder;

use App\Mail\OrderStatusMail;

use Illuminate\Http\Request;

use App\Models\DeliveryBoy;

use App\Models\OrderDetail;

use App\Models\Order;
use App\Models\Address;
use App\Models\Reward;
use Illuminate\Support\Facades\DB;


use App\Models\User;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    protected $googleAccessTokenService;

    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }


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

    //     $orders = is_array($status)? Order::whereIn('order_status', $status)->orderBy('id', 'desc'): Order::where('order_status', $status)->orderBy('id', 'desc');

    //     $orders = $orders->whereHas('user', function ($query) {
    //         $query->where('role_type', '!=' , 2);
    //     });

    //     $orders = $orders->with('orderDetails' ,'user' , 'address.citys' ,'address.states' , 'gift' , 'gift1' , 'promocodes' ,'invoices','rating' )->get();

    //    $orderIds = $orders->pluck('id'); // This will give you an array of order_ids
    //     $rating_avg = DB::table('order_ratings')
    //         ->whereIn('order_id', $orderIds) // Use whereIn to filter by multiple order_ids
    //         ->avg('rating'); 

    $orders = is_array($status)
    ? Order::whereIn('order_status', $status)->orderBy('id', 'desc')
    : Order::where('order_status', $status)->orderBy('id', 'desc');

// Filter orders based on user role
$orders = $orders->whereHas('user', function ($query) {
    $query->where('role_type', '!=', 2);
});

// Eager load relationships
$orders = $orders->with('orderDetails', 'user', 'address.citys', 'address.states', 'gift', 'gift1', 'promocodes', 'invoices', 'rating')->get();

// Loop through each order and calculate its individual average rating
foreach ($orders as $order) {
    // Calculate the average rating for this specific order
    $rating_avg = DB::table('order_ratings')
        ->where('order_id', $order->id)
        ->avg('rating'); // Get the average of the 'rating' column for this order_id
    
    // Store the average rating in the order instance
    $order->average_rating = $rating_avg; // Adding a custom attribute to the order
}

       
        return view('admin.Orders.view_all_orders', compact('orders', 'pageTitle'));
       
    }

    public function update_status(Request $request, $id , $status)
    {
        $routeName = Route::currentRouteName();

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

        if ($order_status == 4) {
            $dilivery_order = TransferOrder::where('order_id',$order->id)->first();
            $dilivery_boy = DeliveryBoy::where('id',$dilivery_order->user_id)->first();

            if ($dilivery_boy) {
                $this->sendPushNotification($dilivery_boy->fcm_token, $order_status);
                $this->sendEmailNotification($dilivery_boy, $order, $order_status);
            }

        }

        if ($order_status == 2 || $order_status == 3 || $order_status == 4) {

            $user = User::find($order->user_id);
  
            if ($user) {

                $this->sendPushNotification($user->fcm_token, $order_status);
        
                $this->sendEmailNotification($user, $order, $order_status);

            }

            if($order_status == 4)
            {
                $orderId = $id ?? 0;
                $vendor_user_id = $user->id;
                $role_type = $user->role_type;

                if($role_type == 2){
                $vendortotalWeight = DB::table('tbl_order1')->where('order_status', 4)->where('user_id', $vendor_user_id)->sum('total_order_weight'); 
                // Log::info("Total Weight: " . $vendortotalWeight);
                if($vendortotalWeight > 0){
                $rewards = Reward::where('weight', '<=', $vendortotalWeight)->where('is_active',1)
                ->orderBy('weight', 'desc')
                ->get(); 
                if ($rewards) {

                    foreach ($rewards as $reward) {
                        // Log::info("Reward Name: " . $reward->name);

                        $AlreadyReward = VendorReward::where('vendor_id', $vendor_user_id)->where('reward_id', $reward->id)->whereIn('status', [1, 2, 3])->first();
    
                        if(!$AlreadyReward){   
                            // Log::info("Reward Given: " . $reward->name);           
                        DB::table('vendor_rewards')->insert([
                        'vendor_id'     => $vendor_user_id,
                        'order_id'      => $orderId,
                        'reward_name'   => $reward->name,
                        'reward_image'  => $reward->image,
                        'reward_id'     => $reward->id,
                        'status'     => 1,
                        'achieved_at'   => now()->setTimezone('Asia/Calcutta')->format('Y-m-d H:i:s'),
                         ]);
    
                        }
                    }

                    }
                }

                } //role type end
            }

        }
        if($routeName == 'order.vendor.update-status'){

            return redirect()->route('order.vendor.new-order')->with('success', 'Order status updated successfully');
        }else{

            return redirect()->route('order.new-order')->with('success', 'Order status updated successfully');
        }

        // } else {

        // 	return  redirect()->route('achievements.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    private function sendPushNotification($fcm_token, $type)
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

        // $payload = [
        //     'message' => [
        //         'token' => $fcm_token,
        //         'notification' => [
        //             'body' => $message,
        //             'title' => $title,
        //         ],
        //     ],
        // ];

        if($fcm_token != null){

            $response = $this->firebaseService->sendNotificationToUser($fcm_token, $title, $message);
    
            if(!$response['success']) {
                
                if (!$response['success']) {
    
                    Log::error('FCM send error: ' . $response['error']);
                    
                }
            }
        }
        // $response = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . $this->googleAccessTokenService->getAccessToken(), 
        //     'Content-Type' => 'application/json',
        // ])->post('https://fcm.googleapis.com/v1/projects/oswalsoap-d8508/messages:send', $payload);
       
        // if ($response->successful()) {

        //     // return $response->body(); 
        //     return true;
        // } else {

        //     throw new \Exception('FCM Request failed with status: ' . $response->status() . ' and error: ' . $response->body());
        // }
     
    }

    private function sendEmailNotification($user, $order, $type)
    {
        $data = [
            'name' => $user->first_name,
            'order_id' => $order->id,
        ];

        switch ($type) {
            case 2:
                // Mail::to('abhi242singh@gmail.com')->send(new OrderStatusMail($data, 'admin.Emails.email-container.orderaccepted', 'Order Accepted'));
                break;
            case 3:
                // Mail::to('abhi242singh@gmail.com')->send(new OrderStatusMail($data, 'admin.Emails.email-container.dispatch', 'Order Dispatched'));
                return $this->transferOrderProcess($order->id);
                break;
            case 4:
                // Mail::to('abhi242singh@gmail.com')->send(new OrderStatusMail($data, 'admin.Emails.email-container.order-delivered', 'Order Successfully Delivered'));
                break;
            case 5:
                // Mail::to('abhi242singh@gmail.com')->send(new OrderStatusMail($data, 'admin.Emails.email-container.order-cancelled', 'Order Cancelled'));
                break;
        }
        return true;
    }

    public function destroy($id, Request $request)

    {
        $routeName = Route::currentRouteName();

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Order::where('id', $id)->delete()) {

            if($routeName == 'order.vendor.destroy'){

                return  redirect()->route('order.vendor.new-order')->with('success', 'Order Deleted Successfully.');

            }else{

                return  redirect()->route('order.new-order')->with('success', 'Order Deleted Successfully.');
            }

        } else {

            if($routeName == 'order.vendor.destroy'){

                return  redirect()->route('order.vendor.new-order')->with('error', 'Some Error Occurred.');

            }else{

                return redirect()->route('order.new-order')->with('error', 'Some Error Occurred.');
            }

        }

        // } else {

        // 	return  redirect()->route('achievements.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

    public function view_product(Request $request, $id) {

        $routeName = Route::currentRouteName();

        $id = base64_decode($id);

        $orders = OrderDetail::where('main_id' , $id)->get();
 
        $pageTitle ='Products Details';

        if($routeName == 'order.vendor.view-product'){

            return view('admin.VendorOrders.view_product_details', compact('orders', 'pageTitle'));

        }else{

            return view('admin.Orders.view_product_details', compact('orders', 'pageTitle'));
        }

    }

    public function view_bill($id, Request $request) {

        $id = base64_decode($id);

        $routeName = Route::currentRouteName();

        if($routeName == 'order.vendor.view-bill'){

            $order = Order::with(['user', 'address.citys', 'address.states','orderDetails.product', 'orderDetails.vendortype', 'invoices', 'gift'])->findOrFail( $id );

        }else{

            $order = Order::with(['user', 'address.citys', 'address.states','orderDetails.product', 'orderDetails.type', 'invoices', 'gift'])->findOrFail( $id );
        }
        $useraddress = Address::where('user_id',$id)->first();

        $user = $order->user;
        $address = $order->address;
        // return $address;
        $city = $address->city ? $address->citys->city_name : '';
        $state = $address->state ? $address->states->state_name : '';
        $zipcode = $address->zipcode;
        $orderItems = $order->orderDetails;
        $invoice = $order->invoices;
        $giftCard = $order->gift;
        $giftCardSec = $order->gift1 ?? null;
        $promocode = $order->promocodes;

        if($routeName == 'order.vendor.view-bill'){

            return view('admin.VendorOrders.view_order_bill', compact('order', 'user', 'address', 'city', 'state', 'zipcode', 'orderItems', 'invoice', 'giftCard' ,'promocode' ,'giftCardSec'));

        }else{

            return view('admin.Orders.view_order_bill', compact('order', 'user', 'address', 'city', 'state', 'zipcode', 'orderItems', 'invoice', 'giftCard' ,'promocode' ,'giftCardSec'));
        }
    }

    public function deliveryChallan($id, Request $request)  {
           
           $id = base64_decode($id);
           $order1_data = Order::with(['user', 'address.citys', 'address.states'])->findOrFail( $id );

           $user = $order1_data->user;
           
           return view('admin.Orders.view_delivery_challan', compact('order1_data', 'user'));
    }

    public function transferOrderProcess($order_id_encoded)
    {
        // dd($order_id_encoded);
        // if (Auth::check() && Auth::user()->is_admin) {

            $order_id = $order_id_encoded;

            $ip = request()->ip();

            $cur_date = now();

            $addedby = Auth::id();

            $order = Order::with('address','user')->find($order_id);

            if (!$order) {
               Session::flash('emessage', 'Order not found');
                return redirect()->back();
            }

            $pincode = $order->address->zipcode;

            if ($order->user->role_type == 2) {
               
                $delivery_users = DeliveryBoy::where('role_type', 2)->where('pincode', 'LIKE', "%$pincode%")->where('is_active', 1)->get();

            }else{

                $delivery_users = DeliveryBoy::where('pincode', 'LIKE', "%$pincode%")->where('is_active', 1)->get();

            }

            if ($delivery_users->isEmpty()) {
                Session::flash('emessage', 'No delivery users available for this pincode');
                return redirect()->back();
            }

            $delivery_user_id = $delivery_users->first()->id;

            TransferOrder::where('order_id', $order_id)->delete();

            $data_insert = [
                'order_id' => $order_id,
                'delivery_user_id' => $delivery_user_id,
                'status' => 1,
                'ip' => $ip,
                'added_by' => $addedby,
                'date' => $cur_date
            ];

            $last_id = TransferOrder::create($data_insert)->id;

            $order->update(['delivery_status' => 1]);

            if ($last_id != 0) {

                $delivery_user_data = DeliveryBoy::find($delivery_user_id);

                if ($delivery_user_data) {
                       
                    $title = "New Order Arrived";

                    $body = "New delivery order transferred to you from admin. Please check.";

                        // $payload = [
                        //     'message' => [
                        //         'token' => $delivery_user_data->fcm_token,
                        //         'notification' => [
                        //             'body' => "New delivery order transferred to you from admin. Please check.",
                        //             'title' => "New Order Arrived",
                        //         ],
                        //     ],
                        // ];

                        if($delivery_user_data->fcm_token != null){

                            $response = $this->firebaseService->sendNotificationToUser($delivery_user_data->fcm_token, $title, $body);
    
                            if(!$response['success']) {
                
                                if (!$response['success']) {
                    
                                    Log::error('FCM send error: ' . $response['error']);
                                    
                                }
                            }
                            
                        }
                        // $response = Http::withHeaders([
                        //     'Authorization' => 'Bearer ' . $this->googleAccessTokenService->getAccessToken(), 
                        //     'Content-Type' => 'application/json',
                        // ])->post('https://fcm.googleapis.com/v1/projects/oswalsoap-d8508/messages:send', $payload);
                       
                        // if ($response->successful()) {
                        //     return $response->body(); 
                        // } else {
                        //     throw new \Exception('FCM Request failed with status: ' . $response->status() . ' and error: ' . $response->body());
                        // }
                    
                    Session::flash('smessage', 'Order Transferred successfully');
                    return redirect()->back();

                } else {
                    Session::flash('emessage', 'Delivery user not found');
                    return redirect()->back();
                }
            } else {
                Session::flash('emessage', 'Sorry, an error occurred');
                return redirect()->back();
            }
        // } else {
        //     return redirect()->route('admin_login');
        // }
    }

    public function VendorIndex()
    {
        
        $routeName = Route::currentRouteName();
     
        switch ($routeName) {

            case 'order.vendor.new-order':

                $status = [1, 2]; 

                $pageTitle = 'New Orders';

                break;

            case 'order.vendor.dispatched-order':

                $status = 3;

                $pageTitle = 'Dispatched Orders';

                break;

            case 'order.vendor.completed-order':

                $status = 4;

                $pageTitle = 'Completed Orders';

                break;

            case 'order.vendor.rejected-order':

                $status = 5;

                $pageTitle = 'Rejected Orders';

                break;

            default:

                abort(404, 'Order status not found');
        }

        $orders = is_array($status)
           ? Order::whereIn('order_status', $status)->orderBy('id', 'desc')
           : Order::where('order_status', $status)->orderBy('id', 'desc');

        $orders = $orders->whereHas('user', function ($query) {
            $query->where('role_type', 2);
        });

        $orders = $orders->with('orderDetails' ,'user' , 'address.citys' ,'address.states' , 'gift' , 'gift1' , 'promocodes' ,'invoices' )->get();

       
        return view('admin.VendorOrders.view_all_orders', compact('orders', 'pageTitle'));
       
    }

  

    public function users_transfer(){
    
        $old_orders = DB::table('tbl_users')->get(); 

            foreach($old_orders as $od){

                $data_insert = [
                    'role_type' => 1,
                    'first_name' => $od->first_name,
                    'first_name_hi' => $od->first_name_hi,
                    'device_id' => $od->device_id,
                    'auth' => $od->auth,
                    'fcm_token' => "",
                    'email' => $od->email,
                    'contact' => $od->contact,
                    'password' => $od->password,
                    'image' => $od->image,
                    'status' => $od->status,
                    'wallet_amount' => 0,
                    'referral_code' => User::generateReferralCode(),
                    'is_hidden' => $od->is_hidden,
                    'ip' => $od->ip,
                    'date' => $od->date,
                    'added_by' => $od->added_by,
                    'is_active' => $od->is_active
                ];
    
                $last_id = User::create($data_insert)->id;
             
                
            }


      }
}