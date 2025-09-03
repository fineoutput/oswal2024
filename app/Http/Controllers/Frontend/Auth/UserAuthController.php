<?php

namespace App\Http\Controllers\Frontend\Auth;

use Illuminate\Support\Facades\Validator;

use App\Models\WalletTransactionHistory;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserTemp;

use App\Models\Otp;

class UserAuthController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    // public function register(Request $request)
    // {

    //     $rules = [
    //         'username'      => 'required|string|max:255',
    //         'email'         => 'required|string|email',
    //         'referral_code' => 'nullable|string|exists:users,referral_code'
    //     ];

    //     if (session()->has('user_contact') && session()->get('user_contact') == $request->phone_no) {

    //         if (session()->has('user_otp_id') && session()->has('user_id')) {

    //             $rules['contact'] = 'required|digits:10';
    //         } else {

    //             $rules['contact'] = 'required|digits:10|unique:users,contact';
    //         }
    //     } else {

    //         session()->flush();

    //         $rules['contact'] = 'required|digits:10|unique:users,contact';
    //     }

    //     // Log::warning('Login failed for Phone No.: ' . $request->phone_no);

    //     // $request->validate($rules);

    //     $validator = Validator::make($request->all(),  $rules);

    //     if ($validator->fails()) {

    //         return response()->json(['success' => false, 'message' => $validator->errors()]);
    //     }

    //     $dlt = config('constants.SMS_LOGIN_DLT');
    //     $sender_id = config('constants.SMS_LOGIN_SENDER_ID');
    //     // Log::error('sender id no found',$sender_id);
    //     if(session()->has('user_otp_id') && session()->has('user_id') && session()->has('user_contact')) {

    //         $OTP = generateOtp();

    //         // $msg = "Welcome to Oswal. Your new OTP is {$OTP} for registration.";
    //         $msg = "Dear Oswal Soap user $OTP is your OTP for login to your account. Do not share this with anyone.";

    //         sendOtpSms($msg, session()->get('user_contact'), $OTP, $dlt, $sender_id); 
            
    //         // Update the existing OTP record
    //         $otpData = Otp::updateOrCreate(

    //             ['id' => session()->get('user_otp_id')],
    //             [
    //                 'otp' => $OTP,
    //                 'ip'  => $request->ip(),
    //                 'date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
    //             ]
    //         );

    //         // return redirect()->back()->with('success', 'Your OTP has been regenerated successfully. Please check your phone for the new code.');
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Your OTP has been regenerated successfully. Please check your phone for the new code',
    //             'data'    => ['contact_no' => session()->get('user_contact')]
    //         ], 200);
    //     }

    //     $referral = [];

    //     $name =  $request->username;

    //     $date = [
    //         'name'      => $name,
    //         'first_name_hi'   => lang_change($name),
    //         'device_id'       => $request->device_id,
    //         'auth'            => generateRandomString(),
    //         'email'           => $request->email,
    //         'contact'         => $request->contact,
    //         'password'        => null,
    //         'status'          => 0,
    //         'referral_code'   => User::generateReferralCode(),
    //         'added_by'        => 1,
    //         'date'            => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
    //         'ip'              => $request->ip(),
    //     ];

    //     $user = UserTemp::create($date);

    //     if ($request->referral_code != null && $request->referral_code != '') {

    //         $referral = handleReferral($request->referral_code , $user->id);
           
    //     }

    //     $OTP = generateOtp();

    //     $msg = "Dear User, Your OTP for Sign Up on OSWALMART is $OTP. Do not share your OTP with anyone.";

    //     sendOtpSms($msg, $user->contact, $OTP, $dlt, $sender_id);

    //     $otpData = Otp::create([
    //         'name' =>  $name,
    //         'contact_no' => $user->contact,
    //         'email' => $request->email,
    //         'otp' => $OTP,
    //         'ip' => $request->ip(),
    //         'added_by' => 1,
    //         'date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
    //     ]);

    //     if ($otpData) {
           
    //         session()->put('user_otp_id', $otpData->id);
            
    //         session()->put('user_id', $user->id);
            
    //         session()->put('user_contact', $user->contact);

    //         if ($request->referral_code != null && $request->referral_code != '') {

    //             session()->put('referrer_tr_id', $referral['referrer_tr_id']); 

    //             session()->put('referee_tr_id', $referral['referee_tr_id']); 

    //         }
    //         // return redirect()->back()->with('success', 'OTP sent successfully.');
    //         return response()->json([ 'success' => true, 'message' => 'OTP sent successfully', 'data' => ['contact_no' => $user->contact]] , 200);

    //     } else {

    //         // return redirect()->back()->with('error', 'Error occurred while saving OTP, please try again.');
    //         return response()->json(['success' => false,'message' => 'Error occurred while saving OTP, please try again']);
    //     }
    // }

    public function register(Request $request)
    {
        $rules = [
            'username'      => 'required|string|max:255',
            'email'         => 'required|string|email',
            'referral_code' => 'nullable|string|exists:users,referral_code'
        ];
    
        if (session()->has('user_contact') && session()->get('user_contact') == $request->phone_no) {
    
            if (session()->has('user_otp_id') && session()->has('user_id')) {
                $rules['contact'] = 'required|digits:10';
            } else {
                $rules['contact'] = 'required|digits:10|unique:users,contact';
            }
        } else {
            session()->flush();
            $rules['contact'] = 'required|digits:10|unique:users,contact';
        }
    
        // Validate the request
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()]);
        }
    
        $dlt = config('constants.SMS_LOGIN_DLT');
        $sender_id = config('constants.SMS_LOGIN_SENDER_ID');
    
        // Check if phone_no equals "0000000000"
        if ($request->phone_no == '0000000000') {
            $OTP = '123456'; // Set OTP to 123456 for this specific phone number
        } else {
            $OTP = generateOtp(); // Generate a normal OTP for other numbers
        }

    
        // Message to send
        $msg = "Dear Oswal Soap user, $OTP is your OTP for login to your account. Do not share this with anyone.";
    
        // Send OTP via SMS
        sendOtpSms($msg, session()->get('user_contact') ?? $request->phone_no, $OTP, $dlt, $sender_id);
    
        // Update the OTP record or create a new one
        if (session()->has('user_otp_id') && session()->has('user_id') && session()->has('user_contact')) {
            $otpData = Otp::updateOrCreate(
                ['id' => session()->get('user_otp_id')],
                [
                    'otp' => $OTP,
                    'ip'  => $request->ip(),
                    'date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                ]
            );
        }
    
        // Proceed with normal OTP creation if user isn't in the session
        $referral = [];
        $name =  $request->username;
        $date = [
            'name'      => $name,
            'first_name_hi'   => lang_change($name),
            'device_id'       => $request->device_id,
            'auth'            => generateRandomString(),
            'email'           => $request->email,
            'contact'         => $request->contact,
            'password'        => null,
            'status'          => 0,
            'referral_code'   => User::generateReferralCode(),
            'added_by'        => 1,
            'date'            => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
            'ip'              => $request->ip(),
        ];
    
        $user = UserTemp::create($date);
    
        // Handle referral if referral code is provided
        if ($request->referral_code != null && $request->referral_code != '') {
            $referral = handleReferral($request->referral_code, $user->id);
        }
    
        // Send OTP to user
        sendOtpSms($msg, $user->contact, $OTP, $dlt, $sender_id);
    
        // Create OTP record
        $otpData = Otp::create([
            'name' =>  $name,
            'contact_no' => $user->contact,
            'email' => $request->email,
            'otp' => $OTP,
            'ip' => $request->ip(),
            'added_by' => 1,
            'date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
        ]);
    
        if ($otpData) {
            session()->put('user_otp_id', $otpData->id);
            session()->put('user_id', $user->id);
            session()->put('user_contact', $user->contact);
    
            if ($request->referral_code != null && $request->referral_code != '') {
                session()->put('referrer_tr_id', $referral['referrer_tr_id']);
                session()->put('referee_tr_id', $referral['referee_tr_id']);
            }
    
            return response()->json([ 'success' => true, 'message' => 'OTP sent successfully', 'data' => ['contact_no' => $user->contact] ], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Error occurred while saving OTP, please try again']);
        }
    }


    public function verifyOtpProcess(Request $request) {

        $rules = [
            'otp'           => 'required|numeric',
        ];

        $request->validate($rules);

        // $validator = Validator::make($request->all(), [ 'otp' => 'required|numeric' ]);

        
        // if ($validator->fails()) {

        //     return response()->json(['success' => false, 'message' => $validator->errors()->first()] , 400);
        // }

        $userOtpId = session()->get('user_otp_id');

        $user_id = session()->get('user_id');

        $enteredOtp = $request->input('otp');

        $referrer_tr_id = session()->get('referrer_tr_id') ?? null;

        $referee_tr_id  = session()->get('referee_tr_id') ?? null;

        $otpRecord = Otp::find($userOtpId);
        $storeuser = Otp::orderBy('id', 'DESC')->where('otp', $request->otp)->first();

if ($storeuser) {
    $usertemp = UserTemp::orderBy('id', 'DESC')->where('contact', $storeuser->contact_no)->first();
    log::info('UserTemp found: ' . json_encode($usertemp));
    
    if (empty($usertemp->contact_no)) {
        $usertempuser = User::orderBy('id', 'DESC')->where('contact', $storeuser->contact_no)->first();
        log::info('User found: ' . json_encode($usertempuser));
    }
    
    if (!empty($usertemp)) {
        $date = [
            'first_name'      => $usertemp->name,
            'first_name_hi'   => lang_change($usertemp->name),
            'device_id'       => $usertemp->device_id,
            'auth'            => generateRandomString(),
            'email'           => $usertemp->email,
            'contact'         => $usertemp->contact,
            'password'        => null,
            'status'          => '1',
            'referral_code'   => User::generateReferralCode(),
            'added_by'        => 1,
            'date'            => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
            'ip'              => $request->ip(),
        ];
    } else {
        $date = [
            'first_name'      => $usertempuser->first_name,
            'first_name_hi'   => lang_change($usertempuser->first_name),
            'device_id'       => $usertempuser->device_id,
            'auth'            => generateRandomString(),
            'email'           => $usertempuser->email,
            'contact'         => $usertempuser->contact,
            'password'        => null,
            'status'          => '1',
            'referral_code'   => User::generateReferralCode(),
            'added_by'        => 1,
            'date'            => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
            'ip'              => $request->ip(),
        ];
    }
    
    $existingUser = User::orderBy('id','DESC')->where('contact', $date['contact'])->first();
    log::info('Existing User found: ' . json_encode($existingUser));

    if ($existingUser) {
        $user = $existingUser;
    } else {
        // If the contact does not exist, create a new user.
        $user = User::create($date);
    }
}


        if ($otpRecord && $otpRecord->otp == $enteredOtp) {
            
            // $otpRecord->is_active = 1; 

            // $otpRecord->save();

            // $user = User::find($user_id);

            // $user->status = 1;

            // $user->save();

            $request->session()->regenerate();

            Auth::login($user); 
            log::info('Login User: ' . json_encode($user));

            session()->forget(['user_otp_id', 'user_id', 'user_contact']);

            if(Route::currentRouteName() == 'register.otp'){

                if($referrer_tr_id != null) {
                    $constant = getConstant();
                   $updateTransactionHistory =  WalletTransactionHistory::updateStatus($referrer_tr_id, WalletTransactionHistory::STATUS_COMPLETED , $constant->referrer_amount);

                   if($updateTransactionHistory){

                     WalletTransactionHistory::updateStatus($referee_tr_id, WalletTransactionHistory::STATUS_COMPLETED , $constant->referee_amount);

                     DB::table('refferal_histoty')->whereIn('transaction_id', [$referrer_tr_id, $referee_tr_id])->update(['status' => 1]);

                   }

                   session()->forget(['referrer_tr_id' ,'referee_tr_id']);
                   
                }

                $message = 'You have Register successfully';
               
            }else { 
    
                $message = 'You have Login successfully';
            }

            $user = User::find($user->id);

            return response()->json([ 'success' => true, 'message' =>  $message ,'redirect_url' => url('/user') ], 200);

            // return redirect()->to('/user')->with('success', $message);

        } else {

            // return redirect()->back()->with('error', 'Invalid OTP. Please try again.');

            return response()->json(['success' => false, 'message' => 'Invalid OTP. Please try again.']);

        }

    }

    public function login(Request $request)
{
    $rules = [
        'phone_no' => 'required|digits:10',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
    }

    $user = User::where('contact', $request->phone_no)->first();
    log::info('User found: ' . json_encode($user));
    if ($user) {
        if ($user->is_active == 0) {
            return response()->json(['success' => false, 'message' => 'Your account is not active. Please contact support.']);
        }
    }

    if ($user) {
        if ($user->role_type == 2) {
            return response()->json([
                'success' => false, 
                'message' => 'You do not have permission to log in with this number. Please contact the administrator for assistance.'
            ], 200);                
        }

        // Check if the phone number is '0000000000'
        if ($request->phone_no == '0000000000') {
            $OTP = '123456'; // Set OTP to 123456 for the '0000000000' phone number
        } else {
            $OTP = generateOtp(); // Generate OTP for all other phone numbers
        }

        $dlt = config('constants.SMS_LOGIN_DLT');
        $sender_id = config('constants.SMS_LOGIN_SENDER_ID');

        // Send OTP message
        $msg = "Dear Oswal Soap user, $OTP is your OTP for login to your account. Do not share this with anyone.";

        sendOtpSms($msg, $user->contact, $OTP, $dlt, $sender_id);

        $otpData = Otp::create([
            'name' => $user->first_name,
            'contact_no' => $user->contact,
            'email' => $user->email,
            'otp' => $OTP,
            'ip' => $request->ip(),
            'added_by' => 1,
            'date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
        ]);

        if ($otpData) {
            session()->put('user_otp_id', $otpData->id);
            session()->put('user_id', $user->id);

            return response()->json([ 
                'success' => true, 
                'message' => 'OTP sent successfully', 
                'data' => ['contact_no' => $user->contact]
            ], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Error occurred while saving OTP, please try again']);
        }
    } else {
        return response()->json(['success' => false, 'message' => 'The provided credentials do not match our records.']);
    }
}

    // public function login(Request $request){

    //     $rules = [
    //         'phone_no'           => 'required|digits:10',
    //     ];

    //     // $request->validate($rules);
    //     $validator = Validator::make($request->all(), ['phone_no'  => 'required|digits:10']);

    //     if ($validator->fails()) {

    //         return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
    //     }

    //     $user = User::where('contact', $request->phone_no)->first();

    //     if ($user) {

    //         if($user->role_type == 2) {
    //             return response()->json([
    //                 'success' => false, 
    //                 'message' => 'You do not have permission to log in with this number. Please contact the administrator for assistance.'
    //             ], 200);                
    //         }

    //         $OTP = generateOtp();

    //         $dlt = config('constants.SMS_LOGIN_DLT');
    //         $sender_id = config('constants.SMS_LOGIN_SENDER_ID');

    //         // $msg="Welcome to fineoutput and Your OTP is".$OTP."for Login." ;
    //         $msg = "Dear Oswal Soap user $OTP is your OTP for login to your account. Do not share this with anyone";


    //         sendOtpSms($msg, $user->contact, $OTP, $dlt, $sender_id);

    //         $otpData = Otp::create([
    //             'name' =>  $user->first_name,
    //             'contact_no' => $user->contact,
    //             'email' => $user->email,
    //             'otp' => $OTP,
    //             'ip' => $request->ip(),
    //             'added_by' => 1,
    //             'date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
    //         ]);

    //         if ($otpData) {
            
    //             session()->put('user_otp_id', $otpData->id);
                
    //             session()->put('user_id', $user->id);

    //             // return redirect()->back()->with('success', 'OTP sent successfully');

    //             return response()->json([ 'success' => true, 'message' => 'OTP sent successfully', 'data' => ['contact_no' => $user->contact]],200);

    //         } else {
    //             // return redirect()->back()->with('error', 'Error occurred while saving OTP, please try again');
    //             return response()->json(['success' => false,'message' => 'Error occurred while saving OTP, please try again']);
    //         }
    //     }else{

    //         // return redirect()->back()->with('error', 'The provided credentials do not match our records.');

    //         return response()->json(['success' => false, 'message' => 'The provided credentials do not match our records.']);

    //     }

    //     // Log::warning('Login failed for Phone No.: ' . $request->phone_no);

    // }

    public function logout(Request $request)
    {
        Auth::logout(); 

        $request->session()->invalidate(); 

        $request->session()->regenerateToken(); 

        // return response()->json(['status' => 200, 'message' => 'Logged out'], 200);
        return redirect()->route('/')->with('success', 'Logged out');
    }

}
