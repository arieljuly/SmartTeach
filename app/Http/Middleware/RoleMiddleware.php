<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enum\UserRole;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        try {
            // Convert string roles to UserRole enum instances
            $allowedRoles = array_map(function($role) {
                return UserRole::from($role);
            }, $roles);
            
        } catch (\ValueError $e) {
            Log::error('Invalid role in middleware', ['roles' => $roles, 'error' => $e->getMessage()]);
            abort(500, 'Invalid role configuration.');
        }
        
        // Debug logging
        Log::info('Role check', [
            'user_id' => $user->user_id,
            'user_role_type' => gettype($user->role),
            'user_role' => $user->role instanceof UserRole ? $user->role->value : $user->role,
            'allowed_roles' => array_map(fn($r) => $r->value, $allowedRoles),
            'has_role' => $user->hasAnyRole($allowedRoles)
        ]);
        
        // Check if user has any of the allowed roles
        if (!$user->hasAnyRole($allowedRoles)) {
            Log::warning('Unauthorized access attempt', [
                'user_id' => $user->user_id,
                'user_role' => $user->role instanceof UserRole ? $user->role->value : $user->role,
                'required_roles' => array_map(fn($r) => $r->value, $allowedRoles),
                'uri' => $request->getUri()
            ]);
            
            abort(403, 'Unauthorized action. You do not have the required role.');
        }
        
        return $next($request);
    }
}
