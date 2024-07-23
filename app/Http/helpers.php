<?php
use Carbon\Carbon;
use App\Models\EcomCategory;
use App\Models\EcomProduct;
use App\Models\Type;
use Illuminate\Support\Facades\Log;


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

if(!function_exists('sendProduct')){

    function sendProduct($cid = false, $pid = false, $pcid = false ) {
        
        $products =  EcomProduct::OrderBy('id' , 'Desc')->where('is_active', 1);

        if($cid){ $products = $products->where('category_id', $cid);}

        if($pid) { $products = $products->where('id', $pid); }

        if($pcid) { $products = $products->where('product_category_id', $pcid); }

        return $products->get();

    }

}

if(!function_exists('sendCategory')){

    function sendCategory($cid = false) {
        
        $categorys =  EcomCategory::OrderBy('id' , 'Desc')->where('is_active', 1);

        if($cid){ $categorys = $categorys->where('id', $cid);}

        return $categorys->get();

    }
    
}

if(!function_exists('formatPrice')){

    function formatPrice($amount) {

        $formatted_amount = number_format((float)$amount, 2, '.', '');

        $parts = explode('.', $formatted_amount);

        $integer_part = $parts[0];

        $decimal_part = isset($parts[1]) ? '.' . $parts[1] : '';

        $formatted_integer_part = implode(',', str_split(strrev($integer_part), 3));

        $formatted_amount = '₹' . strrev($formatted_integer_part) . $decimal_part;

        return $formatted_amount;

    }
    
}

if(!function_exists('sendType')){

    function sendType($cid = false, $pid = false , $id = false ) {
        
        $types =  Type::OrderBy('id' , 'Desc')->where('is_active', 1);

        if($cid) { $types = $types->where('category_id', $cid);}

        if($pid) { $types = $types->where('product_id', $pid); }

        if($id) { $types = $types->where('id', $pid); }

        return $types->get();

    }
    
}

if(!function_exists('sendOtpSms')){

    function sendOtpSms($msg, $contact_no) {

        $url = env('SMS_API_URL');
        $key = env('SMS_API_KEY');
        $sender_id = env('SMS_SENDER_ID');
        $message = $msg;

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'route' => 4,
                'sender' => $sender_id,
                'mobiles' => $contact_no,
                'authkey' => $key,
                'message' => $message,
                'country' => 91,
            ]),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        dd($response);
        
        if ($err) {
            // Log the error or handle it appropriately
            Log::error("cURL Error #:" . $err);
        } else {
            // Process the response if needed
            Log::info("cURL Response: " . $response);
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