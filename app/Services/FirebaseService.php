<?php

namespace App\Services;

use Kreait\Firebase\Factory;

use Kreait\Firebase\Messaging\CloudMessage;

use Kreait\Firebase\Exception\MessagingException;


class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        
        $serviceAccountPath = storage_path('app/serviceaccount.json');

        $firebase = (new Factory)->withServiceAccount($serviceAccountPath);

        $this->messaging = $firebase->createMessaging();
    }

    public function sendNotificationToTopic($topic, $title, $body, $image=null, $data = [])
    {
        
        $message = CloudMessage::withTarget('topic', $topic)
        
            ->withNotification([
                
                'title' => $title,
                
                'body' => $body,

                'image' => $image != null ? $image : '',
                
            ]);
        
        try {
            $result = $this->messaging->send($message);

            return ['success' => true, 'result' => $result];

        } catch (MessagingException $e) {

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // send a notification to a specific user

    public function sendNotificationToUser(string $fcmToken, string $title, string $body, string $image = null , array $data = [])
    {
        
        $message = CloudMessage::withTarget('token', $fcmToken)

            ->withNotification([

                'title' => $title,

                'body' => $body,

                'image' => $image != null ? $image : '',

            ]);

            // ->withData($data); 

        try {

            $result = $this->messaging->send($message);

            return ['success' => true, 'result' => $result];

        } catch (MessagingException $e) {

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

}
