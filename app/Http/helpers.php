<?php
use Carbon\Carbon;
use App\Models\EcomCategory;
use App\Models\EcomProduct;
use App\Models\Type;
use App\Models\ShippingCharge;
use App\Models\Shiprockettoken;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderInvoice;
use App\Models\WalletTransactionHistory;
use Illuminate\Support\Facades\DB;
use App\adminmodel\Team;
use App\Models\VendorType;
use Illuminate\Support\Facades\Cookie;

if (!function_exists('lang_change')) {

    function lang_change($text) {
        
        $apiKey = env('GOOGLE_TRANSLATE_API_KEY');
        
        $url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&source=en&target=hi';

        $handle = curl_init($url);

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($handle);

        $responseDecoded = json_decode($response, true);

        $responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE); 

        curl_close($handle);
        
        if ($responseCode != 200) {

            $b = 'Fetching translation failed! Server response code:' . $responseCode . '<br>';

            $c = 'Error description: ' . $responseDecoded['error']['errors'][0]['message'];
            
            $e = $responseDecoded['error']['errors'][0]['message'];

        } else {

            $b = 'Source: ' . $text . '<br>';

            $c = 'Translation: ' . $responseDecoded['data']['translations'][0]['translatedText'];

            $e = $responseDecoded['data']['translations'][0]['translatedText'];

        }

        return $e;
    }
}

if(!function_exists('uploadImage')){

    function uploadImage($image, $p1 = null, $p2 = null, $p3 = null, $p4 = null) {

        $currentDate = Carbon::now();
    
        $year  = $currentDate->year;
        $month = $currentDate->month;
        $day   = $currentDate->day;
        $time  = $currentDate->format('His');
        
        $directory = 'uploads/image';

        $directory .= "/$year/$month/$day";
        
        if ($p1 !== null) $directory .= "/$p1";
        if ($p2 !== null) $directory .= "/$p2";
        if ($p3 !== null) $directory .= "/$p3";
        if ($p4 !== null) $directory .= "/$p4";
        
    
        if (!file_exists(public_path($directory))) {

            mkdir(public_path($directory), 0777, true);

        }
    
        $imageName = $time . '.' . $image->getClientOriginalExtension();

        $path = public_path($directory);

        $image->move($path, $imageName);
    
        return "$directory/$imageName";
    }
}

if(!function_exists('sendProduct')) {

    function sendProduct($cid = false, $pid = false, $pcid = false , $hid = false , $trid = false , $search = false , $is_fea = false , $paginate =false , $forproduct=false, $roleType=false )  {
        
        $products =  EcomProduct::OrderBy('id', 'Desc')->where('is_active', 1);

        if($cid){ $products = $products->where('category_id', $cid);}

        if($pid) { $products = $products->where('id', $pid); }

        if($pcid) { $products = $products->where('product_category_id', $pcid); }

        if($hid){$products = $products->where('is_hot', 1);}

        if($trid){ 
            
            $products = $products->whereHas('trending', function($query) {
                    $query->where('is_active', 1);
            });
        }

        if($search){$products = $products ->where('name', 'LIKE', "%$search%");}

        if($is_fea){$products = $products->where('is_featured', 1);}
        
        if($forproduct == 2){
            $products = $products->whereIn('product_view', [3, 2]);
        }
        // echo $roleType;
        // // echo "hi";
        // exit;
       
        if($paginate){
            return $products->paginate($paginate);
        }else{
            return $products->get();
        }

    }

}

if(!function_exists('sendCategory')){

    function sendCategory($cid = false) {
        
        $categorys =  EcomCategory::OrderBy('sequence' , 'Asc')->where('is_active', 1);

        if($cid){ $categorys = $categorys->where('id', $cid);}

        return $categorys->get();

    }
    
}

if(!function_exists('formatPrice')){

    function formatPrice($amount , $format=true) {

        $formatted_amount = number_format((float)$amount, 2, '.', '');

        $parts = explode('.', $formatted_amount);

        $integer_part = $parts[0];

        $decimal_part = isset($parts[1]) ? '.' . $parts[1] : '';

        $formatted_integer_part = implode(',', str_split(strrev($integer_part), 3));
        if($format){
            $formatted_amount = '₹' . strrev($formatted_integer_part) . $decimal_part;
        }else{
            $formatted_amount;
        }

        return $formatted_amount;

    }
    
}

if(!function_exists('sendType')){

    function sendType($cid = null, $pid = null, $id = null , $fortype=null) {
       
        if($fortype){

            $query = VendorType::orderBy('id', 'desc')->where('is_active', 1);

        }else{
            
            $query = Type::orderBy('id', 'desc')->where('is_active', 1);

        }
    
        if ($cid) {
            $query->where('category_id', $cid);
        }
    
        if ($pid) {
            $query->where('product_id', $pid);
        }
    
        if ($id) {
            $query->where('id', $id); 
        }
    
        return $query->get();
    }
    
    
}

if (!function_exists('sendOtpSms')) {

    function sendOtpSms($msg, $phone, $otp , $dlt, $sender_id) {

        $url = config('constants.SMS_API_URL');
        $authKey = config('constants.SMS_API_KEY'); 
        $senderId = $sender_id;

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url . '?' . http_build_query([
                'authkey' => $authKey,
                'mobile' => '91' . $phone,
                'message' => urlencode($msg),
                'sender' => $senderId,
                'otp' => $otp,
                'DLT_TE_ID' => $dlt,
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        // dd($response);

        if ($err) {
            // Log the error or handle it appropriately
            Log::error("cURL Error #:" . $err);
        } else {
            // Process the response if needed
            // Log::info("cURL Response: " . $response);
        }
    }
}

if(!function_exists('generateRandomString')){

    function generateRandomString($length = 20){

		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ%$@*!';

		$charactersLength = strlen($characters);

		$randomString = '';

		for ($i = 0; $i < $length; $i++) {

			$randomString .= $characters[rand(0, $charactersLength - 1)];

		}

		return $randomString;

	}
}

if(!function_exists('generateOtp')){

    function generateOtp() {

        return rand(100000, 999999);

    }
}

if(!function_exists('calculateShippingCharges')){

 function calculateShippingCharges($total_order_weight , $city_id)  {

    $shippingData = ShippingCharge::where('city_id', $city_id)->first();
    
    if (!$shippingData) {
        return response()->json(['success' => false ,'message' => 'Shipping services not available in this area.','status' => 400]);
    }
    
    $total_weight_charge = null;

    if ($shippingData->weight1 >= $total_order_weight) {

        $total_weight_charge = $shippingData->shipping_charge1;

    } elseif ($shippingData->weight2 >= $total_order_weight) {

        $total_weight_charge = $shippingData->shipping_charge2;

    } elseif ($shippingData->weight3 >= $total_order_weight) {

        $total_weight_charge = $shippingData->shipping_charge3;

    } elseif ($shippingData->weight4 >= $total_order_weight) {

        $total_weight_charge = $shippingData->shipping_charge4;

    } elseif ($shippingData->weight5 >= $total_order_weight) {

        $total_weight_charge = $shippingData->shipping_charge5;

    } else {

        $total_weight_charge = $shippingData->shipping_charge6;

    }

    $total_weight_charge = number_format((float)$total_weight_charge, 2, '.', '');
    
    $total_order_weight = number_format((float)$total_order_weight, 1, '.', '');

    return response()->json(['success' => true,  'total_order_weight' => $total_order_weight ,'total_weight_charge' => $total_weight_charge]);
 }

}

if(!function_exists('handleReferral')) {

     function handleReferral($referrerCode, $newUserId)
    {

        $referrer = User::where('referral_code', $referrerCode)->first();

        if($referrer) {
            // Credit the referrer
            $transactionData = [
                'user_id' =>  $referrer->id,
                'transaction_type' => 'credit', // or 'debit'
                'amount' => 10,
                'transaction_date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                'status' => WalletTransactionHistory::STATUS_PENDING,
                'description' => 'Referral transaction for referrer',
            ];

            $newTransactionForReferrer = WalletTransactionHistory::createTransaction($transactionData);

            if($newTransactionForReferrer) {
                DB::table('refferal_histoty')->insert([
                    'referrer_id'    => $referrer->id,
                    'referee_id'     => $newUserId,
                    'transaction_id' => $newTransactionForReferrer->id,
                    'reward_points'  => 10,
                    'status'         => 0,
                ]);
            } else {
                Log::alert('Referral history not created for referrer, something went wrong');
                return false;
            }

            // Credit the new user (referee)
            $transactionDataForReferee = [
                'user_id' => $newUserId,
                'transaction_type' => 'credit', // or 'debit'
                'amount' => 10,
                'transaction_date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                'status' => WalletTransactionHistory::STATUS_PENDING,
                'description' => 'Referral transaction for referee',
            ];

            $newTransactionForReferee = WalletTransactionHistory::createTransaction($transactionDataForReferee);

            if($newTransactionForReferee) {
                DB::table('refferal_histoty')->insert([
                    'referrer_id'    => $referrer->id,
                    'referee_id'     => $newUserId,
                    'transaction_id' => $newTransactionForReferee->id,
                    'reward_points'  => 10,
                    'status'         => 0,
                ]);
            } else {
                Log::alert('Referral history not created for referee, something went wrong');
                return false;
            }

        }else{

            return false;

        }

        return ['referrer_tr_id' => $newTransactionForReferrer->id ,'referee_tr_id' => $newTransactionForReferee->id];

    }
}

if (!function_exists('calculate_wallet_discount')) {

    function calculate_wallet_discount($walletBalance)
    {
        $walletPercentage =  getConstant()->wallet_use_amount; 

        $walletDiscount = round($walletBalance * $walletPercentage / 100, 2);

        return $walletDiscount;
    }

}

if (!function_exists('generateInvoiceNumber')){
    function generateInvoiceNumber($order1Id)
    {
     
        $order = Order::findOrFail($order1Id);

        $orderinvoice = new OrderInvoice;
        
        $orderinvoice->user_id    = $order->user_id;

        $orderinvoice->order_id   = $order->id;

        $orderinvoice->invoice_no = date('YmdHis');

        $orderinvoice->save();

        return  $orderinvoice->invoice_no;
    }
}

if (!function_exists('getConstant')){

    function getConstant() {

        $constant = DB::table('constants')->first();

        return $constant;
    }
}

if (!function_exists('getOrderStatus')){

    function getOrderStatus($orderstatusId) {

        $orderStatus = 'Pending';

        if($orderstatusId == 1){

            $orderStatus = 'Under Review';

        }else if($orderstatusId == 2){

            $orderStatus = 'Confirmed';

        }else if($orderstatusId == 3){

            $orderStatus = 'Dispatched';

        }else if($orderstatusId == 4) {

            $orderStatus = 'Delivered';

        }else if($orderstatusId == 5){

            $orderStatus = 'Rejected';

        }else{

            $orderStatus = 'UnPlaced';
        }

        return $orderStatus;
    }
}

if (!function_exists('getRejectedByDetails')) {

    function getRejectedByDetails($rejectedBy, $rejectedById) {

        $user = null;

        $team = null;

        if (!empty($rejectedBy)) {

            if ($rejectedBy == 1) {

                $user = User::where('id', $rejectedById)

                            ->where('is_active', 1)

                            ->first();

                if ($user) {

                    return "User({$user->first_name})";

                }

            } elseif ($rejectedBy == 2) {

                $team = Team::where('id', $rejectedById)

                            ->where('is_active', 1)

                            ->first();

                if ($team) {

                    if ($team->power == 1) {

                        return "SuperAdmin({$team->name})";

                    } elseif ($team->power == 2) {

                        return "Admin({$team->name})";

                    } else {

                        return "Manager({$team->name})";

                    }

                }

            }

        }

        return '-';
    }
}

if (! function_exists('deliveryStatus')){

    function deliveryStatus($sts) {

        if($sts == 0){
            $status = 'Pending';
        }elseif($sts == 1){
            $status = 'Accepted';
        }elseif($sts == 2){
            $status = 'StartDelivery';
        }elseif($sts == 3){
            $status = 'Ongoing';
        }elseif($sts == 4){
            $status = 'Delivered';
        }

        return  $status;
    }
}

if (!function_exists('percentOff')) {

    function percentOff($del_mrp, $selling_price, $format = false) {

        if ($del_mrp <= 0) {
            return 0;
        }

        $percent_off = round((($del_mrp - $selling_price) * 100) / $del_mrp);

        return $format ? "{$percent_off}% off" : $percent_off;
    }

}

if (! function_exists('renderStarRating')) {

    function renderStarRating($rating)
    {
        $fullStars = floor($rating);

        $halfStar = ($rating - $fullStars) >= 0.5;

        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

        $html = '';

        for ($i = 0; $i < $fullStars; $i++) {
            $html .= '<i class="fas fa-star"></i> ';
        }

        if ($halfStar) {
            $html .= '<i class="fas fa-star-half-alt"></i> ';
        }

        for ($i = 0; $i < $emptyStars; $i++) {
            $html .= '<i class="far fa-star"></i> ';
        }

        return $html;
    }
}

if(! function_exists('sendPersistentId')) {

    function sendPersistentId($request) {
        
        $persistentId = $request->cookie('persistent_id');
        
        if (!$persistentId) {

            $persistentId = uniqid();
    
            Cookie::queue('persistent_id', $persistentId, 60 * 24 * 30); 

            $cookieCreatedAt = cookie('persistent_id_created_at', Carbon::now()->toDateTimeString(), 60 * 24 * 30);
        
        }

        return $persistentId;
    }

}

if(! function_exists('cleanamount')) {
    function cleanamount($value) {
    
        $cleanedValue = preg_replace('/[^\d.]/', '', $value);
        
        return floatval($cleanedValue);
    }
}

if(! function_exists('getLatLngFromAddress')){
    
    function getLatLngFromAddress($address) {

        $apiKey = 'AIzaSyAk8VcdFTCgvhaUtTiTk_I2c3D84Rsmt_U'; 
        $address = urlencode($address);
        
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        
        $responseData = json_decode($response, true);
        
        if ($responseData['status'] === 'OK') {
        
            $latitude = $responseData['results'][0]['geometry']['location']['lat'];
            $longitude = $responseData['results'][0]['geometry']['location']['lng'];
            
            return ['latitude' => $latitude, 'longitude' => $longitude];

        } else {
        Log::info("Google Location Response: " . $response);
            return false; 
            
        }
    }

} 

if (!function_exists('formatWeight')) {
    function formatWeight($totalWeight)
    {
        $kg = floor($totalWeight / 1000); 
        $gm = $totalWeight % 1000; 

        $result = '';

        if ($kg > 0) {
            $result .= $kg . ' kg ';
        }

        if ($gm > 0) {
            $result .= $gm . ' gm';
        }

        return trim($result) ?: '--'; 
    }
}
