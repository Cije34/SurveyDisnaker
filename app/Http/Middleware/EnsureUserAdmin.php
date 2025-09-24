<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Gate any request unless it comes from an authenticated admin user.
        if (! $request->user() || ! $request->user()->hasRole('admin')) {
            return redirect()->route('login')->with('status','anda siapa?');
        }

        return $next($request);
    }
}
