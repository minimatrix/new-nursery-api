<?php

use App\Http\Controllers\NurseryController;
use App\Http\Controllers\Auth\StaffAuthController;
use App\Http\Controllers\Auth\ParentAuthController;
use Illuminate\Support\Facades\Route;

// Test route to verify API is working
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

// Public routes
Route::post('/nursery/register', [NurseryController::class, 'register']);

// Staff authentication routes
Route::prefix('staff')->group(function () {
    Route::post('/login', [StaffAuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'user.type:staff'])->group(function () {
        Route::post('/logout', [StaffAuthController::class, 'logout']);
        // Other staff routes...
    });
});

// Parent authentication routes
Route::prefix('parent')->group(function () {
    Route::post('/login', [ParentAuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'user.type:parent'])->group(function () {
        Route::post('/logout', [ParentAuthController::class, 'logout']);
        // Other parent routes...
    });
});
