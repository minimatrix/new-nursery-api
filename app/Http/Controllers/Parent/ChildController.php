<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Parent\Child\UpdateRequest;
use App\Http\Resources\ChildResource;
use App\Models\Child;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChildController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('user.type:parent');
    }

    public function index(): AnonymousResourceCollection
    {
        $children = Child::whereHas('guardians', function ($query) {
            $query->where('user_id', auth()->id());
        })->with(['guardians', 'allergies', 'dietaryRequirements', 'immunisations'])
            ->get();

        return ChildResource::collection($children);
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
}
