<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Parent\UpdateChildAllergyRequest;
use App\Http\Requests\Parent\UpdateChildDietaryRequest;
use App\Http\Requests\Parent\UpdateChildImmunisationRequest;
use App\Models\Child;
use Illuminate\Http\JsonResponse;

class ChildHealthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('user.type:parent');
    }

    public function updateAllergies(UpdateChildAllergyRequest $request, Child $child): JsonResponse
    {
        $this->authorize('update', $child);

        $child->allergies()->sync($request->allergies);

        return response()->json([
            'message' => 'Allergies updated successfully',
            'allergies' => $child->allergies()->with('pivot')->get()
        ]);
    }

    public function updateDietaryRequirements(UpdateChildDietaryRequest $request, Child $child): JsonResponse
    {
        $this->authorize('update', $child);

        $child->dietaryRequirements()->sync($request->dietary_requirements);

        return response()->json([
            'message' => 'Dietary requirements updated successfully',
            'dietary_requirements' => $child->dietaryRequirements()->with('pivot')->get()
        ]);
    }

    public function updateImmunisations(UpdateChildImmunisationRequest $request, Child $child): JsonResponse
    {
        $this->authorize('update', $child);

        $child->immunisations()->sync($request->immunisations);

        return response()->json([
            'message' => 'Immunisations updated successfully',
            'immunisations' => $child->immunisations()->with('pivot')->get()
        ]);
    }
}
