<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSubscription
{
    public function handle(Request $request, Closure $next, string ...$features)
    {
        $user = request()->user();

        if (!$user || !$user->nursery || !$user->nursery->hasActiveSubscription()) {
            return response()->json([
                'message' => 'Active subscription required'
            ], 403);
        }

        if (!empty($features)) {
            $plan = $user->nursery->subscriptionPlan;
            $planFeatures = $plan->features ?? [];

            if (!empty(array_diff($features, $planFeatures))) {
                return response()->json([
                    'message' => 'Your current plan does not include this feature'
                ], 403);
            }
        }

        return $next($request);
    }
}
