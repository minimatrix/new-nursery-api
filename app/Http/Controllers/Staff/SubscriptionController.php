<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionPlanResource;
use App\Models\SubscriptionPlan;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class SubscriptionController extends Controller
{
    public function __construct(private StripeService $stripeService) {}

    public function availablePlans(): JsonResponse
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return response()->json([
            'plans' => SubscriptionPlanResource::collection($plans)
        ]);
    }

    public function createSetupIntent(): JsonResponse
    {
        $nursery = request()->user()->nursery;

        if (!$nursery->stripe_customer_id) {
            $nursery->stripe_customer_id = $this->stripeService->createCustomer($nursery);
            $nursery->save();
        }

        $clientSecret = $this->stripeService->createSetupIntent($nursery);

        return response()->json([
            'client_secret' => $clientSecret
        ]);
    }

    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'plan_id' => ['required', 'exists:subscription_plans,id'],
            'payment_method_id' => ['required', 'string']
        ]);

        $nursery = request()->user()->nursery;
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        try {
            $subscription = $this->stripeService->createSubscription($nursery, $plan);

            $nursery->update([
                'subscription_plan_id' => $plan->id,
                'stripe_subscription_id' => $subscription['subscription_id'],
                'subscription_ends_at' => now()->addSeconds($subscription['current_period_end']),
            ]);

            return response()->json([
                'message' => 'Subscription created successfully',
                'subscription' => $subscription
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create subscription: ' . $e->getMessage()
            ], 422);
        }
    }

    public function cancel(): JsonResponse
    {
        $nursery = request()->user()->nursery;

        if (!$nursery->stripe_subscription_id) {
            return response()->json([
                'message' => 'No active subscription found'
            ], 404);
        }

        try {
            $this->stripeService->cancelSubscription($nursery->stripe_subscription_id);

            $nursery->update([
                'subscription_ends_at' => now(),
                'stripe_subscription_id' => null,
                'subscription_plan_id' => null
            ]);

            return response()->json([
                'message' => 'Subscription cancelled successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to cancel subscription: ' . $e->getMessage()
            ], 422);
        }
    }
}
