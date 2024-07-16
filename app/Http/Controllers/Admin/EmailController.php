<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Email;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminMail;

class EmailController extends Controller
{

    public function index()
    {
        $emails = Email::orderby('id', 'desc')->get();

        return view('admin.Emails.view-email', compact('emails'));
    }

    public function send_mail(Request $request)

    {

        return view('admin.Emails.send-mail');
    }

    public function store(Request $request)

    {
        $rules = [
            'reciver_name'   => 'required|string',
            'reciver_email'  => 'required|email',
            'msg'            => 'required|string',
        ];

        $request->validate($rules);

        $emailData = [

            'team_email'     => Auth::user()->email,

            'reciver_name'   => $request->reciver_name,

            'reciver_email'  => $request->reciver_email,

            'msg'            => $request->msg,

            'status'         => 0,

            'ip'             => $request->ip(),

            'team_id'        => Auth::user()->id,

            'sended_by'      => Auth::user()->id,

        ];

        $email = Email::create($emailData);

        if ($email) {

            Mail::to($request->reciver_email)->send(new AdminMail($email));

            $email->update(['status' => 1]);

            return redirect()->route('email.index')->with('success', 'Mail sent successfully.');
        } else {
            return redirect()->route('email.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Email::where('id', $id)->delete()) {

            return  redirect()->route('email.index')->with('success', 'Dealer Deleted Successfully.');
        } else {
            return redirect()->route('email.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('email.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}