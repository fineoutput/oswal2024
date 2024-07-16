<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactusMail;

class ContactusController extends Controller
{

    public function index()
    {

        $contactuss = ContactUs::orderby('id', 'desc')->get();

        return view('admin.ContactUs.view-contact', compact('contactuss'));
    }

    public function send_reply($id)

    {
        $contactus = ContactUs::where('id', base64_decode($id))->first();

        return view('admin.ContactUs.reply', compact('contactus'));

    }

    public function store(Request $request)

    {

        $rules = [
            'reciver_name'    => 'required|string',
            'msg'             => 'nullable|string',
        ];

        $request->validate($rules);

        $contactus = ContactUs::find($request->contactus_id);
        
        $contactus->update([

            'reply' => 1,

            'reply_message' => $request->input('msg')

        ]);

        Mail::to($contactus->email)->send(new ContactusMail($request->all()));

        return redirect()->route('contact-us.index')->with('success','Mail Sent Successfully');

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (ContactUs::where('id', $id)->delete()) {

            return  redirect()->route('contact-us.index')->with('success', 'Message Deleted Successfully.');
        } else {
            return redirect()->route('contact-us.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('contact-us.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}