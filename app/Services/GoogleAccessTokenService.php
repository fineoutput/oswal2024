<?php

namespace App\Services;

use App\Models\GoogleAccessToken;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Google_Client;

class GoogleAccessTokenService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();

        // $this->client->setAuthConfig(storage_path('app/serviceaccount.json'));
        $this->client->setAuthConfig(public_path('admin/assets/serviceaccount.json'));

        $this->client->setScopes(['https://www.googleapis.com/auth/cloud-platform']); 
    }

    /**
     * Retrieve a valid access token using a service account.
     *
     * @return string Access token.
     */
    
     public function getAccessToken()
     {
        
         $tokenRecord = GoogleAccessToken::latest()->first();
    
         if ($tokenRecord && Carbon::now()->lt($tokenRecord->expires_at)) {
             return $tokenRecord->token;
         }
        
         try {
            
             $this->client->fetchAccessTokenWithAssertion();
             $tokenData = $this->client->getAccessToken();
             $token = $tokenData['access_token'];
     
             $expiresAt = Carbon::now()->addMinutes(58);
     
             GoogleAccessToken::updateOrCreate(
                 ['id' => $tokenRecord ? $tokenRecord->id : null],
                 ['token' => $token, 'expires_at' => $expiresAt]
             );
     
             return $token;
     
         } catch (\Exception $e) {
             
             Log::error('Error fetching access token: ' . $e->getMessage());

             return 'An error occurred while fetching the access token.';
         }

     }
     
}
