<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     * Ensures only users with 'customer' role can access customer routes
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has customer role
        if (!auth()->check() || !auth()->user()->isCustomer()) {
            abort(403, 'Unauthorized access. Customer only.');
        }

        return $next($request);
    }
}