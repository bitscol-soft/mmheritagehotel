<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VerifyApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->token) {
            $user = User::where('api_token', $request->token)->first();
            if ($user) {
                Auth::login($user);
                return $next($request);
            }
        }

        return response()->json(['status' => false, 'message' => 'Invalid token']);
    }
}
