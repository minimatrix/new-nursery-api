<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\User\StoreRequest;
use App\Http\Requests\SuperAdmin\User\UpdateRequest;
use App\Http\Resources\SuperAdminResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('super_admin');
    }

    public function index(): AnonymousResourceCollection
    {
        $superAdmins = User::where('type', 'super_admin')->get();
        return SuperAdminResource::collection($superAdmins);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $superAdmin = User::create([
            ...$request->validated(),
            'password' => Hash::make($request->password),
            'type' => 'super_admin',
            'nursery_id' => null,
        ]);

        return response()->json([
            'message' => 'Super admin created successfully',
            'super_admin' => new SuperAdminResource($superAdmin)
        ], 201);
    }

    public function show(User $superAdmin): JsonResponse
    {
        $this->ensureSuperAdmin($superAdmin);

        return response()->json([
            'super_admin' => new SuperAdminResource($superAdmin)
        ]);
    }

    public function update(UpdateRequest $request, User $superAdmin): JsonResponse
    {
        $this->ensureSuperAdmin($superAdmin);

        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $superAdmin->update($data);

        return response()->json([
            'message' => 'Super admin updated successfully',
            'super_admin' => new SuperAdminResource($superAdmin)
        ]);
    }

    public function destroy(User $superAdmin): JsonResponse
    {
        $this->ensureSuperAdmin($superAdmin);

        // Prevent deleting the last super admin
        if (User::where('type', 'super_admin')->count() <= 1) {
            return response()->json([
                'message' => 'Cannot delete the last super admin'
            ], 422);
        }

        // Prevent self-deletion
        if ($superAdmin->id === auth()->id()) {
            return response()->json([
                'message' => 'Cannot delete your own account'
            ], 422);
        }

        $superAdmin->delete();

        return response()->json([
            'message' => 'Super admin deleted successfully'
        ]);
    }

    private function ensureSuperAdmin(User $user): void
    {
        if ($user->type !== 'super_admin') {
            abort(404, 'Super admin not found');
        }
    }
}
