<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

class SuperAdminPasswordResetController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)
            ->where('type', 'super_admin')
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'We cannot find a super admin with that email address.'
            ], 404);
        }

        // Generate reset token
        $resetToken = Str::random(60);

        // Store token in user's tokens table with specific abilities
        $user->tokens()->create([
            'name' => 'password-reset',
            'token' => hash('sha256', $resetToken),
            'abilities' => ['password-reset'],
            'expires_at' => now()->addHours(1), // Token expires in 1 hour
        ]);

        // Send email with reset token
        Mail::to($user->email)->send(new PasswordResetMail($resetToken));

        return response()->json([
            'message' => 'Password reset link sent to your email.'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)
            ->where('type', 'super_admin')
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid email address.'
            ], 404);
        }

        // Find valid reset token
        $resetToken = $user->tokens()
            ->where('name', 'password-reset')
            ->where('expires_at', '>', now())
            ->first();

        if (!$resetToken) {
            return response()->json([
                'message' => 'Invalid or expired reset token.'
            ], 400);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Revoke all tokens
        $user->tokens()->delete();

        // Create new access token
        $token = $user->createToken('auth-token', ['*'])->plainTextToken;

        return response()->json([
            'message' => 'Password reset successfully.',
            'token' => $token
        ]);
    }
}
