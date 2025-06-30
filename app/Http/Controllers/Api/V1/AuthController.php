<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController
{
    public function login(AuthLoginRequest $request): JsonResponse
    {
        $user = User::firstWhere(['email' => $request->validated('email')]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 0,
                'message' => __('auth.failed'),
                'data' => []
            ], 401);
        }

        return response()->json([
            'status' => 1,
            'message' => __('auth.login_successful'),
            'data' => [
                'token' => $user->createToken('API-Token')->plainTextToken,
            ]
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 1,
            'message' => __('auth.logout_successful'),
            'data' => []
        ], 200);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email'),
            function (User $user, string $token) {
                $user->sendPasswordResetNotification($token); // uses your custom notification below
            }
        );

        if (empty($status)) {
            return response()->json([
                'status' => 0,
                'message' => __('api.data.something_wrong'),
                'data' => []
            ], 400);
        }

        return response()->json([
            'status' => 1,
            'message' => __('auth.verification_email_sent'),
            'data' => []
        ], 200);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if (empty($status)) {
            return response()->json([
                'status' => 0,
                'message' => __('api.data.something_wrong'),
                'data' => []
            ], 400);
        }

        return response()->json([
            'status' => 1,
            'message' => __('api.data.updated_successful'),
            'data' => []
        ], 200);
    }
}
