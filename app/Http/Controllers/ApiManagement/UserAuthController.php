<?php
namespace App\Http\Controllers\ApiManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; 
use App\Models\User;
use App\Models\Otp;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([ 
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'nullable|string|email',
            'phone_no'      => 'required|digits:10|unique:users,contact',
            'password'     => 'required|string|min:6',
            'device_id'    => 'required|string',
            'device_token' => 'required|string',
        ]);

        $name =  $request->first_name .' '. $request->last_name;

        $date = [
            'first_name'      => $name,
            'first_name_hi'   => lang_change($name),
            'device_id'       => $request->device_id,
            'auth'            => generateRandomString(),
            'email'           => $request->email,
            'contact'         => $request->phone_no,
            'password'        => Hash::make($request->password),
            'status'          => 0,
            'added_by'        => 1,
            'date'            => now(),
            'ip'              => $request->ip(),
        ];

        $user = User::create($date);

        $OTP = generateOtp();

        $msg="Welcome to fineoutput and Your OTP is".$OTP."for Register." ;

        // sendOtpSms($msg , $user->contact);

        $otpData = Otp::create([
            'name' =>  $name,
            'contact_no' => $user->contact,
            'email' => $request->email,
            'otp' => $OTP,
            'ip' => $request->ip(),
            'added_by' => 1,
            'date' => now(),
        ]);

        if ($otpData) {
           
            session()->put('user_otp_id', $otpData->id);
            
            session()->put('user_id', $user->id);

            return response()->json([ 'status' => true, 'message' => 'OTP sent successfully', 'data' => ['contact_no' => $user->contact]]);

        } else {
           
            return response()->json(['status' => false,'message' => 'Error occurred while saving OTP, please try again'], 500);
        }
    
    }

    public function verifyOtpProcess(Request $request) {

        $request->validate([ 'otp' => 'required|numeric' ]);

        $userOtpId = session()->get('user_otp_id');

        $user_id = session()->get('user_id');

        $enteredOtp = $request->input('otp');

        $otpRecord = Otp::find($userOtpId);

        if ($otpRecord && $otpRecord->otp == $enteredOtp) {
            
            $otpRecord->is_active = 1; 

            $otpRecord->save();

            $user = User::find($user_id);

            $user->status = 1;

            $user->save();

            $request->session()->regenerate();

            $token = $user->createToken('auth_token')->plainTextToken;

            session()->forget('user_otp_id');

            session()->forget('user_id');

            return response()->json([ 'status' => true, 'token' => $token, 'user' => $user ,'message' => 'You have Login successfully'], 200);

        } else {

            return response()->json(['status' => false, 'message' => 'Invalid OTP. Please try again.'], 401);

        }

    }

    public function login(Request $request){

        Log::info('Login request received', $request->all());

        $request->validate(['phone_no'      => 'required|digits:10']);

        $user = User::where('contact', $request->phone_no)->first();

        if ($user) {

            $OTP = generateOtp();

            $msg="Welcome to fineoutput and Your OTP is".$OTP."for Login." ;

            // sendOtpSms($msg , $user->contact);

            $otpData = Otp::create([
                'name' =>  $user->first_name,
                'contact_no' => $user->contact,
                'email' => $user->email,
                'otp' => $OTP,
                'ip' => $request->ip(),
                'added_by' => 1,
                'date' => now(),
            ]);

            if ($otpData) {
            
                session()->put('user_otp_id', $otpData->id);
                
                session()->put('user_id', $user->id);

                return response()->json([ 'status' => true, 'message' => 'OTP sent successfully', 'data' => ['contact_no' => $user->contact]]);

            } else {
            
                return response()->json(['status' => false,'message' => 'Error occurred while saving OTP, please try again'], 500);
            }
        }else{

            return response()->json(['message' => 'The provided credentials do not match our records.'], 401);

        }

        Log::warning('Login failed for Phone No.: ' . $request->phone_no);

    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }

}
