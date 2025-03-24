<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (Auth::check() && Auth::user()->profile && Auth::user()->profile->role === $role) {
            return $next($request);
        }

        return redirect()->route('dashboard')->withErrors('Accès refusé.');
    }
}
