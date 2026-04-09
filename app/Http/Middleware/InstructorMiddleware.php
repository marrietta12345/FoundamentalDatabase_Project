<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InstructorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access the instructor dashboard.');
        }

        $user = Auth::user();
        
        // Check if user role is instructor (also check for 'teacher' for backward compatibility)
        if ($user->role !== 'instructor' && $user->role !== 'teacher') {
            // Redirect to appropriate dashboard based on role
            if ($user->role === 'student') {
                return redirect()->route('dashboard')->with('error', 'Access denied. This area is for instructors only.');
            }
            
            // For admin or other roles
            return redirect()->route('dashboard')->with('error', 'You do not have instructor privileges.');
        }

        return $next($request);
    }
}