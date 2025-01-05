<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->type !== 'super_admin') {
            return response()->json([
                'message' => 'Unauthorized. Super admin access required.'
            ], 403);
        }

        return $next($request);
    }
}
