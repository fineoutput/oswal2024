<?php
namespace App\Http\Controllers\ApiManagement;

use Illuminate\Support\Facades\Validator;

use App\Models\WalletTransactionHistory;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Log; 

use Illuminate\Support\Facades\DB;

use App\Models\UserDeviceToken;

use App\Models\Cart;

use Illuminate\Http\Request;

use App\Models\User;

use App\Models\Otp;
use Illuminate\Validation\Rule;


class UserAuthController extends Controller
{
    // public function register(Request $request)
    // {
    //     $rules =[ 
    //         'first_name'    => 'required|string|max:255',
    //         'last_name'     => 'required|string|max:255',
    //         'email'         => 'nullable|string|email',
    //         'password'      => 'nullable|string|min:6',
    //         'device_id'     => 'required|string',
    //         'device_token'  => 'nullable|string',
    //         'referral_code' => 'nullable|string|exists:users,referral_code'
    //     ];
    //     // session()->forget(['user_otp_id', 'user_id', 'user_contact']);
    //     if(session()->has('user_contact') && session()->get('user_contact') == $request->phone_no) {

    //         if (session()->has('user_otp_id') && session()->has('user_id')) {

    //             $rules['phone_no'] = 'required|digits:10';

    //         }else{

    //             $rules['phone_no'] = [
    //                 'required',
    //                 'digits:10',
    //                 Rule::unique('users', 'contact')->whereNull('deleted_at'),
    //             ];
                
    //         }

    //     }else{

    //         session()->flush();

    //         $rules['phone_no'] = [
    //             'required',
    //             'digits:10',
    //             Rule::unique('users', 'contact')->whereNull('deleted_at'),
    //         ];

    //     }
        
    //     $validator = Validator::make($request->all(),  $rules);

    //     if ($validator->fails()) {

    //         return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    //     }

    //     $dlt = config('constants.SMS_LOGIN_DLT');
    //     $sender_id = config('constants.SMS_LOGIN_SENDER_ID');

    //     if (session()->has('user_otp_id') && session()->has('user_id') && session()->has('user_contact')) {
        
    //         $OTP = generateOtp();

    //         // $msg = "Welcome to Oswal. Your new OTP is {$OTP} for registration.";
    //         $msg = "Dear Oswal Soap user $OTP is your OTP for login to your account. Do not share this with anyone";
    
    //         sendOtpSms($msg, session()->get('user_contact'), $OTP, $dlt, $sender_id); // Uncomment this line to send the OTP SMS
    
    //         // Update the existing OTP record
    //         $otpData = Otp::updateOrCreate(

    //             ['id' => session()->get('user_otp_id')],
    //             [
    //                 'otp' => $OTP,
    //                 'ip'  => $request->ip(),
    //                 'date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
    //             ]
    //         );
    
    //         return response()->json([
    //             'status'  => 200,
    //             'message' => 'Your OTP has been regenerated successfully. Please check your phone for the new code',
    //             'data'    => ['contact_no' => session()->get('user_contact')]
    //         ]);
    //     }
    //     $referral = [];

    //     $name =  $request->first_name .' '. $request->last_name;

    //     $date = [
    //         'first_name'      => $name,
    //         'first_name_hi'   => lang_change($name),
    //         'device_id'       => $request->device_id,
    //         'auth'            => generateRandomString(),
    //         'email'           => $request->email,
    //         'contact'         => $request->phone_no,
    //         'password'        => Hash::make($request->password) ?? null,
    //         'status'          => 0,
    //         'referral_code'   => User::generateReferralCode(),
    //         'added_by'        => 1,
    //         'date'            => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
    //         'ip'              => $request->ip(),
    //     ];


    //     $user = User::create($date);

    //     if ($request->referral_code != null && $request->referral_code != '') {

    //         $referral = handleReferral($request->referral_code , $user->id);
           
    //     }

    //     UserDeviceToken::create([
    //         'device_id'    => $request->device_id,
    //         'device_token' => $request->device_token ?? '',
    //         'user_id'      => $user->id,
    //     ]);
        
    //     $OTP = generateOtp();

    //     // $msg="Welcome to Oswal and Your OTP is".$OTP."for Register." ;

    //     $msg = "Dear Oswal Soap user $OTP is your OTP for login to your account. Do not share this with anyone";

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

    //         return response()->json([ 'status' => 200, 'message' => 'OTP sent successfully', 'data' => ['contact_no' => $user->contact]]);

    //     } else {
           
    //         return response()->json(['status' => 500,'message' => 'Error occurred while saving OTP, please try again']);
    //     }
    
    // }


    public function register(Request $request)
{
    $rules = [ 
        'first_name'    => 'required|string|max:255',
        'last_name'     => 'required|string|max:255',
        'email'         => 'nullable|string|email',
        'password'      => 'nullable|string|min:6',
        'device_id'     => 'required|string',
        'device_token'  => 'nullable|string',
        'referral_code' => 'nullable|string|exists:users,referral_code'
    ];

    // session()->forget(['user_otp_id', 'user_id', 'user_contact']);
    if(session()->has('user_contact') && session()->get('user_contact') == $request->phone_no) {

        if (session()->has('user_otp_id') && session()->has('user_id')) {

            $rules['phone_no'] = 'required|digits:10';

        } else {

            $rules['phone_no'] = [
                'required',
                'digits:10',
                Rule::unique('users', 'contact')->whereNull('deleted_at'),
            ];
        }

    } else {

        session()->flush();

        $rules['phone_no'] = [
            'required',
            'digits:10',
            Rule::unique('users', 'contact')->whereNull('deleted_at'),
        ];
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }

    $dlt = config('constants.SMS_LOGIN_DLT');
    $sender_id = config('constants.SMS_LOGIN_SENDER_ID');

    // Check if the phone number is '0000000000' and set OTP to '123456'
    if ($request->phone_no == '0000000000') {
        $OTP = '123456';  // Hardcode OTP to '123456'
    } else {
        $OTP = generateOtp();  // Generate OTP for other phone numbers
    }

    if (session()->has('user_otp_id') && session()->has('user_id') && session()->has('user_contact')) {
        
        // Send OTP SMS
        $msg = "Dear Oswal Soap user $OTP is your OTP for login to your account. Do not share this with anyone";
        sendOtpSms($msg, session()->get('user_contact'), $OTP, $dlt, $sender_id); // Uncomment this line to send the OTP SMS
    
        // Update the existing OTP record
        $otpData = Otp::updateOrCreate(
            ['id' => session()->get('user_otp_id')],
            [
                'otp' => $OTP,
                'ip'  => $request->ip(),
                'date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
            ]
        );
    
        return response()->json([
            'status'  => 200,
            'message' => 'Your OTP has been regenerated successfully. Please check your phone for the new code',
            'data'    => ['contact_no' => session()->get('user_contact')]
        ]);
    }

    $referral = [];

    $name =  $request->first_name .' '. $request->last_name;

    $date = [
        'first_name'      => $name,
        'first_name_hi'   => lang_change($name),
        'device_id'       => $request->device_id,
        'auth'            => generateRandomString(),
        'email'           => $request->email,
        'contact'         => $request->phone_no,
        'password'        => Hash::make($request->password) ?? null,
        'status'          => 0,
        'referral_code'   => User::generateReferralCode(),
        'added_by'        => 1,
        'date'            => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
        'ip'              => $request->ip(),
    ];

    $user = User::create($date);

    if ($request->referral_code != null && $request->referral_code != '') {
        $referral = handleReferral($request->referral_code, $user->id);
    }

    UserDeviceToken::create([
        'device_id'    => $request->device_id,
        'device_token' => $request->device_token ?? '',
        'user_id'      => $user->id,
    ]);
    
    // Send OTP for registration
    $msg = "Dear Oswal Soap user $OTP is your OTP for login to your account. Do not share this with anyone";
    sendOtpSms($msg, $user->contact, $OTP, $dlt, $sender_id);

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

        return response()->json([ 
            'status' => 200, 
            'message' => 'OTP sent successfully', 
            'data' => ['contact_no' => $user->contact]
        ]);
    } else {
        return response()->json(['status' => 500, 'message' => 'Error occurred while saving OTP, please try again']);
    }
}


    public function verifyOtpProcess(Request $request) {

        $validator = Validator::make($request->all(), [ 'otp' => 'required|numeric' ]);

        if ($validator->fails()) {

            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }

        $userOtpId = session()->get('user_otp_id');

        $user_id = session()->get('user_id');

        $enteredOtp = $request->input('otp');

        $referrer_tr_id = session()->get('referrer_tr_id') ?? null;

        $referee_tr_id  = session()->get('referee_tr_id') ?? null;

        $otpRecord = Otp::find($userOtpId);

        if ($otpRecord && $otpRecord->otp == $enteredOtp) {
            
            $otpRecord->is_active = 1; 

            $otpRecord->save();

            $user = User::find($user_id);

            $user->status = 1;

            $user->save();

            $request->session()->regenerate();

            $token = $user->createToken('auth_token')->plainTextToken;
            $user->auth = $token;
            $user->save();

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
            if($user){
            if ($user->role_type == 1) {
                // return 'hello';/
                Cart::where('device_id', $user->device_id)->where('user_id','!=',0)->delete();
            }
        }

        $data = [
            'id' => $user->id,
            'auth' => $user->auth,
            'first_name' => $user->first_name,
            'contact' => $user->contact,
            'referral_code' => $user->referral_code,
            'role_type' => $user->role_type,
        ];

            return response()->json([ 'status' => 200, 'token' => $token, 'data' => $data ,'message' =>  $message], 200);
            
        } else {

            return response()->json(['status' => 401, 'message' => 'Invalid OTP. Please try again.']);

        }

    }


    public function login(Request $request)
{
    $validator = Validator::make($request->all(), ['phone_no'  => 'required|digits:10']);

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }

    $user = User::where('contact', $request->phone_no)->first();

    // Check if the phone number is '0000000000' and set OTP to '123456'
    if ($request->phone_no == '0000000000') {
        $OTP = '123456';  // Hardcode OTP to '123456'
    } else {
        $OTP = generateOtp();  // Generate OTP for other phone numbers
    }

    if ($user) {
        $dlt = config('constants.SMS_LOGIN_DLT');
        $sender_id = config('constants.SMS_LOGIN_SENDER_ID');

        // Prepare the OTP message
        $msg = "Dear Oswal Soap user $OTP is your OTP for login to your account. Do not share this with anyone";

        // Send the OTP SMS
        sendOtpSms($msg, $user->contact, $OTP, $dlt, $sender_id);

        // Save OTP data to the database
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
                'status' => 200, 
                'message' => 'OTP sent successfully', 
                'data' => ['contact_no' => $user->contact]
            ], 200);

        } else {
            return response()->json(['status' => 500, 'message' => 'Error occurred while saving OTP, please try again']);
        }
    } else {
        return response()->json(['status' => 401, 'message' => 'The provided credentials do not match our records.']);
    }
}



    // public function login(Request $request){

    //     $validator = Validator::make($request->all(), ['phone_no'  => 'required|digits:10']);

    //     if ($validator->fails()) {

    //         return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    //     }

    //     $user = User::where('contact', $request->phone_no)->first();

    //     if ($user) {

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

    //             return response()->json([ 'status' => 200, 'message' => 'OTP sent successfully', 'data' => ['contact_no' => $user->contact]],200);

    //         } else {
            
    //             return response()->json(['status' => 500,'message' => 'Error occurred while saving OTP, please try again']);
    //         }
    //     }else{

    //         return response()->json(['status' => 401, 'message' => 'The provided credentials do not match our records.']);

    //     }

    //     // Log::warning('Login failed for Phone No.: ' . $request->phone_no);

    // }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['status' => 200, 'message' => 'Logged out'], 200);
    }

}
