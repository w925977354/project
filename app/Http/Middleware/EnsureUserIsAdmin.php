<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Admin Authorization Middleware
 * 
 * This middleware ensures that only users with is_admin=true can access protected routes.
 * This is a critical security measure for the admin panel.
 * 
 * SECURITY IMPLEMENTATION:
 * - Checks if user is authenticated
 * - Verifies the user has admin privileges (is_admin = true)
 * - Returns 403 Forbidden for unauthorized access attempts
 */
class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            abort(403, 'Unauthorized. Please login first.');
        }

        // Check if user has admin privileges
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized. Admin access only.');
        }

        // User is authenticated and is an admin, allow access
        return $next($request);
    }
}
