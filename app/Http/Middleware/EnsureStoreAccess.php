<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreAccess
{
    /**
     * Ensure the authenticated user has access to the requested store scope.
     *
     * Super Admins can access any store.
     * Other users can only access their own store's data.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        // Super Admin can access everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if route has a store_id parameter
        $storeId = $request->route('store') ?? $request->input('store_id');

        if ($storeId && (int) $storeId !== (int) $user->store_id) {
            abort(403, 'Anda tidak memiliki akses ke toko ini.');
        }

        return $next($request);
    }
}
