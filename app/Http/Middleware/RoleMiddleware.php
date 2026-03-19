<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        $allowedRoles = [];

        foreach ($roles as $role) {
            $allowedRoles = array_merge(
                $allowedRoles,
                array_map('trim', explode(',', $role))
            );
        }

        // Only valid roles in system
        $allowedRoles = array_intersect($allowedRoles, ['admin', 'student']);

        if (!in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
