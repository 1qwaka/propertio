<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MyAuthAdminMiddleware
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

        if (!$user->is_admin) {
            return response()->json(['message' => 'You must be an Admin to perform this action'], 403);
        }

        return $next($request);
    }
}
