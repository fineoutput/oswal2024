<?php

namespace App\Http\Controllers\Admin;

use App\Models\PushNotification as PushNotificationModel;

// use App\Services\GoogleAccessTokenService;

use App\Services\FirebaseService;

use App\Notifications\PushNotification;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class PushNotificationController extends Controller
{
    // protected $googleAccessTokenService;
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function index()
    {

        $notifactions = PushNotificationModel::orderby('id', 'desc')->get();

        return view('admin.Notifaction.view-notifaction', compact('notifactions'));
    }

    public function create(Request $request, $id = null)

    {
        $notifaction = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin" && $admin_position !== "Admin") {

                return redirect()->route('promocode.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $notifaction = PushNotificationModel::find(base64_decode($id));
            
        }

        return view('admin.Notifaction.add-notifaction', compact('notifaction'));
    }

    public function store(Request $request)
{
    // dd($request->all());

    $rules = [
        'title'         => 'required|string',
        'description'   => 'required|string',
        'img'            => 'nullable|file|mimes:xlsx,csv,xls,pdf,doc,docx,txt,jpg,jpeg,png|max:25000',
    ];

    $request->validate($rules);

    // if (!isset($request->notifaction_id)) {
    //     $notification = new PushNotificationModel;
    // } else {
    //     $notification = PushNotificationModel::find($request->notifaction_id);

    //     if (!$notification) {
    //         return redirect()->route('notification.index')->with('error', 'notifaction not found.');
    //     }
    // }

    // // Handle image upload
    // if ($request->hasFile('img')) {
    //     $notification->image = uploadImage($request->file('img'), 'notifaction');
    // }

    // // Set notification properties
    // $notification->title = $request->title;
    // $notification->description = $request->description;
    // $notification->ip = $request->ip();
    // $notification->date = now();
    // $notification->added_by = Auth::user()->id;
    // $notification->is_active = 1;

    // // Save notification to database
    // if ($notification->save()) {
    //     $message = isset($request->notifaction_id) ? 'Notification updated successfully.' : 'Notification inserted successfully.';

    //     // Firebase push notification setup
    //     $firebase = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));
    //     $messaging = $firebase->createMessaging();
    //     $topic = 'OswalSoap';

    //     // Build the CloudMessage object
    //     $cloudMessage = CloudMessage::withTarget('topic', $topic)
    //         ->withNotification(Notification::create($notification->title, $notification->description))
    //         ->withData(['key' => 'value']);  // Optional custom data

    //     try {
    //         // Send the message
    //         $response = $messaging->send($cloudMessage);

    //         // Handle the response
    //         if (isset($response['success']) && !$response['success']) {
    //             Log::error('FCM send error: ' . $response['error']);
    //         }

    //         return redirect()->route('notification.index')->with('success', $message);
    //     } catch (\Exception $e) {
    //         // Handle any error that occurred during the sending process
    //         Log::error('FCM send exception: ' . $e->getMessage());
    //         return redirect()->route('notification.index')->with('error', 'Error sending notification to Firebase.');
    //     }
    // } else {
    //     return redirect()->route('notification.index')->with('error', 'Something went wrong. Please try again later.');
    // }

    if (!isset($request->notifaction_id)) {
        $notification = new PushNotificationModel;
    } else {
        $notification = PushNotificationModel::find($request->notifaction_id);
    
        if (!$notification) {
            return redirect()->route('notification.index')->with('error', 'Notification not found.');
        }
    }

    if ($request->hasFile('img')) {
        $notification->image = uploadImage($request->file('img'), 'notifaction');
    }
    
    // Set notification properties
    $notification->title = $request->title;
    $notification->description = $request->description;
    $notification->ip = $request->ip();
    $notification->date = now();
    $notification->added_by = Auth::user()->id;
    $notification->is_active = 1;
    
    // Save notification to the database
    if ($notification->save()) {
        $message = isset($request->notifaction_id) ? 'Notification updated successfully.' : 'Notification inserted successfully.';
    
        // Firebase push notification setup
        $firebase = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));
        $messaging = $firebase->createMessaging();
        $topic = 'OswalSoap';
    
        // Prepare the notification and data payloads
        $notificationPayload = [
            'title' => $notification->title,
            'body' => $notification->description,
            'image' => $notification->image, // This is the URL to the image (must be public)
        ];
    
        // You can also add custom data if needed
        $dataPayload = [
            'key' => 'value',
            'image' => $notification->image, // Include the image URL in the data
        ];
    
        // Build the CloudMessage object
        $cloudMessage = CloudMessage::withTarget('topic', $topic)
            ->withNotification($notificationPayload) // Set the notification payload (title, body, image)
            ->withData($dataPayload); // Add custom data (image URL, etc.)
    
        try {
            // Send the message
            $response = $messaging->send($cloudMessage);
    
            // Handle the response
            if (isset($response['success']) && !$response['success']) {
                Log::error('FCM send error: ' . $response['error']);
            }
    
            return redirect()->route('notification.index')->with('success', $message);
        } catch (\Exception $e) {
            // Handle any error that occurred during the sending process
            Log::error('FCM send exception: ' . $e->getMessage());
            return redirect()->route('notification.index')->with('error', 'Error sending notification to Firebase.');
        }
    } else {
        return redirect()->route('notification.index')->with('error', 'Something went wrong. Please try again later.');
    }
}

    // public function store(Request $request)

    // {
    //     // dd($request->all());

    //     $rules = [
    //         'title'         => 'required|string',
    //         'description'   => 'required|string',
    //         'img'         => 'nullable|file|mimes:xlsx,csv,xls,pdf,doc,docx,txt,jpg,jpeg,png|max:25000',
    //     ];
        
    //     $request->validate($rules);

    //     if (!isset($request->notifaction_id)) {

    //         $notification = new PushNotificationModel;

    //     } else {

    //         $notification = PushNotificationModel::find($request->notifaction_id);
            
    //         if (!$notification) {
                
    //             return redirect()->route('notification.index')->with('error', 'notifaction not found.');
                
    //         }

    //     }
        
    //     // $notification->fill($request->all());

    //     if($request->hasFile('img')){

    //         $notification->image = uploadImage($request->file('img'), 'notifaction');

    //     }

    //     $notification->title = $request->title;

    //     $notification->description = $request->description;

    //     $notification->ip = $request->ip();

    //     $notification->date = now();

    //     $notification->added_by = Auth::user()->id;

    //     $notification->is_active = 1;

    //     if ($notification->save()) {

    //         $message = isset($request->notification_id) ? 'Notification updated successfully.' : 'Notification inserted successfully.';
            
    //         // $notification->notify(new PushNotification($notification->title, $notification->description, $notification->image, $this->googleAccessTokenService->getAccessToken()));

    //         // $response = $this->firebaseService->sendPushNotification('OswalSoap', $notification->title, $notification->description, asset($notification->image));

    //         //  return $response;
    //         // print_r(base_path(env('FIREBASE_CREDENTIALS')));
    //         // exit;
    //         $firebase = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));
    //         $messaging = $firebase->createMessaging();
    //         $topic = 'OswalSoap';
    //         // Build the message
    //         $message = CloudMessage::withTarget('topic', $topic) // Target a topic
    //             ->withNotification(Notification::create($notification->title, $notification->description))
    //             ->withData(['key' => 'value']); // Optional custom data

    //         // Send the message
    //         $response = $messaging->send($message);
         
    //         // return $messaging;
    //         // return "Notification sent to topic: {$topic}";

            
    //         if (!$response['success']) {

    //             Log::error('FCM send error: ' . $response['error']);
                
    //         }
            
    //         return redirect()->route('notification.index')->with('success', $message);

    //     } else {

    //         return redirect()->route('notification.index')->with('error', 'Something went wrong. Please try again later.');

    //     }

    // }
    
}
