<?php

namespace App\Http\Controllers\ApiManagement;

use Illuminate\Support\Facades\Validator; 

use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Http;

use App\Models\DeliveryAmount;

use App\Models\TransferOrder;

use Illuminate\Http\Request;

use App\Models\DeliveryBoy;

use App\Models\OrderDetail;

use GuzzleHttp\Client;

use App\Models\Order;

use App\Services\FirebaseService;
use App\Services\DeliveryBoyService;
use App\Models\User;

use Illuminate\Support\Facades\Log;
class DeliveryBoyController extends Controller
{

    protected $firebaseService;
    protected $firebaseServiceDelivery;

    public function __construct(FirebaseService  $firebaseService, DeliveryBoyService $firebaseServiceDelivery)
    {
        $this->firebaseService = $firebaseService;
        $this->firebaseServiceDelivery = $firebaseServiceDelivery;
    }

    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email'        => 'required|email',
            'password'     => 'required',
            'device_token' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $deliveryBoy = DeliveryBoy::where('email', $request->email)->first();

        if (!$deliveryBoy) {
            return response()->json([
                'success' => false,
                'message' => 'Delivery Boy not found',
            ]);
        }
        
        if($deliveryBoy->is_active != 1){
            return response()->json([
                'success' => false,
                'message' => 'Please Contact Admin',
            ]);
        }

        if (!$deliveryBoy || !Hash::check(trim($request->password), $deliveryBoy->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ]);
        }

        //add Device Token 
        if($request->device_token){
            $deliveryBoy->update(['device_token' => $request->device_token]);
        }

        $token = $deliveryBoy->createToken('DeliveryBoyApp')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'You have logged in successfully.',
            'token' => $token,
            'data' => $deliveryBoy,
            'image' => asset($deliveryBoy->image) ,
        ]);
        
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function dashboard(Request $request)
    {
       
        $deliveryBoy = Auth::user(); 
    
        if ($deliveryBoy) {
           
            $deliveryAmount = $deliveryBoy->deliveryAmount; 

            $tamount = $deliveryAmount ? $deliveryAmount->amount : 0;
   
            // Fetch total amount to collect
            $transferOrders = TransferOrder::where('status', '>=' , 1)->where('delivery_user_id', $deliveryBoy->id)->get(); 
            $walletAmountSum = DeliveryAmount::where('deluser_id', $deliveryBoy->id)->sum('amount');
            // return $walletAmountSum;

    
            $tfamount = 0;
            foreach ($transferOrders as $transferOrder) {
                if($transferOrder->Orders->payment_type == 2){
                    continue;
                }
                $tfamount += $transferOrder->Orders ? $transferOrder->Orders->total_amount : 0;
            }
    
           // Count total pending orders
           $pendingOrders = TransferOrder::where('delivery_user_id', $deliveryBoy->id)
           ->where('status', '>=', 1)
           ->where('status', '<', 4)
           ->count();

           // Count total completed orders
           $completedOrders = TransferOrder::where('delivery_user_id', $deliveryBoy->id)
           ->whereIn('status', [4, 5])
           ->count();
       
            // Prepare response data
            $data = [
                'wallet_amount' => formatPrice($walletAmountSum),
                'total_collection' => formatPrice($tfamount),
                'pending_orders' => $pendingOrders,
                'completed_orders' => $completedOrders,
            ];
    
            // Return success response
            return response()->json([
                'message' => 'Success',
                'status' => 200,
                'data' => $data,
            ]);
        }
    
        // Return error response if user is not authenticated
        return response()->json([
            'message' => 'User not authenticated!',
            'status' => 401,
        ]);
    }

    public function calculate_distance($lat1, $lon1, $lat2, $lon2)
    {
        $dist = $this->GetDrivingDistance($lat1, $lon1, $lat2, $lon2);
        return $dist;
    }

    // public function get_coordinates($address)
    // {
    //     $address = urlencode($address);
    //     $url = "https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=India&key=" . config('constants.GOOGLE_MAP_KEY');

    //     $client = new Client();
    //     $response = $client->request('GET', $url);
    //     $response_a = json_decode($response->getBody());

    //     $status = $response_a->status;
    //     if ($status == 'ZERO_RESULTS') {
    //         return false;
    //     } else {
    //         return [
    //             'lat' => $response_a->results[0]->geometry->location->lat,
    //             'long' => $response_a->results[0]->geometry->location->lng
    //         ];
    //     }
    // }

    public function getDrivingDistance($lat1, $long1, $lat2, $long2)
    {
        $apiKey = config('constants.GOOGLE_MAP_KEY');
        
        $origin = $lat1 . ',' . $long1;
    
        $destination = $lat2 . ',' . $long2;

        $url = 'https://maps.googleapis.com/maps/api/distancematrix/json';
    
        $response = Http::get($url, [
            'origins' => $origin,
            'destinations' => $destination,
            'key' => $apiKey
        ]);

        if ($response->successful()) {

            $data = $response->json();

            if ($data['status'] === 'OK' && $data['rows'][0]['elements'][0]['status'] === 'OK') {
            
                $distance = $data['rows'][0]['elements'][0]['distance']['text'];
                $duration = $data['rows'][0]['elements'][0]['duration']['text'];

                return [
                    'distance' => $distance,
                    'time' => $duration
                ];
                
            } else {
                return [
                        'distance' => 'N/A',
                        'time' => 'N/A',
                ];
            }
        } else {
            
                return [
                    'distance' => 'Error retrieving data',
                    'time' => 'Error retrieving data',
                ];
        }

    }

    // public function orderList(Request $request) {
   
    //     $user = Auth::user();

    //     $latitude =  $user->latitude;

    //     $longitude =  $user->longitude;

    //     $transferOrders = TransferOrder::where('status','!=', 4)->where('delivery_user_id', $user->id)
    //                     ->with(['Orders.user', 'Orders.address'])
    //                     ->get();
    //     $data = [];
        
    //     foreach($transferOrders as $value){

    //         $order =$value->orders;

    //         if(!$order){
    //             continue;
    //         }

    //         $userAddress = $order->address;

            
    //         $dist = $this->calculate_distance($latitude, $longitude, $userAddress->latitude, $userAddress->longitude);

    //         $data[] = [
    //             'transfer_order_id' => $value->id,
    //             'delivery_status'=> deliveryStatus($value->status),
    //             'order_id' => $value->order_id,
    //             'user_id' => $value->orders->user_id,
    //             'user_name' => $value->orders->user->first_name,
    //             'distance' => $dist['distance'] ?? '0',
    //             'time' => $dist['time'] ?? '0',
    //             'unit' => $dist['unit'] ?? 'Not Found',
    //         ];

    //     }

    //     return response()->json(['sucess' => true , 'message' => 'order featch SucessFully' , 'orders' => $data], 200);
    // }


    public function orderList(Request $request) {
        $user = Auth::user();
    
        $latitude = $request->latitude ?? $user->latitude;
        $longitude = $request->longitude ?? $user->longitude;
// return $request->latitude;
        // Fetch all transfer orders assigned to the current delivery boy

        // $transferOrders = TransferOrder::OrderBy('id','DESC')->where('status', '!=', 4) 
        //     ->where('delivery_user_id', $user->id) // Filter by delivery boy
        //     ->with(['orders.user', 'orders.address'])
        //     ->get();

        // $completeOrders = TransferOrder::OrderBy('id','DESC')->where('status', '=', 4) 
        //     ->where('delivery_user_id', $user->id) 
        //     ->with(['orders.user', 'orders.address'])
        //     ->get();

        $transferOrders = TransferOrder::orderBy('id', 'DESC')
        ->whereNotIn('status', [4, 5])
        ->where('delivery_user_id', $user->id)
        ->with(['orders.user', 'orders.address'])
        ->get();


        $completeOrders = TransferOrder::orderBy('id', 'DESC')
        ->where(function ($query) {
            $query->where('status', 4)
                ->orWhere('status', 5);
        })
        ->where('delivery_user_id', $user->id)
        ->with(['orders.user', 'orders.address'])
        ->get();

    
        $data = [];
        $completedata = [];
    
       
        foreach ($transferOrders as $value) {
            $order = $value->orders;
    
            // If order does not exist, skip this iteration
            if (!$order) {
                continue;
            }
    
            $userAddress = $order->address;
    
            // If user does not exist in the order, skip this iteration
            if (!$order->user) {
                continue;
            }
    
            // Calculate the distance between the delivery boy and the order's address
            $dist = $this->calculate_distanceee($latitude, $longitude, $userAddress->latitude, $userAddress->longitude);
            // return $latitude;
            // return $dist;
          
            $data[] = [
                'transfer_order_id' => $value->id,
                'delivery_status' => deliveryStatus($value->status),
                'order_id' => $value->order_id,
                'latitude' => $request->latitude ?? '',
                'longitude' => $request->longitude ?? '',
                'user_id' => $value->orders->user_id,
                'user_name' => $value->orders->user->first_name ?? 'N/A',  // Check if user exists and fallback to 'N/A'
                'shopname' => $value->orders->user->vendor->shopname ?? 'N/A',  // Check if user exists and fallback to 'N/A'
                'distance' => $dist['distance'] ?? '0', // Distance in km
                'time' => $dist['time'] ?? '0', // Estimated delivery time in minutes
                'unit' => $dist['unit'] ?? 'Not Found', // Distance unit
                'delivery_status' => $value->status, // Distance unit
            ];
        }

        foreach ($completeOrders as $value) {
            $order = $value->orders;
    
            // If order does not exist, skip this iteration
            if (!$order) {
                continue;
            }
    
            $userAddress = $order->address;
    
            // If user does not exist in the order, skip this iteration
            if (!$order->user) {
                continue;
            }
    
            // Calculate the distance between the delivery boy and the order's address
            $dist = $this->calculate_distanceee($latitude, $longitude, $userAddress->latitude, $userAddress->longitude);
            // return $latitude;
            // return $dist;
          
            $completedata[] = [
                'transfer_order_id' => $value->id,
                'delivery_status' => deliveryStatus($value->status),
                'order_id' => $value->order_id,
                'latitude' => $request->latitude ?? '',
                'longitude' => $request->longitude ?? '',
                'user_id' => $value->orders->user_id,
                'user_name' => $value->orders->user->first_name ?? 'N/A',  // Check if user exists and fallback to 'N/A'
                'shopname' => $value->orders->user->vendor->shopname ?? 'N/A', 
                'distance' => $dist['distance'] ?? '0', // Distance in km
                'time' => $dist['time'] ?? '0', // Estimated delivery time in minutes
                'unit' => $dist['unit'] ?? 'Not Found', // Distance unit
                'delivery_status' => $value->status, // Distance unit
            ];
        }
    
        // Sort orders by distance (nearest first)
        // usort($data, function ($a, $b) {
        //     return $a['distance'] <=> $b['distance'];
        // });
    
        // Return the sorted list of orders
        return response()->json([
            'success' => true,
            'message' => 'Orders fetched successfully.',
            'data' => [
                'orders' => $data,
                'complete_orders' => $completedata,
            ],
        ], 200);
        
    }

    public function calculate_distanceee($lat1, $lon1, $lat2, $lon2) {
        $earth_radius = 6371; 
 
        $dlat = deg2rad($lat2 - $lat1);
        $dlon = deg2rad($lon2 - $lon1);
    
        // Apply Haversine formula
        $a = sin($dlat / 2) * sin($dlat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dlon / 2) * sin($dlon / 2);
    
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earth_radius * $c; 
        $time = ($distance / 30) * 60; 
    
        return [
            'distance' => number_format($distance, 2), 
            'time' => number_format($time, 2), 
            'unit' => 'km', 
        ];
    }

    public function orderDetail(Request $request) {
   
        $validator = Validator::make($request->all(), [
            'transfer_id' => 'required|integer|exists:transfer_orders,id',
            'latitude'  => 'required',
            'longitude' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
        $user = Auth::user();

        $transferOrder = TransferOrder::with([
            'orders', 
            'orders.user', 
            'orders.address', 
            'orders.OrderDetails'
        ])
        ->where('status','!=', 4)
        ->where('id', $request->transfer_id) 
        ->where('delivery_user_id', $user->id) 
        ->first();
       
        if (!$transferOrder) {
            return response()->json([
                'success' => false,
                'message' => 'No order found or you do not have permission to view this order.',
            ], 404);
        }

        if ($transferOrder->orders->payment_type == 1) {

            $payment_type = 'Cash on delivery';

        } elseif ($transferOrder->orders->payment_type == 2) {

            $payment_type = 'All Ready Pay';
        }

        // $latitude =  $user->latitude;
       

        // $longitude =  $user->longitude;

        $latitude = $request->latitude ?? $user->latitude;
        $longitude = $request->longitude ?? $user->longitude;
        // return $latitude;
        
        $dist = $this->calculate_distanceee($latitude, $longitude, $transferOrder->orders->address->latitude, $transferOrder->orders->address->longitude);

        
        $data= [
            'order_id'    => $transferOrder->order_id,
            'user_id'     => $transferOrder->orders->user_id,
            'user_name'   => $transferOrder->orders->user->first_name,
            'shopname'   => $transferOrder->orders->user->vendor->shopname ?? 'N/A',
            'phone_no'    => $transferOrder->orders->user->contact,
            'address'     => $transferOrder->orders->address,
            'delivery_status'     => $transferOrder->status,
            'payment_type'=> $payment_type,
            'delivery_status'=> deliveryStatus($transferOrder->status),
             'order_detail' =>[
                'amount' => $transferOrder->orders->total_amount,
               'date' => $transferOrder->orders->created_at->format('d-m-y'),
                'no_of_product' => count($transferOrder->orders->OrderDetails),
                'distance' => $dist['distance'] ?? '0',
                'time' => $dist['time'] ?? '0',
                'unit' => $dist['unit'] ?? 'Not Found',
             ]
        ];
        
        return response()->json(['sucess' => true , 'message' => 'order featch SucessFully' , 'orders' => $data], 200);
    }

    public function productDetail(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:tbl_order1,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => 201
            ]);
        }

        $orderId = $request->input('order_id');

        $orderDetails = OrderDetail::with('product' ,'type')->where('main_id', $orderId)->get();

        if (!$orderDetails) {
            return response()->json([
                'success' => false,
                'message' => 'No Product found or you do not have permission to view this order.',
            ], 404);
        }

        $data = [];
        foreach ($orderDetails as $orderDetail) {
            $product =  $orderDetail->product;
            $unit = $orderDetail->type;

            $data[] = [
                'product_name' => $product->name ?? '',
                'image' => $product->img1 ? asset($product->img1) : '',
                'qty' => $orderDetail->quantity,
                'type' => $unit->type_name ?? '',
                'qty_price' => $unit->selling_price ?? '',
                'amount' => $orderDetail->amount
            ];
        }

        return response()->json([
            'message' => 'success',
            'status' => 200,
            'data' => $data,
        ]);
    }

    public function acceptOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transfer_id' => 'required|exists:transfer_orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => 400
            ]);
        }

        $order = TransferOrder::find($request->transfer_id);

        if ($order->status == 1) {
            return response()->json([
                'message' => 'Order has already been accepted.',
                'status' => 400
            ]);
        }

        $order->status = 1;
        $order->accepted_at = now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
        $order->save();

        Order::where('id',$order->order_id)->update(['delivery_status' => 2]);

        return response()->json([
            'message' => 'Order accepted successfully.',
            'status' => 200,
            'data' => [
                'delivery_status'=> deliveryStatus($order->status),
                'order_id' => $order->id,
                'accepted_at' => $order->accepted_at,
            ],
        ]);
    }

    public function orders(Request $request)
    {
        
        $deliveryBoy = Auth::user();

        $latitude =  $deliveryBoy->latitude;
        $longitude =  $deliveryBoy->longitude;
    
    
        $orders = TransferOrder::where('status', '>=', 1)->where('status','!=', 4)
                    ->where('delivery_user_id', $deliveryBoy->id)
                    ->join('tbl_order1', 'transfer_orders.order_id', '=', 'tbl_order1.id')
                    ->join('user_address', 'tbl_order1.address_id', '=', 'user_address.id')
                    ->with(['orders.user', 'orders.address', 'orders.orderDetails.product', 'orders.orderDetails.type'])
                    ->select('transfer_orders.*')
                    ->get();
    
        $groupedOrders = [];
    
        foreach ($orders as $transferOrder) {
            $order = $transferOrder->orders;
            $userAddress = $order->address;
            $user = $order->user;
    
            if (!$order || !$userAddress || !$user) {
                continue;
            }
    
            $orderDetails = $order->orderDetails;
            $orderInfo = [];
    
            foreach ($orderDetails as $orderDetail) {
                $product = $orderDetail->product;
                $unit = $orderDetail->type;
    
                $orderInfo[] = [
                    'product_id' => $orderDetail->product_id,
                    'product_name' => $product->name ?? '',
                    'image' => $product->img1 ? asset($product->img1) : '',
                    'qty' => $orderDetail->quantity,
                    'type' => $unit->type_name ?? '',
                    'amount' => $orderDetail->amount
                ];
            }
    
            $dist = $this->calculate_distance($latitude, $longitude, $userAddress->latitude, $userAddress->longitude);
    
            $orderData = [
                'delivery_status'=> deliveryStatus($transferOrder->status),
                'order_id' => $transferOrder->order_id,
                'final_amount' => $order->total_amount,
                'user_address' => $userAddress->address ?? '',
                'phone' => $user->contact ?? '',
                'user_name' => $user->first_name ?? '',
                'latitude' => $userAddress->latitude ?? '',
                'longitude' => $userAddress->longitude ?? '',
                'pincode' => $userAddress->zipcode ?? '',
                'total_product' => $orderDetails->count(),
                'payment_method' => $order->payment_type == 1 ? 'Cash on Delivery' : 'Paid',
                'live_address' => $userAddress->address ?? '',
                'distance' => $dist['distance'] ?? '0',
                'time' => $dist['time'] ?? '0',
                'unit' => $dist['unit'] ?? 'Not Found',
                'order_details' => $orderInfo,
            ];
    
            $groupedOrders[$userAddress->zipcode][] = $orderData;
        }

        foreach ($groupedOrders as $zipcode => &$orders) {
            usort($orders, function($a, $b) {
                return $a['distance'] <=> $b['distance'];
            });
        }
        ksort($groupedOrders);
    
        return response()->json([
            'message' => 'Success',
            'status' => 200,
            'data' => $groupedOrders,
        ]);
    }
    
    // public function startDelivery(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'transfer_id'  => 'required|integer|exists:transfer_orders,id',
    //         'latitude'     => 'required|numeric',
    //         'longitude'    => 'required|numeric'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => $validator->errors()->first(),
    //             'status' => 400
    //         ]);
    //     }

    //     $orderId   = $request->input('transfer_id');
    //     $latitude  = $request->input('latitude');
    //     $longitude = $request->input('longitude');
    //     $ride = $request->input('ride');
    //     $startTime = now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'); 

    //     $user = Auth::user();

    //     $ongoingOrder = TransferOrder::where('status', 2)
    //         ->orWhere('status', 3)
    //         ->where('delivery_user_id', $user->id)
    //         ->first();

    //     if ($ongoingOrder) {
    //         if ($ongoingOrder->id != $orderId) {

    //             return response()->json([
    //                 'message' => 'First complete your ongoing order before accepting a new one.',
    //                 'status'  => 400
    //             ]);
    //         }
    //     }

    //     $deliveryOrder = TransferOrder::with('Orders.address')->where('id', $orderId)
    //         ->where('delivery_user_id', $user->id)
    //         ->first();

    //     if ($deliveryOrder) {
    //         $order = $deliveryOrder->Orders;
    //         if (!$order) {
    //             return response()->json([
    //                 'message' => 'Order details not found.',
    //                 'status'  => 400
    //             ]);
    //         }

    //         Order::where('id',$order->order_id)->update(['delivery_status' => 2]);

    //         $userAddress = $order->address;
    //         if (!$userAddress) {
    //             return response()->json([
    //                 'message' => 'Address details not found.',
    //                 'status'  => 400
    //             ]);
    //         }

    //         $deliveryOrder->update([
    //             'status' => 2, 
    //             'start_location' => "$latitude,$longitude",
    //             'start_time' => $startTime
    //         ]);

    //         return response()->json([
    //             'message' => 'Delivery started successfully',
    //             'status' => 200,
    //             'data' => [
    //                 'order_id' => $deliveryOrder->id,
    //                 'delivery_status'=> deliveryStatus(2),
    //                 'start_location' => $deliveryOrder->start_location,
    //                 'start_time' => $deliveryOrder->start_time,
    //                 'user_address' => [
    //                     'name' => $userAddress->name,
    //                     'address' => $userAddress->address,
    //                     'landmark' => $userAddress->landmark,
    //                     'doorflat' => $userAddress->doorflat,
    //                     'latitude' => $userAddress->latitude,
    //                     'longitude' => $userAddress->longitude,
    //                     'location_address' => $userAddress->location_address,
    //                     'city' => $userAddress->citys->city_name,
    //                     'state' => $userAddress->states->state_name,
    //                     'zipcode' => $userAddress->zipcode,
    //                 ],
    //             ]
    //         ]);

    //     } else {
    //         return response()->json([
    //             'message' => 'Order not found or not assigned to you.',
    //             'status'  => 400
    //         ]);
    //     }
    // }



    public function startDelivery(Request $request)
{
    $validator = Validator::make($request->all(), [
        'transfer_id'  => 'required|integer|exists:transfer_orders,id',
        'latitude'     => 'required|numeric',
        'longitude'    => 'required|numeric',
        'ride'          => 'required|integer|in:1,2', // Ensure 'ride' input is either 1 or 2
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => $validator->errors()->first(),
            'status' => 400
        ]);
    }

    $orderId   = $request->input('transfer_id');
    $latitude  = $request->input('latitude');
    $longitude = $request->input('longitude');
    $ride = $request->input('ride');
    $startTime = now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'); 

    $user = Auth::user();
        $ongoingOrder = TransferOrder::where(function($query) {
            $query->where('status', 2)
                ->orWhere('status', 3);
        })
        ->where('delivery_user_id', $user->id)
        ->first();

    if ($ongoingOrder) {
        if ($ongoingOrder->id != $orderId) {
            return response()->json([
                'message' => 'First complete your ongoing order before accepting a new one.',
                'status'  => 400
            ]);
        }
    }

    $deliveryOrder = TransferOrder::with('Orders.address')->where('id', $orderId)
        ->where('delivery_user_id', $user->id)
        ->first();

    if ($deliveryOrder) {
        $order = $deliveryOrder->Orders;
        if (!$order) {
            return response()->json([
                'message' => 'Order details not found.',
                'status'  => 400
            ]);
        }

        if($user->role_type == 2){
        Order::where('id',$deliveryOrder->order_id)->update(['delivery_status' => 2, 'order_status' => 3]);
        $this->sendPushNotificationVendor($user->fcm_token, $order->order_status);
        }else{
            Order::where('id',$deliveryOrder->order_id)->update(['delivery_status' => 2]);
        }
        $userAddress = $order->address;
        if (!$userAddress) {
            return response()->json([
                'message' => 'Address details not found.',
                'status'  => 400
            ]);
        }

        // Handle ride = 1 (just start delivery without location check)
        if ($ride == 1) {
            // Order::where('id',$order->order_id)->update(['order_status' => 3]);
            $deliveryOrder->update([
                'status' => 2, 
                'start_location' => "$latitude,$longitude",
                'start_time' => $startTime
            ]);

            return response()->json([
                'message' => 'Delivery started successfully',
                'status' => 200,
                'data' => [
                    'order_id' => $deliveryOrder->id,
                    'delivery_status'=> deliveryStatus(2),
                    'start_location' => $deliveryOrder->start_location,
                    'start_time' => $deliveryOrder->start_time,
                    'user_address' => [
                        'name' => $userAddress->name,
                        'address' => $userAddress->address,
                        'landmark' => $userAddress->landmark,
                        'doorflat' => $userAddress->doorflat,
                        'latitude' => $userAddress->latitude,
                        'longitude' => $userAddress->longitude,
                        'location_address' => $userAddress->location_address,
                        'city' => $userAddress->citys->city_name,
                        'state' => $userAddress->states->state_name,
                        'zipcode' => $userAddress->zipcode,
                    ],
                ]
            ]);
        }

        // Handle ride = 2 (check if rider is within 500 meters)
        if ($ride == 2) {
            // Calculate distance using the Haversine formula
            $distance = $this->calculateDistance($latitude, $longitude, $userAddress->latitude, $userAddress->longitude);

            // Check if the rider is within 500 meters
            if ($distance <= 100.0) { // 500 meters = 0.5 kilometers
                $deliveryOrder->update([
                    'status' => 2, 
                    'start_location' => "$latitude,$longitude",
                    'start_time' => $startTime
                ]);

                return response()->json([
                    'message' => 'Delivery started successfully',
                    'status' => 200,
                    'data' => [
                        'order_id' => $deliveryOrder->id,
                        'delivery_status'=> deliveryStatus(2),
                        'start_location' => $deliveryOrder->start_location,
                        'start_time' => $deliveryOrder->start_time,
                        'vendor' => 'vendor',
                        'latitude' => $latitude ?? '',
                        'longitude' => $longitude ?? '',
                        'user_address' => [
                            'name' => $userAddress->name,
                            'address' => $userAddress->address,
                            'landmark' => $userAddress->landmark,
                            'doorflat' => $userAddress->doorflat,
                            'latitude' => $userAddress->latitude,
                            'longitude' => $userAddress->longitude,
                            'location_address' => $userAddress->location_address,
                            'city' => $userAddress->citys->city_name,
                            'state' => $userAddress->states->state_name,
                            'zipcode' => $userAddress->zipcode,
                        ],
                    ]
                ]);
            } else {
                return response()->json([
                    'message' => 'Within 1 Kilometer of delivery address to End.',
                    'status' => 400
                ]);
            }
        }

    } else {
        return response()->json([
            'message' => 'Order not found or not assigned to you.',
            'status'  => 400
        ]);
    }
}

private function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371; // Earth radius in kilometers

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $distance = $earthRadius * $c; // Distance in kilometers

    return $distance; // Return the distance in kilometers
}

    public function completeOrder(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'transfer_id'  => 'required|integer|exists:transfer_orders,id',
            'latitude'     => 'required|numeric',
            'longitude'    => 'required|numeric',
            'paymentType'  => 'required|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => 400
            ]);
        }

        $transferId  = $request->input('transfer_id');
        $latitude    = $request->input('latitude');
        $longitude   = $request->input('longitude');
        $paymentType = $request->input('payment_type');
        $endTime     = now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'); 

        $imagePath = '';
        if ($request->hasFile('image')) {

            $imagePath = uploadImage($request->file('image'), 'complete_order');
            
        }

        $user = Auth::user();

        $deliveryUserId = $user->id;
      
        $transfer = TransferOrder::where('id',$transferId)
            ->where('delivery_user_id', $deliveryUserId)
            ->first();

        if (!$transfer) {
            return response()->json([
                'message' => 'No data found',
                'status' => 201
            ]);
        }

        $updated = TransferOrder::where('id', $transfer->id)
            ->update([
                'status' => 4,
                'payment_type' => $paymentType,
                'image' => $imagePath,
                'end_location' => "$latitude,$longitude",
                'end_time' => $endTime
            ]);

 
        Order::where('id',$transfer->order_id)->update(['delivery_status' => 3 , 'order_status' => 4]);
        
        $orderdata = Order::find($transfer->order_id);
        $users = User::find($orderdata->user_id);
        $this->sendPushNotificationVendor($users->fcm_token, $orderdata->order_status);

        if ($paymentType != 'User did not accept order') {

            $totalAmount =  Order::where('id',$transfer->order_id)->value('total_amount');
          
            DeliveryAmount::insert([
                'deluser_id' => $deliveryUserId,
                'amount' => $totalAmount,
                'payment_type' => $paymentType,
                'ip' => $request->ip(),
                'added_by' =>  $deliveryUserId,
                'is_active' =>  1,
                'date' =>  now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
            ]);
            
        }

        if ($updated) {
            return response()->json([
                'message' => 'success',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Error occurred',
                'status' => 201
            ]);
        }
    }

    private function sendPushNotificationVendor($fcm_token,$status) {
        if($status == 3){
        $title = 'Order Complete!';
        $message = 'Your order has been Complete.';
    }else{
        $title = 'Order Dispatch!';
        $message = 'Your order has been Dispatch.';
    }
        if ($fcm_token != null) {
            $response = $this->firebaseService->sendNotificationToUser($fcm_token, $title, $message);
    
            if (!$response['success']) {
                Log::error('FCM send error: ' . $response['error']);
                Log::error('FCM full response: ' . json_encode($response)); // Log full response for debugging
            }
        } else {
            Log::error('FCM token is null or invalid.');
        }
    }

    public function currentLocation(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'address'   => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'status'  => 400
            ], 400);
        }

        $user = DeliveryBoy::find(Auth::user()->id);

        $user->update([
            'latitude'  => round($request->latitude, 6),
            'longitude' => round($request->longitude, 6), 
            'address'   => $request->address ?? ''
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'delivery_boy_status' =>  $user->is_active == 1 ? 'Active' : 'Inactive',
        ], 200);
    }

    public function updateFcm(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'delivery_boy_id'   => 'required|integer|exists:delivery_boy,id',
            'fcm_token' => 'required|string',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = DeliveryBoy::find($request->delivery_boy_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'status' => 401,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'success'=>true,
            'status' => 200,
            'message' => 'FCM token updated successfully'
        ], 200);
    }

}
