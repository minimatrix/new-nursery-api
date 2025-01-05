<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\PricingBand\StoreRequest;
use App\Http\Requests\Staff\PricingBand\UpdateRequest;
use App\Http\Resources\PricingBandResource;
use App\Models\PricingBand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PricingBandController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $pricingBands = PricingBand::orderBy('min_age_months')->get();
        return PricingBandResource::collection($pricingBands);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $this->authorize('create', PricingBand::class);

        $pricingBand = PricingBand::create([
            'nursery_id' => request()->user()->nursery_id,
            ...$request->validated()
        ]);

        return response()->json([
            'message' => 'Pricing band created successfully',
            'pricing_band' => new PricingBandResource($pricingBand)
        ], 201);
    }

    public function show(PricingBand $pricingBand): JsonResponse
    {
        $this->authorize('view', $pricingBand);

        return response()->json([
            'pricing_band' => new PricingBandResource($pricingBand)
        ]);
    }

    public function update(UpdateRequest $request, PricingBand $pricingBand): JsonResponse
    {
        $this->authorize('update', $pricingBand);

        $pricingBand->update($request->validated());

        return response()->json([
            'message' => 'Pricing band updated successfully',
            'pricing_band' => new PricingBandResource($pricingBand)
        ]);
    }

    public function destroy(PricingBand $pricingBand): JsonResponse
    {
        $this->authorize('delete', $pricingBand);

        $pricingBand->delete();

        return response()->json([
            'message' => 'Pricing band deleted successfully'
        ]);
    }
}
