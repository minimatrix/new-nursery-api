<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Parent\EmergencyContact\StoreRequest;
use App\Http\Requests\Parent\EmergencyContact\UpdateRequest;
use App\Models\Child;
use App\Models\EmergencyContact;
use Illuminate\Http\JsonResponse;

class EmergencyContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('user.type:parent');
    }

    public function store(StoreRequest $request, Child $child): JsonResponse
    {
        $this->authorize('update', $child);

        $contact = $child->emergencyContacts()->create($request->validated());

        return response()->json([
            'message' => 'Emergency contact added successfully',
            'contact' => $contact
        ], 201);
    }

    public function update(UpdateRequest $request, Child $child, EmergencyContact $contact): JsonResponse
    {
        $this->authorize('update', $child);

        if ($contact->child_id !== $child->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $contact->update($request->validated());

        return response()->json([
            'message' => 'Emergency contact updated successfully',
            'contact' => $contact
        ]);
    }

    public function destroy(Child $child, EmergencyContact $contact): JsonResponse
    {
        $this->authorize('update', $child);

        if ($contact->child_id !== $child->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $contact->delete();

        return response()->json([
            'message' => 'Emergency contact deleted successfully'
        ]);
    }
}
