<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class SetGlobalLocation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        $state = 29;
        $city = 629;

        if (Auth::check()) {
            // For logged-in users
            $user = Auth::user();
            $details = DB::table('user_state_city')
                ->where('user_id', $user->id)
                ->first();

            if ($details) {
                $state = $details->state_id;
                $city = $details->city_id;
            }
        } else {
          
            $sessionId = $request->cookie('persistent_id');
            if ($sessionId) {
                $preference = DB::table('user_state_city')
                    ->where('persistent_id', $sessionId)
                    ->first();

                if ($preference) {
                    $state = $preference->state_id;
                    $city = $preference->city_id;
                }
            }
        }

        view()->share('globalState', $state);
        view()->share('globalCity', $city);

        return $next($request);
    }
}
