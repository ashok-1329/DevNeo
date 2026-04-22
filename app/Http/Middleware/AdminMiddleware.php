<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Not logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // DEBUG (IMPORTANT - temporary)
        // dd($user, $user->role);

        // If role missing → allow TEMP (to avoid loop)
        if (!$user->role) {
            return $next($request); // ⚠️ allow for now
        }

        // Check admin role
        if ($user->role->slug !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
