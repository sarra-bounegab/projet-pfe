<?php



namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // If the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // If the user's status is 0 (pending approval), log them out and show the message
            if ($user->status == 0) {
                Auth::logout(); // Log them out
                return redirect()->route('login')->with('error', 'Votre compte est en attente d\'approbation.');
            }
    
            // If the user's status is 1, proceed with normal login
            if ($user->status == 1) {
                // You can optionally add a message to notify users when they log in if needed
                // session()->flash('message', 'Bienvenue!');
            }
        }
    
        return $next($request);
    }
    
}
