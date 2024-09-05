<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function setLocation(Request $request)
    {
        $state = $request->input('state', 29);
        $city = $request->input('city' , 629);

        if (Auth::check()) {

            $user = Auth::user();

            DB::table('user_state_city')->updateOrCreate([
                'user_id'  =>  $user->id,
                'state_id' =>  $state,
                'city_id'  =>  $city,
            ]);

        } else {
            
            $persistentId = $request->cookie('persistent_id');

            if (!$persistentId) {

                $persistentId = uniqid();

                Cookie::queue('persistent_id', $persistentId, 60 * 24 * 30); // Store persistent ID in a cookie for 30 days
            }

            // Store or update guest preferences
            DB::table('user_state_city')->updateOrCreate(
                ['persistent_id' => $persistentId],
                ['state' => $state, 'city' => $city]
            );
        }

        return redirect()->back(); 
    }

    public function getLocation()
    {
        $state = null;
        $city = null;

        if (Auth::check()) {
         
            $user = Auth::user();
            $state = $user->state;
            $city = $user->city;
        } else {
            
            $persistentId = request()->cookie('persistent_id');
            if ($persistentId) {
                $preference = DB::table('user_state_city')->where('persistent_id', $persistentId)->first();
                if ($preference) {
                    $state = $preference->_id;
                    $city = $preference->city_id;
                }
            }
        }

        return response()->json(['state' => $state, 'city' => $city]);
    }
}
