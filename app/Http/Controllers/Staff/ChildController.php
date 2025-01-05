<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\Child\StoreRequest;
use App\Http\Requests\Staff\Child\UpdateRequest;
use App\Http\Resources\ChildResource;
use App\Models\Child;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChildController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('user.type:staff');
    }

    public function index(): AnonymousResourceCollection
    {
        $children = Child::where('nursery_id', auth()->user()->nursery_id)
            ->with(['guardians', 'allergies', 'dietaryRequirements', 'immunisations'])
            ->get();

        return ChildResource::collection($children);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $child = Child::create([
            'nursery_id' => auth()->user()->nursery_id,
            ...$request->validated()
        ]);

        return response()->json([
            'message' => 'Child created successfully',
            'child' => new ChildResource($child)
        ], 201);
    }

    public function show(Child $child): JsonResponse
    {
        $this->authorize('view', $child);

        $child->load(['guardians', 'allergies', 'dietaryRequirements', 'immunisations']);

        return response()->json([
            'child' => new ChildResource($child)
        ]);
    }

    public function update(UpdateRequest $request, Child $child): JsonResponse
    {
        $this->authorize('update', $child);

        $child->update($request->validated());

        return response()->json([
            'message' => 'Child updated successfully',
            'child' => new ChildResource($child)
        ]);
    }

    public function destroy(Child $child): JsonResponse
    {
        $this->authorize('update', $child);

        $child->delete();

        return response()->json([
            'message' => 'Child deleted successfully'
        ]);
    }
}
