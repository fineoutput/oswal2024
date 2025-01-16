<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $secretKey = $request->header('X-Webhook-Secret');

        if ($secretKey !== env('WEBHOOK_SECRET_KEY')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->getContent();

        Log::info("Webhook Data: ", ['data' => $data]);

        $jsonData = json_decode($data, true);

        if ($jsonData) {
            return response()->json(['message' => 'Webhook received successfully']);
        }

        return response()->json(['message' => 'Invalid data'], 400);
    }


    
}
