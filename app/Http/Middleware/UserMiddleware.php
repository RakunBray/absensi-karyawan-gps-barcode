<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and belongs to the 'user' group
        if (Auth::check() && Auth::user()->isUser) {
            // Check if the user is verified/approved
            if (Auth::user()->status !== 'approved') {
                 return redirect()->route('approval.pending');
            }
            return $next($request);
        }

        // If the user is not an user, return a 403 Forbidden response
        abort(403);
    }
}
