<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PushNotification as PushNotificationModel;
use App\Notifications\PushNotification;

class PushNotificationController extends Controller
{
    public function index()
    {

        $notifactions = PushNotificationModel::orderby('id', 'desc')->get();

        return view('admin.Notifaction.view-notifaction', compact('notifactions'));
    }

    public function create($id = null, Request $request)

    {
        $notifaction = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('promocode.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $notifaction = PushNotificationModel::find(base64_decode($id));
            
        }

        return view('admin.Notifaction.add-notifaction', compact('notifaction'));
    }

    public function store(Request $request)

    {
        // dd($request->for_admin);

        $rules = [
            'title'         => 'required|string',
            'description'   => 'required|string',
            'img'         => 'nullable|file|mimes:xlsx,csv,xls,pdf,doc,docx,txt,jpg,jpeg,png|max:25000',
        ];
        
        $request->validate($rules);

        if (!isset($request->notifaction_id)) {

            $notification = new PushNotificationModel;

        } else {

            $notification = PushNotificationModel::find($request->notifaction_id);
            
            if (!$notification) {
                
                return redirect()->route('notification.index')->with('error', 'notifaction not found.');
                
            }

        }
        
        $notification->fill($request->all());

        if($request->hasFile('img')){

            $notification->image = uploadImage($request->file('img'), 'notifaction');

        }

        $notification->ip = $request->ip();

        $notification->date = now();

        $notification->added_by = Auth::user()->id;

        $notification->is_active = 1;

        if ($notification->save()) {

            $message = isset($request->notification_id) ? 'Notification updated successfully.' : 'Notification inserted successfully.';
            
            $notification->notify(new PushNotification($notification->title, $notification->description, $notification->image));
            
            return redirect()->route('notification.index')->with('success', $message);

        } else {

            return redirect()->route('notification.index')->with('error', 'Something went wrong. Please try again later.');

        }

    }
    
}
