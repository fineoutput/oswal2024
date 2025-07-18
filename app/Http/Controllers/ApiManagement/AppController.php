<?php

namespace App\Http\Controllers\ApiManagement;

use Illuminate\Support\Facades\Validator;

use App\Models\WalletTransactionHistory;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\ShippingCharge;

use Illuminate\Http\Request;

use App\Models\VendorReward;

use App\Models\GiftCardSec;

use App\Models\Websliders2;

use App\Models\Promocode;

use App\Models\GiftCard;

use App\Models\Slider2;

use App\Models\Address;

use App\Models\Comment;

use App\Models\Slider;

use App\Models\Reward;

use App\Models\Popup;

use App\Models\State;

use App\Models\Blog;

use App\Models\City;

use App\Models\User;
use App\Models\Popupimage;

class AppController extends Controller
{

    public function popupimage(Request $request)
    {

        $user_id = 0;
        $role_type = null; // Initialize to null
        
        // Check if there is an Authorization header
        if ($request->header('Authorization')) {
            $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $userDetails = User::where('auth', $auth_token)->first();
        
            if ($userDetails) {
                $device_id = $userDetails->device_id;
                $user_id = $userDetails->id;
                $role_type = $userDetails->role_type;
            }
        }
        
        $user = User::where('id', $user_id)->first();
        $latestPopupImage = Popupimage::where('status', '1')->latest()->first();
        
        // Check if the user is logged in or if the role_type is 1 or user_id is 0
        if ($role_type == 2) {
            // If role_type is 2, deny access to the image
            return response()->json([
                'success' => false,
                'message' => 'Role type 2 does not have permission to view image'
            ], 200);  // HTTP status code 403 (Forbidden)
        }
        
        // If the user is logged in with role_type 1 or user_id is 0 (non-logged-in user)
        if ($role_type == 1 || $user_id == 0) {
            // Check if a valid popup image exists
            if ($latestPopupImage) {
                $imageUrl = asset('uploads/popup_images/' . basename($latestPopupImage->image));
                
                return response()->json([
                    'success' => true,
                    'image_url' => $imageUrl
                ], 200);
            } else {
                // If there's no popup image, return success with a message
                return response()->json([
                    'success' => true,
                    'message' => 'No popup image found'
                ], 200);
            }
        }
        
        // If the user is neither logged in nor has role_type 1, return failure response
        return response()->json([
            'success' => false,
            'message' => 'User not logged in or role type not found'
        ], 401); 
    }
    
    public function blog()
    {

        $blogs = Blog::with('comment')->orderby('id', 'desc')->where('is_active', 1)->get();

        return response()->json(['success' => true, 'data' => $blogs], 200);
    }

    public function add_blog_comment(Request $request)
    {

        $routeName = Route::currentRouteName();

        $validationRules = [
            'name'       => 'required|string',
            'email'      => 'required|email',
            'comment'    => 'required|string',
            'blog_id'    => 'required|numeric',
        ];

        if ($routeName === 'blog.edit-comment') {
            $validationRules['comment_id'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {

            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        if (!isset($request->comment_id)) {

            $comment =  new Comment;

            $comment->ip = $request->ip();

            $comment->cur_date = now();

            $comment->reply_status = 0;

            $comment->is_active = 1;
        } else {

            $comment = Comment::find($request->comment_id);

            if (!$comment) {

                return response()->json(['success' => false, 'message' => 'Comment not found.'], 404);
            }
        }

        $comment->fill($request->all());

        if ($comment->save()) {

            $message = isset($request->comment_id) ? 'Comment updated successfully.' : 'Comment inserted successfully.';

            return response()->json(['success' => true, 'message' => $message], 200);
        } else {

            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again later.'], 500);
        }
    }

    public function GetState()
    {

        $states = State::OrderBy('state_name', 'ASC')->get();

        return response()->json(['success' => true, 'data' => $states], 200);
    }

    public function GetCity($sid = null)
    {

        $citys = City::OrderBy('city_name', 'ASC');

        if ($sid != null) {

            $citys = $citys->where('state_id', $sid);
        }

        $citys = $citys->get();

        return response()->json(['success' => true, 'data' => $citys], 200);
    }

    public function addAddress(Request $request)
    {
        // dd('sfsdf');
        $validator = Validator::make($request->all(), [
            'doorflat'       => 'required|string',
            'landmark'       => 'required|string',
            'city_id'        => 'required|integer',
            'state_id'       => 'required|integer',
            'zipcode'        => 'required|integer',
            'address'        => 'nullable|string',
            'latitude'       => 'nullable|string',
            'longitude'      => 'nullable|string',
            'location_address'  => 'nullable|string',

        ]);

        $user_id = 0;
        if ($request->header('Authorization')) {
            $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $userDetails = User::where('auth', $auth_token)->first();
            if ($userDetails) {
                $device_id = $userDetails->device_id;
                $user_id = $userDetails->id;
                $role_type = $userDetails->role_type;
            }
        }

        $user = User::where('id',$user_id)->first();
        // return $user;

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
        }

        $city = City::find($request->city_id);

        $state_id = $city ? $city->state_id : null;

        $shippingData = ShippingCharge::where('city_id', $city->id)->first();

        if (!$shippingData) {
            return response()->json(['success' => false, 'message' => 'Shipping services not available in this area.'], 400);
        }

        $custom_address = $request->doorflat . " " . $request->landmark . " " . $request->address . " " . $city->city_name . " " . $city->state->state_name . " " . $request->zipcode . " " . 'India';

        // $location = getLatLngFromAddress($custom_address);

        // if (!getLatLngFromAddress($custom_address)) {
        //     $live_location = "not able to get from google";
        //     // return response()->json(['success' => false, 'message' => 'Address Not Found.'], 400);
        // }
        // else{
        //     $live_location =
        // }


        $addressData = [
            'user_id'          =>  $user->id,
            'name'             => $user->first_name,
            'doorflat'         => $request->doorflat,
            'landmark'         => $request->landmark,
            'city'             => strval($city->id),
            'state'            => strval($state_id),
            'zipcode'          => $request->zipcode,
            'address'          => $request->address,
            'latitude'         => round($request->latitude, 6),
            'longitude'        => round($request->longitude, 6),
            'location_address' => $request->location_address,
            'date'             => now()->setTimezone('Asia/Calcutta')->toDateTimeString(),
        ];

        $userAddress = Address::create($addressData);

        $userAddress->load('states', 'citys');

        $response = $userAddress;

        $response['custom_address'] = $custom_address;

        return response()->json(['success' => true, 'message' => 'Address Added Sucessfully.', 'data' =>  $response], 201);
    }

    public function getAddress(Request $request)
    {
        $user_id = 0;
        if ($request->header('Authorization')) {
            $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $userDetails = User::where('auth', $auth_token)->first();
            if ($userDetails) {
                $device_id = $userDetails->device_id;
                $user_id = $userDetails->id;
                $role_type = $userDetails->role_type;
            }
        }

        // return $user_id;

        // $user_id   = auth()->id();
        $address_data = [];
        $addresses = Address::where('user_id', $user_id)->get();
        foreach ($addresses as $address) {

            $address->load('states', 'citys');

            $addr_string = "Doorflat {$address->doorflat}, ";

            if (!empty($address->landmark)) {
                $addr_string .= "{$address->landmark}, ";
            }
            $addr_string .= "{$address->address}, {$address->zipcode}";

            $address['custom_address'] = $addr_string;

            $address_data[] =  $address;
        }

        return response()->json(['success' => true, 'data' =>  $address_data], 200);
    }

    public function headerSlider(Request $request)
    {
        $role_type = 1;
        if ($request->header('Authorization')) {
            $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $userDetails = User::where('auth', $auth_token)->first();
            if ($userDetails) {
                $device_id = $userDetails->device_id;
                $user_id = $userDetails->id;
                $role_type = $userDetails->role_type;
            }
        }

        $sliders =  Websliders2::where('is_active', 1)->get();

        $data = [];

        foreach ($sliders as $key => $value) {

            if ($request->role_type == 2) {

                if ($value->vendor_image == null) {

                    continue;
                }

                $data[] = [
                    'id'    => $value->id,
                    'url'   => $value->vendor_link,
                    'image' => asset($value->vendor_image),
                ];
            } else {

                if ($value->app_img == null) {

                    continue;
                }

                $data[] = [
                    'id'    => $value->id,
                    'url'   => $value->app_link,
                    'image' => asset($value->app_img),
                ];
            }
        }

        return response()->json(['success' => true, 'data' =>  $data], 200);
    }

    public function footerSlider(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'lang'      => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $role_type = 1;
        if ($request->header('Authorization')) {
            $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $userDetails = User::where('auth', $auth_token)->first();
            if ($userDetails) {
                $device_id = $userDetails->device_id;
                $user_id = $userDetails->id;
                $role_type = $userDetails->role_type;
            }
        }

        $sliders =  Slider2::where('is_active', 1)->get();

        $data = [];

        foreach ($sliders as $key => $value) {

            if ($request->role_type == 2) {

                if ($value->vendor_image == null) {
                    continue;
                }

                $data[] = [
                    'id'            => $value->id,
                    'slider_name'   => ($request->lang == 'hi') ? $value->vendor_slider_name_hi : $value->vendor_slider_name,
                    'image'         => asset($value->vendor_image),
                ];
            } else {

                if ($value->app_image == null) {
                    continue;
                }

                $data[] = [
                    'id'            => $value->id,
                    'slider_name'   => ($request->lang == 'hi') ? $value->app_slider_name_hi : $value->app_slider_name,
                    'image'         => asset($value->app_image),
                ];
            }
        }

        return response()->json(['success' => true, 'data' =>  $data], 200);
    }

    public function festivalSlider(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'lang'      => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $role_type = 1;
        if ($request->header('Authorization')) {
            $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $userDetails = User::where('auth', $auth_token)->first();
            if ($userDetails) {
                $device_id = $userDetails->device_id;
                $user_id = $userDetails->id;
                $role_type = $userDetails->role_type;
            }
        }

        $sliders =  Slider::where('is_active', 1)->get();

        $data = [];

        foreach ($sliders as $key => $value) {
            if ($request->role_type == 2) {

                if ($value->vendor_image == null) {
                    continue;
                }

                $data[] = [
                    'id'            => $value->id,
                    'slider_name'   => ($request->lang == 'hi') ? $value->vendor_slider_name_hi : $value->vendor_slider_name,
                    'image'         => asset($value->vendor_image),
                ];
            } else {

                if ($value->app_image == null) {
                    continue;
                }

                $data[] = [
                    'id'            => $value->id,
                    'slider_name'   => ($request->lang == 'hi') ? $value->app_slider_name_hi : $value->app_slider_name,
                    'image'         => asset($value->app_image),
                ];
            }
        }

        return response()->json(['success' => true, 'data' =>  $data], 200);
    }



    public function allSliders(Request $request)
{
    $validator = Validator::make($request->all(), [
        'lang' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
    }

    $role_type = 1;

    // Get role_type from Authorization token (if exists)
    if ($request->header('Authorization')) {
        $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
        $userDetails = User::where('auth', $auth_token)->first();

        if ($userDetails) {
            $role_type = $userDetails->role_type;
        }
    }

    // Step 2: Agar request me explicitly role_type diya gaya ho, to use overwrite karo
    if ($request->has('role_type')) {
        $role_type = (int) $request->input('role_type');
    }

    // ------------------------------------
    // 🎯 1. Footer Sliders
    // ------------------------------------
    $footerSliders = Slider2::where('is_active', 1)->get();
    $footerData = [];

    foreach ($footerSliders as $slider) {
        if ($role_type == 2) {
            if (!$slider->vendor_image) continue;
            $footerData[] = [
                'id'          => $slider->id,
                'slider_name' => ($request->lang == 'hi') ? $slider->vendor_slider_name_hi : $slider->vendor_slider_name,
                'image'       => asset($slider->vendor_image),
            ];
        } else {
            if (!$slider->app_image) continue;
            $footerData[] = [
                'id'          => $slider->id,
                'slider_name' => ($request->lang == 'hi') ? $slider->app_slider_name_hi : $slider->app_slider_name,
                'image'       => asset($slider->app_image),
            ];
        }
    }

    // ------------------------------------
    // 🎯 2. Header Sliders
    // ------------------------------------
    $headerSliders = Websliders2::where('is_active', 1)->get();
    $headerData = [];

    foreach ($headerSliders as $slider) {
        if ($role_type == 2) {
            if (!$slider->vendor_image) continue;
            $headerData[] = [
                'id'    => $slider->id,
                'url'   => $slider->vendor_link,
                'image' => asset($slider->vendor_image),
            ];
        } else {
            if (!$slider->app_img) continue;
            $headerData[] = [
                'id'    => $slider->id,
                'url'   => $slider->app_link,
                'image' => asset($slider->app_img),
            ];
        }
    }

    // ------------------------------------
    // 🎯 3. Festival Sliders
    // ------------------------------------
    $festivalSliders = Slider::where('is_active', 1)->get();
    $festivalData = [];

    foreach ($festivalSliders as $slider) {
        if ($role_type == 2) {
            if (!$slider->vendor_image) continue;
            $festivalData[] = [
                'id'          => $slider->id,
                'slider_name' => ($request->lang == 'hi') ? $slider->vendor_slider_name_hi : $slider->vendor_slider_name,
                'image'       => asset($slider->vendor_image),
            ];
        } else {
            if (!$slider->app_image) continue;
            $festivalData[] = [
                'id'          => $slider->id,
                'slider_name' => ($request->lang == 'hi') ? $slider->app_slider_name_hi : $slider->app_slider_name,
                'image'       => asset($slider->app_image),
            ];
        }
    }

    // ------------------------------------
    // ✅ Final Combined Response
    // ------------------------------------
    return response()->json([
        'success'         => true,
        'footer_sliders'  => $footerData,
        'header_sliders'  => $headerData,
        'festival_sliders'=> $festivalData,
    ], 200);
}

    public function getPromoCode()
    {

        $promocodes  =  Promocode::where('is_active', 1)->get();

        return response()->json(['success' => true, 'data' =>  $promocodes], 200);
    }

    public function giftCard()
    {

        $giftcards  =  giftCard::where('is_active', 1)->get();

        $data = [];

        foreach ($giftcards as $key => $value) {
            $data[] = [
                'id'              => $value->id,
                'name'            => $value->name,
                'description'     => $value->description,
                'price'           => $value->price,
                'image'           => asset($value->image),
            ];
        }

        return response()->json(['success' => true, 'data' =>  $data], 200);
    }

    public function giftCardSec(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_id'   => 'required|integer|exists:ecom_products,id',
            'type_id'      => 'required|integer|exists:types,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $giftcards  =  giftCardSec::where('product_id', $request->product_id)->where('type_id', $request->type_id)->where('is_active', 1)->get();

        $data = [];

        foreach ($giftcards as $key => $value) {
            $data[] = [
                'id'              => $value->id,
                'name'            => $value->name,
                'price'           => $value->price,
                'image'           => asset($value->appimage),
            ];
        }

        return response()->json(['success' => true, 'data' =>  $data], 200);
    }

    public function popup(Request $request)
    {

        $popups =  Popup::where('is_active', 1)->get();

        $data = [];

        foreach ($popups  as $key => $value) {
            $data[] = [
                'id'              => $value->id,
                'name'            => $value->name,
                'image'           => asset($value->image),
            ];
        }

        return response()->json(['success' => true, 'data' =>  $data], 200);
    }


    public function walletTransaction(Request $request)
    {

        $userId = 0;
        if ($request->header('Authorization')) {
            $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $userDetails = User::where('auth', $auth_token)->first();
            if ($userDetails) {
                $device_id = $userDetails->device_id;
                $userId = $userDetails->id;
                $role_type = $userDetails->role_type;
            }
        }


        $completedTransactions = WalletTransactionHistory::getByStatus(
            WalletTransactionHistory::STATUS_COMPLETED,
            $userId
        );

        $data = [];

        foreach ($completedTransactions as $value) {

            if ($value->amount == "0.0") {
                continue;
            }


            $data[] = [
                'user'             => $value->user_id,
                'transaction_type' => $value->transaction_type,
                'amount'           => $value->amount,
                'description'      => $value->description,
                'date'             => date('Y-m-d', strtotime($value->created_at)) . ' | ' . date('H:i:s A', strtotime($value->created_at)),
            ];
        }

        return response()->json(['success' => true, 'data' =>  $data], 200);
    }

    // public function giveRating(Request $request) {

    //     $validator = Validator::make($request->all(), [
    //         'device_id'   => 'required|string|exists:users,device_id',
    //         'user_id'     => 'required|string|exists:users,id',
    //         'product_id'  => 'required|integer|exists:ecom_products,id',
    //         'category_id' => 'required|integer|exists:ecom_categories,id',
    //         'rating'      => 'required|integer|min:1|max:5',
    //         'description' => 'required|string|max:1000',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first()
    //         ], 400);
    //     }

    //     $productRating = new ProductRating();
    //     $productRating->device_id   = $request->input('device_id');
    //     $productRating->product_id  = $request->input('product_id');
    //     $productRating->category_id = $request->input('category_id');
    //     $productRating->rating      =  (float) $request->input('rating');
    //     $productRating->description = $request->input('description');
    //     $productRating->description_hi = lang_change($request->input('description'));
    //     $productRating->ip  = $request->ip();
    //     $productRating->date  = now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
    //     $productRating->save();

    //     return response()->json(['success' => true,'message' => 'Rating submitted successfully','data' => $productRating], 201);
    // }
    public function giveRating(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_id'    => 'required|integer|exists:tbl_order1,id',
            'rating'      => 'required|integer|min:1|max:5',
            'description' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        
        $user_id = 0;
        if ($request->header('Authorization')) {
            $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $userDetails = User::where('auth', $auth_token)->first();
            if ($userDetails) {
                $device_id = $userDetails->device_id;
                $user_id = $userDetails->id;
                $role_type = $userDetails->role_type;
            }
        }

        $user = User::where('id',$user_id)->first();

        $data = [
            'device_id'     =>  $user->device_id,
            'user_id'       =>  $user->id,
            'order_id'      => $request->input('order_id'),
            'rating'        => (float) $request->input('rating'),
            'description'   => $request->input('description'),
            'description_hi' => lang_change($request->input('description')),
            'ip'            => $request->ip(),
            'date'          => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
        ];

        $inserted = DB::table('order_ratings')->insert($data);

        if ($inserted) {
            return response()->json([
                'success' => true,
                'message' => 'Rating submitted successfully',
                'data'    => $data
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit rating'
            ], 500);
        }
    }

    public function getWalletAmount(Request $request)
    {

        $user_id = 0;
        if ($request->header('Authorization')) {
            $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $userDetails = User::where('auth', $auth_token)->first();
            if ($userDetails) {
                $device_id = $userDetails->device_id;
                $user_id = $userDetails->id;
                $role_type = $userDetails->role_type;
            }
        }

        $user = User::where('id',$user_id)->first();
        // return $user;

        $data = [
            'wallet_amount' => $user->wallet_amount ?? '',
        ];

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function updateFcm(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string',
        ]);

        $user_id = 0;
        if ($request->header('Authorization')) {
            $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $userDetails = User::where('auth', $auth_token)->first();
            if ($userDetails) {
                $device_id = $userDetails->device_id;
                $user_id = $userDetails->id;
                $role_type = $userDetails->role_type;
            }
        }
    
        // Log::info("fcm_token: " . $request->fcm_token);
        // Log::info("auth: " . $request->header('Authorization'));
        // If validation fails, return an error
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = User::where('id',$user_id)->first();  
    
        // If the user is not found, return an error
        if (!$user) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'User not found'
            ], 404);
        }
    
        // Update the FCM token for the user
        $user->fcm_token = $request->fcm_token;
        $user->save();
    
        // Return a successful response
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'FCM token updated successfully'
        ], 200);
    }

    // public function updateFcm(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'fcm_token' => 'required|string',
    //     ]);


    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first()
    //         ], 400);
    //     }
    //     $device_id = auth()->user()->device_id;
    //     $user_id = auth()->user()->id;

    //     $user = User::where('device_id', $device_id)->orwhere('id', $user_id)->first();

    //     if (!$user) {
    //         return response()->json([
    //             'success' => false,
    //             'status' => 401,
    //             'message' => 'Unauthorized'
    //         ]);
    //     }

    //     $user->fcm_token = $request->fcm_token;
    //     $user->save();

    //     return response()->json([
    //         'success' => true,
    //         'status' => 200,
    //         'message' => 'FCM token updated successfully'
    //     ], 200);
    // }

    public function getReward(Request $request)
    {
        $user_id = 0;
    if ($request->header('Authorization')) {
        $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
        $userDetails = User::where('auth', $auth_token)->first();
        
        if ($userDetails) {
            $user_id = $userDetails->id;
            Log::info("User authenticated with ID: " . $user_id);
        } else {
            Log::warning("Failed to authenticate user with token: " . $auth_token);
            return response()->json(['success' => false, 'message' => 'Invalid token'], 401);
        }
    } else {
        Log::warning("Authorization header missing.");
        return response()->json(['success' => false, 'message' => 'Authorization header is missing.'], 401);
    }

    $user = User::where('id', $user_id)->first();
    // return $user;


        $rewardlists = Reward::where('is_active', 1)->orderBy('id', 'desc')->get();

        // $user = Auth::user();

        // if (!$user) {
        //     return response()->json(['success' => false, 'message' => 'User not authenticated']);
        // }

        $totalWeight = $user->orders->sum('total_order_weight');
        $fineltotalweight = $totalWeight / 1000;

        $data = [];

        foreach ($rewardlists as $reward) {

            $vendorStatus = VendorReward::where('reward_id', $reward->id)
                ->where('vendor_id', $user->id)
                ->first();

            if ($vendorStatus) {
                
                if($vendorStatus->status == 1){

                    $status = 'applied';

                }elseif ($vendorStatus->status == 2) {

                    $status = 'accepted';

                }else{

                    $status = '';
                }

                
            } elseif ($fineltotalweight >= $reward->weight) {

                $status = 'eligible';

            } else {

                $status = 'not eligible';
            }

            $data[] = [
                'id'     => $reward->id,
                'product_id' => $reward->product_id,
                'name'   => $reward->name,
                'image'  => asset($reward->image),
                'price' => $reward->price,
                'weight' => $reward->weight.'KG',
                'order_weight' => $fineltotalweight.'KG',
                'status' => $status,
                'reward_type' => $reward->reward_type,
            ];
        }

        return response()->json(['success' =>  true, 'data' => $data], 200);
    }

    public function claimReward(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated']);
        }

        $validator = Validator::make($request->all(), [
            'reward_id' => 'required|integer|exists:rewards,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $rewardId = $request->reward_id;
        $reward = Reward::find($rewardId);

        if (!$reward || !$reward->is_active) {
            return response()->json(['success' => false, 'message' => 'Reward is not available']);
        }

        $totalWeight = $user->orders->sum('total_order_weight');

        $lastOrder = $user->orders()->latest()->first();

        if ($totalWeight < $reward->weight) {
            return response()->json(['success' => false, 'message' => 'Not eligible for this reward']);
        }

        $exists = VendorReward::where('reward_id', $rewardId)
            ->where('vendor_id', $user->id)
            ->where('status', '!=', 3)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Reward already applied']);
        }

        VendorReward::create([
            'vendor_id'     => $user->id,
            'order_id'      => $lastOrder ? $lastOrder->id : null, 
            'reward_name'   => $reward->name,
            'reward_image'  => $reward->image,
            'reward_id'     => $reward->id,
            'status'        => VendorReward::STATUS_APPLIED,
            'achieved_at'   => now()->setTimezone('Asia/Calcutta')->format('Y-m-d H:i:s'),
        ]);

        return response()->json(['success' => true, 'message' => 'Reward successfully applied'], 201);
    }


    // public function claimReward(Request $request)
    // {
    //     $user_id = 0;
    // if ($request->header('Authorization')) {
    //     $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
    //     $userDetails = User::where('auth', $auth_token)->first();
        
    //     if ($userDetails) {
    //         $user_id = $userDetails->id;
    //         Log::info("User authenticated with ID: " . $user_id);
    //     } else {
    //         Log::warning("Failed to authenticate user with token: " . $auth_token);
    //         return response()->json(['success' => false, 'message' => 'Invalid token'], 401);
    //     }
    // } else {
    //     Log::warning("Authorization header missing.");
    //     return response()->json(['success' => false, 'message' => 'Authorization header is missing.'], 401);
    // }

    // $user = User::where('id', $user_id)->first();

    // if (!$user) {
    //     return response()->json(['success' => false, 'message' => 'User not found'], 404);
    // }

    // $validator = Validator::make($request->all(), [
    //     'reward_id' => 'required|integer|exists:rewards,id',
    // ]);

    // if ($validator->fails()) {
    //     return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
    // }

    // $rewardId = $request->reward_id;
    // $reward = Reward::find($rewardId);

    // if (!$reward || !$reward->is_active) {
    //     return response()->json(['success' => false, 'message' => 'Reward is not available']);
    // }

    // // Check if user is eligible for reward
    // $totalWeight = $user->orders->sum('total_order_weight');
    // $lastOrder = $user->orders()->latest()->first();

    // if ($totalWeight < $reward->weight) {
    //     return response()->json(['success' => false, 'message' => 'Not eligible for this reward']);
    // }

    // // Check if reward has already been claimed
    // $exists = VendorReward::where('reward_id', $rewardId)
    //     ->where('vendor_id', $user->id)
    //     ->where('status', '!=', 3)
    //     ->exists();

    // if ($exists) {
    //     return response()->json(['success' => false, 'message' => 'Reward already applied']);
    // }

    // // Create VendorReward
    // VendorReward::create([
    //     'vendor_id'     => $user->id,
    //     'order_id'      => $lastOrder ? $lastOrder->id : null,
    //     'reward_name'   => $reward->name,
    //     'reward_image'  => $reward->image,
    //     'reward_id'     => $reward->id,
    //     'status'        => VendorReward::STATUS_APPLIED,
    //     'achieved_at'   => now()->setTimezone('Asia/Calcutta')->format('Y-m-d H:i:s'),
    // ]);

    // return response()->json(['success' => true, 'message' => 'Reward successfully applied'], 201);
    // }

    public function get_location(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_key' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'device_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        $api_key = $request->api_key;
        $lat = $request->lat;
        $long = $request->long;
        $apiKey = config('constants.GOOGLE_MAP_KEY');

        if($api_key == config('constants.API_KEY_OSWALAPP')){

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $long . '&key=' . $apiKey,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET', // Change to GET
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);

            $r = json_decode($response);
             Log::error('Location Response: ' . $r);
            $r2 = $r->results[0]->formatted_address;
            if(!empty($r2 )){
                return response()->json(['status' => 'Success', 'message' => $r2]);
            }
            // print_r($r2);

        // if (!getLatLngFromAddress($custom_address)) {
        //     $live_location = "not able to get from google";
        //     // return response()->json(['success' => false, 'message' => 'Address Not Found.'], 400);
        // }
        // else{
        //     $live_location =
        // }


        }

    }

    public function delete(Request $request)  // Add Request parameter
{
    if ($request->header('Authorization')) {
        $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
        
        $userDetails = User::where('auth', $auth_token)->first();
        
        if (!$userDetails) {
            return response()->json([
                'message' => 'Unauthorized: Invalid authentication token'
            ], 401);
        }
        
        $device_id = $userDetails->device_id;
        $user_id = $userDetails->id;
        $role_type = $userDetails->role_type;
        

        
        return response()->json([
            'message' => 'Your request has been sent to the admin. Our team will contact you shortly.',
            'status' => 200,
        ], 200);
    }
    
    return response()->json([
        'message' => 'Unauthorized',
        'status' => 201,
    ], 200);
}

    public function unauth()
    {
        return response()->json(['success' => false, 'message' => 'Please login unauth route'], 201);
    }
}
