<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserType
{
    public function handle(Request $request, Closure $next, string $userType): Response
    {
        if ($request->user()->type !== $userType) {
            return response()->json([
                'message' => 'Unauthorized access.'
            ], 403);
        }

        return $next($request);
    }
}
