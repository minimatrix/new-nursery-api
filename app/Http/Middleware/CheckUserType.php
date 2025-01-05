<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    public function handle(Request $request, Closure $next, string $type): Response
    {
        if (request()->user()->type !== $type) {
            return response()->json([
                'message' => 'Unauthorized. ' . ucfirst($type) . ' access required.'
            ], 403);
        }

        return $next($request);
    }
}
