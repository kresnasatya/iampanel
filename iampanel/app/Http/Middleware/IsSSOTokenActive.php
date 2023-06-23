<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use RistekUSDI\Kisara\Base;

class IsSSOTokenActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $is_token_active = (new Base(config('sso')))->isTokenActive($request->bearerToken());
        if (!$is_token_active) {
            return response()->json([
                'message' => 'Invalid token.'
            ], 401);
        }
        return $next($request);
    }
}
