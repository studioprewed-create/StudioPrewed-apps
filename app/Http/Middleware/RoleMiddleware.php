<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!in_array(Auth::user()->role, $roles)) {
            return redirect()
                ->route('dashboard')
                ->with('warning', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
