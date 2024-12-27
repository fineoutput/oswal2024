<?php

namespace App\Http\Controllers\Frontend\Users;

use App\Models\WalletTransactionHistory;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Models\ShippingCharge;

use Illuminate\Http\Request;

use App\Models\Promocode;

use App\Models\Address;

use App\Models\Order;

use App\Models\State;

use App\Models\City;
use App\Models\TransferOrder;

use App\Models\User;

class UserController extends Controller
{
    
    public function index(Request $request)
    {

        $orderlists = $this->orders();

        $address_data = $this->getAddress();

        $walletTransactions = $this->walletTransaction();
        $ratings = DB::table('order_ratings')->get();

        return view('Users.dashboard', compact('orderlists' ,'address_data' ,'walletTransactions', 'ratings'))->with('tittle' , 'Dashboard');

    }

    public function orders()
    {
        $user = Auth::user();
        
        $user_id = $user->id;

        $lang    = 'en';

        $dataw = [];

        $orders = Order::with('orderDetails.product')->where('user_id', $user->id)->where('order_status', '!=', 0)->orderBy('id', 'DESC')->get();
        
        if ($orders->isNotEmpty()) {

            foreach ($orders as $order) {

                $promo = '';

                if ($order->promocode != 'Apply coupon' && !empty($order->promocode)) {

                    $promocode = Promocode::find($order->promocode);

                    if ($promocode) {

                        $promo = $promocode->promocode;

                    }

                }

                $payment_type = '';

                if ($order->payment_type == 1) {

                    $payment_type = $lang != 'hi' ? 'Cash on delivery' : lang_change('Cash on delivery');

                } elseif ($order->payment_type == 2) {

                    $payment_type = $lang != 'hi' ? 'Online Payment' : lang_change('Online Payment');

                }
                $productImage = [];
                
                foreach ($order->orderDetails as $key => $detail) {
                    $product = $detail->product;
                    
                    if ($product) {
                        $productImage[] = asset($product->img_app1);
                    } else {
                        
                        $productImage[] = null;
                    }
                }
                
                

                $rating_avg = DB::table('order_ratings')->where('order_id', $order->id)->avg('rating');
                
                $rating_avg = number_format((float)$rating_avg, 1, '.', '');
                $tracktransfer = TransferOrder::where('order_id',$order->id)->get();

                $dataw[] = [
                    'order_id'        => $order->id,
                    'order_status'    => getOrderStatus($order->order_status),
                    'order_rejected'  => getRejectedByDetails($order->rejected_by , $order->rejected_by_id),
                    'sub_total'       => formatPrice($order->sub_total,false),
                    'amount'          => formatPrice($order->total_amount,false),
                    'promocode_discount' => formatPrice($order->promo_deduction_amount,false),
                    'delivery_charge' => formatPrice($order->delivery_charge,false),
                    'rating_status'   => $rating_avg > 0 ? 1 : 0,
                    'rating'          => $rating_avg,
                    'cod_charge'      => $order->cod_charge,     
                    'payment_type'    => $payment_type,
                    'date'            => $order->date,
                    'promocode'       => $promo,
                    'product_image'   => $productImage,
                    'track'   => $tracktransfer,
                ];
            }
        }

        return $dataw;
    }

    public function orderDetail(Request $request, $id)
    {

        
        $user_id = Auth::user()->id;
        $order_id = decrypt($id);
        $lang = 'en';
 
        $order = Order::find($order_id);
        
        if (!$order) {
            return response()->json([
                'message' => 'Order does not exist',
                'status' => 400
            ]);
        }
     
        $address  = $order->address;
        
        $address->load('states', 'citys');

        $addr_string = "Doorflat {$address->doorflat}, ";

        $addr_string = "Doorflat {$address->doorflat}, " .
                   (!empty($address->landmark) ? "{$address->landmark}, " : '') .
                   "{$address->address}, {$address->zipcode}";

        $orderDetails = $order->orderDetails()->orderBy('id', 'DESC')->get();
        $data = [];
        $productdata = [];

        $payment_type = '';

        if ($order->payment_type == 1) {

            $payment_type = $lang != 'hi' ? 'Cash on delivery' : lang_change('Cash on delivery');

        } elseif ($order->payment_type == 2) {

            $payment_type = $lang != 'hi' ? 'Online Payment' : lang_change('Online Payment');

        }

        // if($order->delivery_status != 0 && $order->track_id != null){

            $deleveryBoy = [
                'id' => '1',
                'name' => 'Manish',
                'phone' => '1234567891',
            ];

        // }

        foreach ($orderDetails as $detail) {

            $product = $detail->product;

            if (!$product) {
                continue;
            }

            $type = $detail->type;

            if (!$type) {
                continue;
            }
     
            $product_name = $lang != "hi" ? $product->name : $product->name_hi;
            $category_name = $lang != "hi" ? $product->category->name : $product->category->name_hi;
            $type_name = $lang != "hi" ? $type->type_name : $type->type_name_hi;

            
             $productdata[] = [
                'order_detail_id'  => $detail->id,
                'product_name'     => $product_name,
                'category_name'    => $category_name,
                'product_image'    => asset($product->img1),
                'type_name'        => $type_name,
                'quantity'         => $detail->quantity,
                'quantity_price'   => $detail->amount,
            ];
        }

        $data = [
            'product'          => $productdata,
            'order_id'         => $order->id,
            'subtotal'         => formatPrice($order->sub_total),
            'promo_discount'   => formatPrice($order->promo_deduction_amount),
            'wallet_discount'  => formatPrice($order->extra_discount),
            'delivery_charge'  => formatPrice($order->delivery_charge),
            'gift_amount'      => formatPrice($order->gift_amt) ?? 0,
            'total_amount'     => formatPrice($order->total_amount),
            'order_status'     => getOrderStatus($order->order_status),
            'address'          => $addr_string,
            'payment_mod'      => $payment_type,
            'cod_charge'       => formatPrice($order->cod_charge),
            'order_datetime'   => $order->date,
            'deleveryBoydetail'=> $deleveryBoy
        ];

       return view('Users.view-orderdetail' , compact('data'))->with('title' , 'Order Details');
    }

    public function cancelOrder(Request $request, $id)

    {

        $user = Auth::user();

        $order_id = decrypt($id);

        $order = Order::find($order_id);

        if($order->extra_discount != null){

            if ($user instanceof User) {

                $user->wallet_amount += $order->extra_discount;
    
                $user->save();
            }

            WalletTransactionHistory::createTransaction([
                'user_id' => $user->id,
                'transaction_type' => 'debit',
                'amount' => $order->extra_discount,
                'transaction_date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                'status' => WalletTransactionHistory::STATUS_COMPLETED,
                'description' => "Refunded wallet amount for canceled order ID: {$order->id}",
            ]);

        }
        
        $order->order_status= 5;
        $order->rejected_by = 1;
        $order->rejected_by_id= Auth::user()->id;
        $order->save();

        return redirect()->to('/user')->with('success', 'Order canceled successfully');

    }

    public function trackOrder(Request $request, $id)
    {
    
        $user_id = Auth::user()->id;
        $order_id = $id;

        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'message' => 'User does not exist',
                'status' => 201,
            ]);
        }

        $order = Order::find($order_id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found!',
                'status' => 201,
                'data' => [],
            ]);
        }

        if (empty($order->track_id)) {

            $res4[] = [
                'date' => $order->date,
                'title' => 'Ordered',
                'activity' => 'Order Placed',
                'location' => '',
            ];

            return response()->json([
                'message' => 'Track id not found!',
                'status' => 201,
                'data' => $res4,
            ]);
        }

        $track_id = $order->track_id;

        $token = getShipRocketToken();

        // Track the order with Shiprocket

        $main_respo = trackOrderApi($token ,$track_id);

        $tracking_data = $main_respo->tracking_data;

        $track_status = $tracking_data->track_status;

        if ($track_status != 0) {

            $res2 = [];

            foreach ($tracking_data->shipment_track_activities as $activity) {

                $activity_parts = explode('-', $activity->activity);

                $activity_title = $activity_parts[0];

                $activity_detail = implode(' ', array_slice($activity_parts, 1));
                
                $res2[] = [
                    'date' => $activity->date,
                    'title' => $activity_title,
                    'activity' => $activity_detail,
                    'location' => $activity->location,
                ];
            }

            return response()->json([
                'message' => 'success',
                'status' => 200,
                'data' => $res2,
            ]);
            
        } else {
            return response()->json([
                'message' => 'wrong order id',
                'status' => 201,
                'data' => [],
            ]);
        }
    }

    public function getAddress() {
        
        $addresses = Address::where('user_id', Auth::User()->id)->get();

        $address_data = [];

        foreach ($addresses as $address) {

            $address->load('states', 'citys');

            $addr_string = "Doorflat {$address->doorflat}, ";

            if (!empty($address->landmark)) {
                $addr_string .= "{$address->landmark}, ";
            }
            $addr_string .= "{$address->address},{$address->zipcode}";

            $address['custom_address'] = $addr_string;

            $address_data[] =  $address;
        }

        return $address_data;
    }
    
    public function walletTransaction() {
        
        $completedTransactions = WalletTransactionHistory::getByStatus(
            WalletTransactionHistory::STATUS_COMPLETED,
            Auth::id()
        );

        $data = [];

        foreach($completedTransactions as $value){

            $data[] = [
                'user'             => $value->user_id,
                'transaction_type' => $value->transaction_type,
                'amount'           => $value->amount,
                'description'      => $value->description,
                'date'             => $value->created_at,
            ];
        }

        return $data;
    }

    public function addAddress(Request $request, $redirect , $id=null)  {
        
        $address = null;

        if ($id !== null) {

            $address = Address::find(base64_decode($id));
            
        }
        $states = State::all();

        $cities = City::get();

        return view('Users.add-address', compact('address' ,'states' ,'cities' , 'redirect'));

    }

    public function storeAddress(Request $request)
    {
       
        $rules = [
            'doorflat'  => 'required|string',
            'city'      => 'required|integer',
            'state'     => 'required|integer',
            'zipcode'   => 'required|digits:6|integer',
            'address'   => 'required|string',
            'landmark'  => 'required|string',
        ];

        $request->validate($rules); 

        $address = isset($request->address_id) ? Address::find($request->address_id) : new Address;

        if (isset($request->address_id) && !$address) {
            return redirect()->back()->with('error', 'Address not found.');
        }

        $shippingData = ShippingCharge::where('city_id', $request->city)->first();
        if (!$shippingData) {
            return redirect()->back()->with('error', 'Shipping services not available in this area.');
        }
 
        $address->fill($request->except('latitude', 'longitude'));

        $address->latitude = $request->latitude ?? '131'; 

        $address->longitude = $request->longitude ?? '131';  

        $address->user_id = Auth::user()->id; 

        $address->date = now()->setTimezone('Asia/Calcutta')->toDateTimeString();

        if ($address->save()) {

            $message = isset($request->address_id) ? 'Address updated successfully.' : 'Address inserted successfully.';

            if($request->redirect == 'checkout'){

                return redirect()->route('checkout.get-address')->with('success', $message);

            }else{

                return redirect()->to('/user')->with('success', $message);

            }
           
        } else {

            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function getCity(Request $request) {
        
        $stateId = $request->input('state_id');

        $cities = City::select('id', 'city_name')->where('state_id', $stateId)->get();
        
        return response()->json($cities, 200);
        
    }

    public function deleteAddress(Request $request, $id)
    {
     
        $address = Address::where('user_id', Auth::user()->id)->where('id', base64_decode($id))->first();

        if (!$address) {
            return redirect()->back()->with('error', 'Address not found.');
        }

        
        if ($address->delete()) {
            return redirect()->back()->with('success', 'Address deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete the address. Please try again.');
        }
    }

    public function rating(Request $request){
        // dd($request);
        $validatedData = $request->validate([
            'order_id' => 'required|integer',
            'rating' => 'required|integer',
            'description' => 'nullable|string',
        ]);
    
        $ratingData = [
            'order_id' => $validatedData['order_id'],
        ];
    
        $updateData = [
            'rating' => $validatedData['rating'],
            'description' => $validatedData['description'] ?? null,
        ];
    
        // Check if a record exists; update if found, insert otherwise
        $rating = DB::table('order_ratings')->updateOrInsert($ratingData, $updateData);
    
        return response()->json(['success' => true, 'message' => 'Rating submitted successfully.']);
    }
}
       