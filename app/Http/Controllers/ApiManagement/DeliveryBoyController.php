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


class DeliveryBoyController extends Controller
{
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
            ], 422);
        }

        $deliveryBoy = DeliveryBoy::where('email', $request->email)->first();

        if (!$deliveryBoy || !Hash::check(trim($request->password), $deliveryBoy->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
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
            $completedOrders = TransferOrder::where('delivery_user_id', $deliveryBoy->id)->where('status', 4)->count();
        
            // Prepare response data
            $data = [
                'wallet_amount' => formatPrice($tamount),
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

    public function orderList(Request $request) {
   
        // $validator = Validator::make($request->all(), [
        //     'latitude'  => 'required',
        //     'longitude' => 'required'
        // ]);
    
        // if ($validator->fails()) {
        //     return response()->json([
        //         'message' => $validator->errors()->first(),
        //         'status' => 400
        //     ]);
        // }
       
        $user = Auth::user();

        $latitude =  $user->latitude;
        
        $longitude =  $user->longitude;

        $transferOrders = TransferOrder::where('status','!=', 4)->where('delivery_user_id', $user->id)
                        ->with(['Orders.user', 'Orders.address'])
                        ->get();
                        
        $data = [];
        
        foreach($transferOrders as $value){

            $order =$value->orders;

            if(!$order){
                continue;
            }

            $userAddress = $order->address;

            
            $dist = $this->calculate_distance($latitude, $longitude, $userAddress->latitude, $userAddress->longitude);

            $data[] = [
                'transfer_order_id' => $value->id,
                'delivery_status'=> deliveryStatus($value->status),
                'order_id' => $value->order_id,
                'user_id' => $value->orders->user_id,
                'user_name' => $value->orders->user->first_name,
                'distance' => $dist['distance'] ?? '0',
                'time' => $dist['time'] ?? '0',
                'unit' => $dist['unit'] ?? 'Not Found',
            ];

        }

        return response()->json(['sucess' => true , 'message' => 'order featch SucessFully' , 'orders' => $data], 200);
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

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        
        $dist = $this->calculate_distance($latitude, $longitude, $transferOrder->orders->address->latitude, $transferOrder->orders->address->longitude);

        $data= [
            'order_id'    => $transferOrder->order_id,
            'user_id'     => $transferOrder->orders->user_id,
            'user_name'   => $transferOrder->orders->user->first_name,
            'phone_no'    => $transferOrder->orders->user->contact,
            'address'     => $transferOrder->orders->address,
            'payment_type'=> $payment_type,
            'delivery_status'=> deliveryStatus($transferOrder->status),
             'order_detail' =>[
                'amount' => $transferOrder->orders->total_amount,
                'date'   => $transferOrder->orders->created_at,
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
        $validator = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => 400
            ]);
        }
    
        $latitude = $request->latitude;
        $longitude = $request->longitude;
    
        $deliveryBoy = Auth::user();
    
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
    
    public function startDelivery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transfer_id'  => 'required|integer|exists:transfer_orders,id',
            'latitude'     => 'required|numeric',
            'longitude'    => 'required|numeric'
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
        $startTime = now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'); 

        $user = Auth::user();

        $ongoingOrder = TransferOrder::where('status', 2)
            ->orWhere('status', 3)
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

            Order::where('id',$order->order_id)->update(['delivery_status' => 2]);

            $userAddress = $order->address;
            if (!$userAddress) {
                return response()->json([
                    'message' => 'Address details not found.',
                    'status'  => 400
                ]);
            }

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

        } else {
            return response()->json([
                'message' => 'Order not found or not assigned to you.',
                'status'  => 400
            ]);
        }
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
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
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
