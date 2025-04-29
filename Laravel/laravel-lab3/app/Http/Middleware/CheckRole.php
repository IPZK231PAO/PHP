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
    public function handle($request, Closure $next, ...$roles)
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    $user = auth()->user();
    $userRole = $user->role;
    \Log::info("CheckRole: User role = $userRole, Allowed roles = " . implode(', ', $roles));

    if (!in_array($userRole, $roles)) {
        abort(403, "Ваша роль ($userRole) не має доступу до цього ресурсу.");
    }

    return $next($request);
}
}
