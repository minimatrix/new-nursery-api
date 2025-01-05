<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\NurseryResource;
use App\Models\Nursery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NurseryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $nurseries = Nursery::with(['users', 'subscriptionPlan'])->get();
        return NurseryResource::collection($nurseries);
    }

    public function show(Nursery $nursery): JsonResponse
    {
        $nursery->load(['users', 'subscriptionPlan']);
        return response()->json([
            'nursery' => new NurseryResource($nursery)
        ]);
    }
}
