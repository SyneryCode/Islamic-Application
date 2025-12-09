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
        // التحقق من البيانات المدخلة
        $data = $request->validated();

        // 1) التحقق من وجود البريد الإلكتروني في النظام
        if (User::where('email', $data['email'])->exists()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'البريد الإلكتروني مستخدم مسبقاً.',
            ], 409); // Conflict
        }

        // 2) التحقق من وجود اسم المستخدم
        if (User::where('username', $data['username'])->exists()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'اسم المستخدم محجوز مسبقاً.',
            ], 409); // Conflict
        }

        // 3) إنشاء المستخدم الجديد عبر الخدمة
        $user = $this->authService->register($data);

        // 4) تسجيل الدخول تلقائياً بعد التسجيل لإنشاء التوكن
        $loginResult = $this->authService->login([
            'email'    => $user->email,
            'password' => $data['password'], // تأكد من إرسال كلمة السر
        ]);

        // تحقق من نتيجة تسجيل الدخول
        if (!$loginResult || empty($loginResult['token'])) {
            throw new \RuntimeException('فشل إنشاء التوكن بعد التسجيل.');
        }

        // 5) إرجاع التوكن مع بيانات المستخدم
        return response()->json([
            'status'  => 'success',
            'message' => 'تم إنشاء الحساب وتسجيل الدخول بنجاح.',
            'data'    => [
                'user'  => [
                    'id'       => $user->id,
                    'username' => $user->username,
                    'email'    => $user->email,
                ],
                'token' => $loginResult['token'],  // التوكن الذي تم إنشاؤه بعد التسجيل
            ],
        ], 201); // 201 تعني أنه تم إنشاء المستخدم بنجاح

    } catch (\Exception $e) {
        // معالجة جميع الأخطاء التي قد تحدث
        Log::error('Registration error: ' . $e->getMessage(), [
            'stack_trace' => $e->getTraceAsString(),
            'request_data' => $request->all()  // طباعة بيانات الطلب للمراجعة
        ]);

        // تحديد نوع الخطأ بناءً على محتوى الاستثناء
        return response()->json([
            'status'  => 'error',
            'message' => 'فشل إنشاء الحساب، يرجى المحاولة مرة أخرى لاحقاً.',
            'error'   => $e->getMessage(),  // إضافة الرسالة التفصيلية هنا
        ], 500);  // 500 تشير لوجود مشكلة في السيرفر
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