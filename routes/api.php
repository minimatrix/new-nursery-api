<?php

use App\Http\Controllers\NurseryController;
use App\Http\Controllers\Auth\StaffAuthController;
use App\Http\Controllers\Auth\ParentAuthController;
use App\Http\Controllers\ChildHealthController;
use App\Http\Controllers\EmergencyContactController;
use App\Http\Controllers\Auth\StaffPasswordResetController;
use App\Http\Controllers\Auth\ParentPasswordResetController;
use App\Http\Controllers\Auth\SuperAdminAuthController;
use App\Http\Controllers\WebhookController;
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
    Route::post('/forgot-password', [StaffPasswordResetController::class, 'forgotPassword']);
    Route::post('/reset-password', [StaffPasswordResetController::class, 'resetPassword']);

    Route::middleware(['auth:sanctum', 'user.type:staff'])->group(function () {
        Route::post('/logout', [StaffAuthController::class, 'logout']);
        // Other staff routes...
    });
});

// Parent authentication routes
Route::prefix('parent')->group(function () {
    Route::post('/login', [ParentAuthController::class, 'login']);
    Route::post('/forgot-password', [ParentPasswordResetController::class, 'forgotPassword']);
    Route::post('/reset-password', [ParentPasswordResetController::class, 'resetPassword']);

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
    // Pricing Bands
    Route::apiResource('pricing-bands', Staff\PricingBandController::class);

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

// Super Admin Routes
Route::prefix('super-admin')->group(function () {
    Route::post('/login', [SuperAdminAuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'super_admin'])->group(function () {
        Route::post('/logout', [SuperAdminAuthController::class, 'logout']);

        // Add routes for managing nurseries
        Route::get('/nurseries', [SuperAdmin\NurseryController::class, 'index']);
        Route::get('/nurseries/{nursery}', [SuperAdmin\NurseryController::class, 'show']);

        // Super Admin User Management
        Route::get('/users', [SuperAdmin\UserController::class, 'index']);
        Route::post('/users', [SuperAdmin\UserController::class, 'store']);
        Route::get('/users/{superAdmin}', [SuperAdmin\UserController::class, 'show']);
        Route::put('/users/{superAdmin}', [SuperAdmin\UserController::class, 'update']);
        Route::delete('/users/{superAdmin}', [SuperAdmin\UserController::class, 'destroy']);

        // Subscription Plan Management
        Route::apiResource('subscription-plans', SuperAdmin\SubscriptionPlanController::class);
    });
});

// Stripe Webhook
Route::post('webhook/stripe', [WebhookController::class, 'handleStripeWebhook']);
