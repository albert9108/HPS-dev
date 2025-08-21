<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUploadPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Authentication required.'], 401);
        }

        // Allow all admins to upload
        if ($user->isAdmin()) {
            return $next($request);
        }

        // For students, deny upload access (they can only view files)
        if ($user->isStudent()) {
            return response()->json(['error' => 'Students do not have upload permissions.'], 403);
        }

        // Fallback to original whitelist for backward compatibility
        $allowedUsers = [
            'user1@example.com',
            'user2@example.com',
            'HPSadmin',
        ];

        if (!in_array($user->email ?? $user->student_id, $allowedUsers)) {
            return response()->json(['error' => 'Unauthorized upload access.'], 403);
        }

        return $next($request);
    }
}
