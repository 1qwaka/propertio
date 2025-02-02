<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'You need to login',
                'id' => Auth::id(),
                'user' => Auth::user(),
            ], 401);
        }

        return $next($request);
    }
}
