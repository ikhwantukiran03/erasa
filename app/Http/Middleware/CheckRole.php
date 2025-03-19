<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user has one of the allowed roles (comma-separated)
        $roles = explode(',', $role);
        
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}