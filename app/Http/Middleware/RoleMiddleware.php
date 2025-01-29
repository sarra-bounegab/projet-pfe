<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
{
    if (Auth::check() && Auth::user()->usertype === $role) {
        return $next($request);
    }

    // If the user does not have the required role, redirect them to their dashboard
    return redirect()->route('user.dashboard');
}

}
