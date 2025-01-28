<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MyAuthAgentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user == null) {
            return response()->json(['message' => 'You are not logged in'], 401);
        }

        if ($user->agent == null) {
            return response()->json(['message' => 'You must be an Agent to perform this action'], 403);
        }

        return $next($request);
    }
}
