<?php

namespace App\Http\Controllers;

use App\Http\Requests\NurseryRegistrationRequest;
use App\Http\Resources\NurseryResource;
use App\Http\Resources\UserResource;
use App\Models\Nursery;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NurseryController extends Controller
{
    public function register(NurseryRegistrationRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $nursery = Nursery::create([
                'name' => $request->nursery_name,
                'email' => $request->nursery_email,
            ]);

            $admin = User::create([
                'nursery_id' => $nursery->id,
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'type' => 'staff',
                'is_admin' => true,
            ]);

            return response()->json([
                'message' => 'Nursery registered successfully',
                'nursery' => new NurseryResource($nursery),
                'admin' => new UserResource($admin),
                'token' => $admin->createToken('admin-token')->plainTextToken,
            ], 201);
        });
    }
}
