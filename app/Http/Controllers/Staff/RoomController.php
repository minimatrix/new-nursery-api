<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\Room\StoreRequest;
use App\Http\Requests\Staff\Room\UpdateRequest;
use App\Http\Requests\Staff\Room\AssignStaffRequest;
use App\Http\Requests\Staff\Room\AssignChildrenRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Models\User;
use App\Models\Child;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('user.type:staff');
    }

    public function index(): AnonymousResourceCollection
    {
        $rooms = auth()->user()->is_admin
            ? Room::with(['staff', 'children'])->get()
            : auth()->user()->rooms()->with(['staff', 'children'])->get();

        return RoomResource::collection($rooms);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $this->authorize('create', Room::class);

        $room = Room::create([
            'nursery_id' => auth()->user()->nursery_id,
            ...$request->validated()
        ]);

        return response()->json([
            'message' => 'Room created successfully',
            'room' => new RoomResource($room)
        ], 201);
    }

    public function show(Room $room): JsonResponse
    {
        $this->authorize('view', $room);

        $room->load(['staff', 'children']);

        return response()->json([
            'room' => new RoomResource($room)
        ]);
    }

    public function update(UpdateRequest $request, Room $room): JsonResponse
    {
        $this->authorize('update', $room);

        $room->update($request->validated());

        return response()->json([
            'message' => 'Room updated successfully',
            'room' => new RoomResource($room)
        ]);
    }

    public function destroy(Room $room): JsonResponse
    {
        $this->authorize('delete', $room);

        $room->delete();

        return response()->json([
            'message' => 'Room deleted successfully'
        ]);
    }

    public function assignStaff(AssignStaffRequest $request, Room $room): JsonResponse
    {
        $this->authorize('update', $room);

        $room->staff()->sync($request->staff);

        return response()->json([
            'message' => 'Staff assigned successfully',
            'room' => new RoomResource($room->load('staff'))
        ]);
    }

    public function assignChildren(AssignChildrenRequest $request, Room $room): JsonResponse
    {
        $this->authorize('update', $room);

        Child::whereIn('id', $request->children)
            ->update(['room_id' => $room->id]);

        return response()->json([
            'message' => 'Children assigned successfully',
            'room' => new RoomResource($room->load('children'))
        ]);
    }
}
