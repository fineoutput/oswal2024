<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


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