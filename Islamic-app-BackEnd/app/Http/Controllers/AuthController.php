<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->register($request->validated());

            return response()->json([
                'message' => 'User registered successfully',
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Registration failed'
            ], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        if (!$result) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $result['user']->id,
                'username' => $result['user']->username,
                'email' => $result['user']->email,
            ],
            'token' => $result['token']
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $token = $this->authService->requestPasswordReset($request->email);

        if (!$token) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // In a real application, you would send an email here
        // For demo purposes, we'll return the token
        return response()->json([
            'message' => 'Password reset token generated',
            'reset_token' => $token // Remove this in production
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $success = $this->authService->resetPassword($request->validated());

        if (!$success) {
            return response()->json([
                'message' => 'Invalid or expired reset token'
            ], 400);
        }

        return response()->json([
            'message' => 'Password reset successfully'
        ]);
    }

public function user(Request $request): JsonResponse
{
    try {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Token is invalid or expired'
            ], 401);
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Unauthorized',
            'message' => 'Token is invalid'
        ], 401);
    }
}
}