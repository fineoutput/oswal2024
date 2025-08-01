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



      public function GetProduct(Request $request){

        $category_id = $request->input('category_id');

        $products = sendProduct($category_id);
        
        return response()->json($products, 200);

    }

    public function create(Request $request, $id = null)
    {
                $categories = sendCategory();
                //   $products   = sendProduct($slider->category_id);

             $notifaction = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin" && $admin_position !== "Admin") {

                return redirect()->route('promocode.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $notifaction = PushNotificationModel::find(base64_decode($id));
            
        }

        return view('admin.Notifaction.add-notifaction', compact('notifaction','categories'));
    }

  public function store(Request $request)
    {

        $rules = [
            'title'         => 'required|string',
            'description'   => 'required|string',
            'type'   => 'required|string',
            'img'            => 'nullable|file|mimes:xlsx,csv,xls,pdf,doc,docx,txt,jpg,jpeg,png|max:25000',
        ];

        $request->validate($rules);

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
        
        $notification->title = $request->title;
        $notification->description = $request->description;
        $notification->product_id = $request->product_id;
        $notification->category_id = $request->category_id;
        $notification->ip = $request->ip();
        $notification->date = now();
        $notification->added_by = Auth::user()->id;
        $notification->is_active = 1;
        $notification->type = $request->type;
        
        if ($notification->save()) {
            $message = isset($request->notifaction_id) ? 'Notification updated successfully.' : 'Notification inserted successfully.';
        
            // Firebase push notification setup
            $firebase = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));
            $messaging = $firebase->createMessaging();
            // $topic = 'OswalSoap';
            $topic = $notification->type;
        
            // Prepare the notification and data payloads
            $imageUrl = asset($notification->image);
            $notificationPayload = [
                'title' => $notification->title,
                'body' => $notification->description,
                'image' => $imageUrl, // This is the URL to the image (must be public)
            ];
        
            // You can also add custom data if needed
            $dataPayload = [
                'key' => 'value',
                'image' => $imageUrl,
                'category_id' => $notification->category_id,
                'product_id' => $notification->product_id,
                'screen' => 'ProductDetail',
            ];
       
            $cloudMessage = CloudMessage::withTarget('topic', $topic)
                ->withNotification($notificationPayload) // Set the notification payload (title, body, image)
                ->withData($dataPayload); // Add custom data (image URL, etc.)
        
            try {
                $response = $messaging->send($cloudMessage);

                     Log::info('FCM send response:', [
                            'response' => $response,
                            'notification' => $notificationPayload,
                            'dataPayload' => $dataPayload
                        ]);
        
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
