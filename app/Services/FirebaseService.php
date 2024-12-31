<?php

namespace App\Services; 

use Kreait\Firebase\Factory;

use Kreait\Firebase\Messaging\CloudMessage;

use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\Notification;


class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        
        $serviceAccountPath = public_path('admin/assets/serviceaccount.json');

        $firebase = (new Factory)->withServiceAccount($serviceAccountPath);

        $this->messaging = $firebase->createMessaging();
    }


function sendPushNotification($token, $title, $body)
{

    $firebase = (new Factory)->withServiceAccount(config('firebase.credentials.file'));
    return $firebase;
    $messaging = $firebase->createMessaging();

    $message = CloudMessage::withTarget('token', $token)
        ->withNotification(Notification::create($title, $body))
        ->withData(['key' => 'value']); 

    $messaging->send($message);
}

// function sendPushNotification($token, $title, $body)
//         {

//             $firebase = (new Factory)->withServiceAccount(config('firebase.credentials'));
//             return $firebase;
//             $messaging = $firebase->createMessaging();

//             $message = CloudMessage::withTarget('token', $token)
//                 ->withNotification(PushNotification::create($title, $body))
//                 ->withData(['key' => 'value']); // Optional custom data
// return $message;
//             try {
//                 $messaging->send($message);
//                 return ['success' => true, 'message' => 'Notification sent successfully.'];
//             } catch (\Kreait\Firebase\Exception\MessagingException $e) {
//                 return ['success' => false, 'error' => $e->getMessage()];
//             } catch (\Throwable $e) {
//                 return ['success' => false, 'error' => $e->getMessage()];
//             }
//         }



    // public function sendNotificationToTopic($topic, $title, $body, $image=null, $data = [])
    // {
        
    //     $message = CloudMessage::withTarget('topic', $topic)
        
    //         ->withNotification([
                
    //             'title' => $title,
                
    //             'body' => $body,

    //             'image' => $image != null ? $image : '',
                
    //         ]);
        
    //     try {
    //         $result = $this->messaging->send($message);

    //         return ['success' => true, 'result' => $result];

    //     } catch (MessagingException $e) {

    //         return ['success' => false, 'error' => $e->getMessage()];
    //     }
    // }

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
