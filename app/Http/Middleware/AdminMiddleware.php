<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        
        if (auth()->id() && auth()->id() == 1) {
            return $next($request);
        }

        abort(404);
    }
}
