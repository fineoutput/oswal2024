<?php

namespace App\Services; 

use Kreait\Firebase\Factory;

use Kreait\Firebase\Messaging\CloudMessage;

use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\Notification;


class DeliveryBoyService
{
    protected $messaging;

    public function __construct()
    {
        
        $serviceAccountPath = public_path('admin/assets/servicesaccountdelivery.json');

        $firebase = (new Factory)->withServiceAccount($serviceAccountPath);

        $this->messaging = $firebase->createMessaging();
    }


function sendPushNotification($token, $title, $body)
{

    $firebase = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS_DILIVERY')));
    $messaging = $firebase->createMessaging();
    $topic = 'OswalSoap';
    // Build the message
    $message = CloudMessage::withTarget('topic', $topic) // Target a topic
        ->withNotification(Notification::create($title, $body))
        ->withData(['key' => 'value']); // Optional custom data

    // Send the message
    $messaging->send($message);

    return "Notification sent to topic: {$topic}";

}

// function sendPushNotification($token, $title, $body)
//         {

//             $firebase = (new Factory)->withServiceAccount(config('firebase.credentials'));
//
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

    public function sendNotificationToDelivery(string $fcmToken, string $title, string $body, string $image = null , array $data = [])
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
