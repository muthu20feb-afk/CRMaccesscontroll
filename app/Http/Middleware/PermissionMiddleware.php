<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
class PermissionMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $routeName = Route::currentRouteName();

        if ($user->hasRole('admin')) {
            return $next($request);
        }

        if ($user->hasPermission($routeName)) {
            return $next($request);
        }

        abort(403, 'You do not have permission to access this route.');

        return $next($request);
    }
}
