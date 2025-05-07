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
        
        if (Auth::check()) {
            $user = Auth::user();
            
           
            if ($user->status == 0) {
                Auth::logout(); 
                return redirect()->route('login')->with('error', 'Votre compte est en attente d\'approbation.');
            }
    
          
            if ($user->status == 1) {
                
                // session()->flash('message', 'Bienvenue!');
            }
        }
    
        return $next($request);
    }
    
}
