<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $description;
    protected $imagePath;
    protected $googleAccessTokenService;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $description, $imagePath = null, $googleAccessTokenService)
    {
        $this->title = $title;
        $this->description = $description;
        $this->imagePath = $imagePath;
        $this->googleAccessTokenService = $googleAccessTokenService;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['fcm'];
    }   

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->imagePath,
        ];
    }

    public function toFcm($notifiable)
    {
        $url = 'https://fcm.googleapis.com/v1/projects/oswalsoap-d8508/messages:send';

        $payload = [
            'message' => [
                'topic' => 'OswalSoap',
                'notification' => [
                    'title' => $this->title,
                    'body' => $this->description,
                    'image' => asset($this->imagePath),
                    'sound' => 'default',
                ],
                'priority' => 'high',
            ],
        ];

        $headers = [
            'Authorization' => 'key=' .$this->googleAccessTokenService,
            'Content-Type' => 'application/json',
        ];

        $response = Http::withHeaders($headers)->post($url, $payload);

       
        if ($response->failed()) {

            Log::error('FCM send error: ' . $response->body());
            
        }
    }
}
