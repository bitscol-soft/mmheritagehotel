<?php

namespace App\Http\Middleware;

use App\Models\UserLoginStatus;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LoginActivityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        UserLoginStatus::where('date', '<', date('Y-m-d'))->delete();
        
        if (Auth::check()) {
            UserLoginStatus::updateOrCreate([
                'user_id' => auth()->id(),
                'date'    => date('Y-m-d')
            ],
            [
                'company_id' => auth()->user()->company_id
            ]);

            $exprie_at = Carbon::now()->addMinute(5);
            
            Cache::put('logged-in-users-' . auth()->id(), true, $exprie_at);
        }
        return $next($request);
    }
}
