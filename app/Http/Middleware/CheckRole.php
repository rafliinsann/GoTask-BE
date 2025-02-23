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
    public function handle($request, Closure $next, $role)
{
    $user = auth()->user();
    $workspace = $request->route('workspace');

    if ($user->isSuperAdmin()) {
        return $next($request); // Superadmin bisa akses semua
    }

    if ($workspace->owner_id === $user->id && in_array($role, ['owner', 'member'])) {
        return $next($request); // Owner workspace
    }

    if (in_array($user->id, $workspace->members ?? []) && $role === 'member') {
        return $next($request); // Member workspace
    }

    return response()->json(['error' => 'Unauthorized'], 403);
}

}
