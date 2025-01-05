<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class StaffPasswordResetController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)
            ->where('type', 'staff')
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'No staff member found with this email address'
            ], 404);
        }

        $token = Password::createToken($user);

        // Here you would typically send an email with the reset link
        // For now, we'll return the token in the response
        return response()->json([
            'message' => 'Password reset link sent successfully',
            'token' => $token // Remove this in production
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                if ($user->type !== 'staff') {
                    return false;
                }

                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json([
                'message' => __($status)
            ], 400);
        }

        return response()->json([
            'message' => 'Password reset successfully'
        ]);
    }
}
