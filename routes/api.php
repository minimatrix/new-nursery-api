<?php

use App\Http\Controllers\NurseryController;
use App\Http\Controllers\Auth\StaffAuthController;
use App\Http\Controllers\Auth\ParentAuthController;
use App\Http\Controllers\ChildHealthController;
use App\Http\Controllers\EmergencyContactController;
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

// Child Health Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('children/{child}')->group(function () {
        // Health Information
        Route::post('/allergies', [ChildHealthController::class, 'updateAllergies']);
        Route::post('/dietary-requirements', [ChildHealthController::class, 'updateDietaryRequirements']);
        Route::post('/immunisations', [ChildHealthController::class, 'updateImmunisations']);

        // Emergency Contacts
        Route::post('/emergency-contacts', [EmergencyContactController::class, 'store']);
        Route::put('/emergency-contacts/{contact}', [EmergencyContactController::class, 'update']);
        Route::delete('/emergency-contacts/{contact}', [EmergencyContactController::class, 'destroy']);
    });
});

// Staff Routes
Route::prefix('staff')->middleware(['auth:sanctum', 'user.type:staff'])->group(function () {
    // Child Management
    Route::apiResource('children', Staff\ChildController::class);

    Route::prefix('children/{child}')->group(function () {
        // Health Information
        Route::post('/allergies', [Staff\ChildHealthController::class, 'updateAllergies']);
        Route::post('/dietary-requirements', [Staff\ChildHealthController::class, 'updateDietaryRequirements']);
        Route::post('/immunisations', [Staff\ChildHealthController::class, 'updateImmunisations']);

        // Emergency Contacts
        Route::post('/emergency-contacts', [Staff\EmergencyContactController::class, 'store']);
        Route::put('/emergency-contacts/{contact}', [Staff\EmergencyContactController::class, 'update']);
        Route::delete('/emergency-contacts/{contact}', [Staff\EmergencyContactController::class, 'destroy']);
    });
});

// Parent Routes
Route::prefix('parent')->middleware(['auth:sanctum', 'user.type:parent'])->group(function () {
    // Child Management (limited access)
    Route::get('children', [Parent\ChildController::class, 'index']);
    Route::get('children/{child}', [Parent\ChildController::class, 'show']);
    Route::patch('children/{child}', [Parent\ChildController::class, 'update']);

    Route::prefix('children/{child}')->group(function () {
        // Health Information
        Route::post('/allergies', [Parent\ChildHealthController::class, 'updateAllergies']);
        Route::post('/dietary-requirements', [Parent\ChildHealthController::class, 'updateDietaryRequirements']);
        Route::post('/immunisations', [Parent\ChildHealthController::class, 'updateImmunisations']);

        // Emergency Contacts
        Route::post('/emergency-contacts', [Parent\EmergencyContactController::class, 'store']);
        Route::put('/emergency-contacts/{contact}', [Parent\EmergencyContactController::class, 'update']);
        Route::delete('/emergency-contacts/{contact}', [Parent\EmergencyContactController::class, 'destroy']);
    });
});
