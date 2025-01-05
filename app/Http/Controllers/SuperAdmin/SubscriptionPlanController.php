<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\SubscriptionPlan\StoreRequest;
use App\Http\Requests\SuperAdmin\SubscriptionPlan\UpdateRequest;
use App\Http\Resources\SubscriptionPlanResource;
use App\Models\SubscriptionPlan;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubscriptionPlanController extends Controller
{
    public function __construct(private StripeService $stripeService)
    {
        $this->middleware('auth:sanctum');
        $this->middleware('super_admin');
    }

    public function index(): AnonymousResourceCollection
    {
        $plans = SubscriptionPlan::withCount('nurseries')->get();
        return SubscriptionPlanResource::collection($plans);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $plan = SubscriptionPlan::create($request->validated());

        return response()->json([
            'message' => 'Subscription plan created successfully',
            'plan' => new SubscriptionPlanResource($plan)
        ], 201);
    }

    public function show(SubscriptionPlan $plan): JsonResponse
    {
        $plan->loadCount('nurseries');
        return response()->json([
            'plan' => new SubscriptionPlanResource($plan)
        ]);
    }

    public function update(UpdateRequest $request, SubscriptionPlan $plan): JsonResponse
    {
        $plan->update($request->validated());

        return response()->json([
            'message' => 'Subscription plan updated successfully',
            'plan' => new SubscriptionPlanResource($plan)
        ]);
    }

    public function destroy(SubscriptionPlan $plan): JsonResponse
    {
        if ($plan->nurseries()->exists()) {
            return response()->json([
                'message' => 'Cannot delete plan with active subscriptions'
            ], 422);
        }

        $plan->delete();

        return response()->json([
            'message' => 'Subscription plan deleted successfully'
        ]);
    }
}
