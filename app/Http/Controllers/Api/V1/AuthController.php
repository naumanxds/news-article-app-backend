<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\AuthLoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
}
