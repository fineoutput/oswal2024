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

use Illuminate\Http\Request;

use App\Models\Vendor;

use App\Models\User;

use App\Models\Otp;

class VendorAuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|string|email',
            'device_id'         => 'required|string',
            'device_token'      => 'nullable|string',
            'referral_code'     => 'nullable|string|exists:users,referral_code',
            'shopname'          => 'required|string',
            'pincode'           => 'required|integer|digits:6',
            'city'              => 'required|integer',
            'state'             => 'required|integer',
            'address'           => 'required|string',
            'addhar_front_image'=> 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'addhar_back_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gstno'             => 'required|string|size:15',
        ];

        if (session()->has('user_contact') && session()->get('user_contact') == $request->phone_no) {

            if (session()->has('user_otp_id') && session()->has('user_id')) {

                $rules['phone_no'] = 'required|digits:10';

                $rules['addhar_front_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
                $rules['addhar_back_image']  = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            } else {

                $rules['phone_no'] = 'required|digits:10|unique:users,contact';

                $rules['addhar_front_image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
                $rules['addhar_back_image']  = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            }

        } else {

            session()->flush();

            $rules['phone_no'] = 'required|digits:10|unique:users,contact';

            $rules['addhar_front_image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            $rules['addhar_back_image']  = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }

        $dlt = config('constants.SMS_SIGNUP_DLT');

        $sender_id = config('constants.SMS_SIGNUP_SENDER_ID');

        if (session()->has('user_otp_id') && session()->has('user_id') && session()->has('user_contact')) {
            $OTP = generateOtp();

            // $msg = "Welcome to Oswal. Your new OTP is {$OTP} for registration.";
            $msg = "Dear User,
                    Your OTP for signup on OSWALMART is $OTP and is valid for 30 minutes. Please do not share this OTP with anyone.

                    Welcome!!

                    Regards,
                    OSWAL SOAP";

            sendOtpSms($msg, session()->get('user_contact'), $OTP, $dlt, $sender_id); // Uncomment this line to send the OTP SMS

            // Update the existing OTP record
            $otpData = Otp::updateOrCreate(
                ['id' => session()->get('user_otp_id')],
                [
                    'otp' => $OTP,
                    'ip' => $request->ip(),
                    'date' => now()
                        ->setTimezone('Asia/Kolkata')
                        ->format('Y-m-d H:i:s'),
                ]
            );

            return response()->json([
                'status' => 200,
                'message' => 'Your OTP has been regenerated successfully. Please check your phone for the new code',
                'data' => ['contact_no' => session()->get('user_contact')],
            ]);
        }
        $referral = [];

        $name = $request->first_name . ' ' . $request->last_name;

        $date = [
            'role_type'     => 2,
            'first_name'    => $name,
            'first_name_hi' => lang_change($name),
            'device_id'     => $request->device_id,
            'auth'          => generateRandomString(),
            'email'         => $request->email,
            'contact'       => $request->phone_no,
            'password'      => Hash::make($request->password) ?? null,
            'status'        => 0,
            'referral_code' => User::generateReferralCode(),
            'added_by'      => 1,
            'date'          => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
            'ip'            => $request->ip(),
        ];

        $user = User::create($date);

        if($request->hasFile('addhar_front_image')){

            $addhar_front_image = uploadImage($request->file('addhar_front_image'), 'vendor' ,'Addhar' , 'front');

        }
        if($request->hasFile('addhar_back_image')){

            $addhar_back_image = uploadImage($request->file('addhar_back_image'), 'vendor' ,'Addhar' , 'back');

        }
        $vendor = Vendor::create([
            'user_id'  => $user->id,
            'shopname' => $request->shopname,
            'pincode'  => $request->pincode,
            'city_id'  => $request->city,
            'state_id' => $request->state,
            'address'  => $request->address,
            'addhar_front_image' => $addhar_front_image,
            'addhar_back_image'  => $addhar_back_image,
            'gstno' => $request->gstno,
        ]);

        if ($request->referral_code != null && $request->referral_code != '') {
            $referral = handleReferral($request->referral_code, $user->id);
        }

        UserDeviceToken::create([
            'device_id' => $request->device_id,
            'device_token' => $request->device_token ?? '',
            'user_id' => $user->id,
        ]);

        $OTP = generateOtp();

        // $msg="Welcome to Oswal and Your OTP is".$OTP."for Register." ;

        $msg = "Dear User,
                Your OTP for signup on OSWALMART is $OTP and is valid for 30 minutes. Please do not share this OTP with anyone.

                Welcome!!

                Regards,
                OSWAL SOAP";

        sendOtpSms($msg, $user->contact, $OTP, $dlt, $sender_id);

        $otpData = Otp::create([
            'name' => $name,
            'contact_no' => $user->contact,
            'email' => $request->email,
            'otp' => $OTP,
            'ip' => $request->ip(),
            'added_by' => 1,
            'date' => now()
                ->setTimezone('Asia/Kolkata')
                ->format('Y-m-d H:i:s'),
        ]);

        if ($otpData) {

            session()->put('user_otp_id', $otpData->id);

            session()->put('user_id', $user->id);

            session()->put('user_contact', $user->contact);

            if ($request->referral_code != null && $request->referral_code != '') {
                
                session()->put('referrer_tr_id', $referral['referrer_tr_id']);

                session()->put('referee_tr_id', $referral['referee_tr_id']);
            }

            return response()->json(['status' => 200, 'message' => 'OTP sent successfully', 'data' => ['contact_no' => $user->contact]]);
        } else {
            return response()->json(['status' => 500, 'message' => 'Error occurred while saving OTP, please try again']);
        }
    }

    public function verifyOtpProcess(Request $request)
    {
        $validator = Validator::make($request->all(), ['otp' => 'required|numeric']);

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }

        $userOtpId = session()->get('user_otp_id');

        $user_id = session()->get('user_id');

        $enteredOtp = $request->input('otp');

        $referrer_tr_id = session()->get('referrer_tr_id') ?? null;

        $referee_tr_id = session()->get('referee_tr_id') ?? null;

        $otpRecord = Otp::find($userOtpId);

        if ($otpRecord && $otpRecord->otp == $enteredOtp) {
            $otpRecord->is_active = 1;

            $otpRecord->save();

            $user = User::find($user_id);

            $user->status = 1;

            $user->save();

            $request->session()->regenerate();

            $token = $user->createToken('auth_token')->plainTextToken;

            session()->forget(['user_otp_id', 'user_id', 'user_contact']);

            if (Route::currentRouteName() == 'register.otp') {
                if ($referrer_tr_id != null) {
                    $constant = getConstant();
                    $updateTransactionHistory = WalletTransactionHistory::updateStatus($referrer_tr_id, WalletTransactionHistory::STATUS_COMPLETED, $constant->referrer_amount);

                    if ($updateTransactionHistory) {
                        WalletTransactionHistory::updateStatus($referee_tr_id, WalletTransactionHistory::STATUS_COMPLETED, $constant->referee_amount);

                        DB::table('refferal_histoty')
                            ->whereIn('transaction_id', [$referrer_tr_id, $referee_tr_id])
                            ->update(['status' => 1]);
                    }

                    session()->forget(['referrer_tr_id', 'referee_tr_id']);
                }

                $message = 'You have Register successfully';
            } else {
                $message = 'You have Login successfully';
            }

            $user = User::find($user->id);

            return response()->json(['status' => 200, 'token' => $token, 'user' => $user, 'message' => $message], 200);
        } else {
            return response()->json(['status' => 401, 'message' => 'Invalid OTP. Please try again.']);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), ['phone_no' => 'required|digits:10']);

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }

        $user = User::where('contact', $request->phone_no)->first();

        if ($user) {
            $OTP = generateOtp();

            $dlt = config('constants.SMS_LOGIN_DLT');
            $sender_id = config('constants.SMS_LOGIN_SENDER_ID');

            // $msg="Welcome to fineoutput and Your OTP is".$OTP."for Login." ;
            $msg = "Dear User,
                    Your OTP for login on OSWALMART is $OTP and is valid for 30 minutes. Please do not share this OTP with anyone.

                    Regards,
                    OSWAL SOAP";

            sendOtpSms($msg, $user->contact, $OTP, $dlt, $sender_id);

            $otpData = Otp::create([
                'name' => $user->first_name,
                'contact_no' => $user->contact,
                'email' => $user->email,
                'otp' => $OTP,
                'ip' => $request->ip(),
                'added_by' => 1,
                'date' => now()
                    ->setTimezone('Asia/Kolkata')
                    ->format('Y-m-d H:i:s'),
            ]);

            if ($otpData) {
                session()->put('user_otp_id', $otpData->id);

                session()->put('user_id', $user->id);

                return response()->json(['status' => 200, 'message' => 'OTP sent successfully', 'data' => ['contact_no' => $user->contact]], 200);
            } else {
                return response()->json(['status' => 500, 'message' => 'Error occurred while saving OTP, please try again']);
            }
        } else {
            return response()->json(['status' => 401, 'message' => 'The provided credentials do not match our records.']);
        }

        Log::warning('Login failed for Phone No.: ' . $request->phone_no);
    }

    public function logout(Request $request)
    {
        $request
            ->user()
            ->tokens()
            ->delete();

        return response()->json(['status' => 200, 'message' => 'Logged out'], 200);
    }
}