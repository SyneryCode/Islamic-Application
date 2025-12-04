<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\UserHabitController;


// قوالب العادات الإسلامية
Route::get('habits', [HabitController::class, 'index']);
Route::post('habits', [HabitController::class, 'store']);
Route::get('habits/{habit}', [HabitController::class, 'show']);
Route::put('habits/{habit}', [HabitController::class, 'update']);
Route::delete('habits/{habit}', [HabitController::class, 'destroy']);

// عادات المستخدم
Route::get('users/{user}/habits', [UserHabitController::class, 'index']);
Route::post('user-habits', [UserHabitController::class, 'store']);
Route::patch('user-habits/{id}/status', [UserHabitController::class, 'updateStatus']);
Route::delete('users/{user}/habits/{id}', [UserHabitController::class, 'destroy']);

Route::apiResource('tasks', TaskController::class);

// راوت إضافي لتعليم المهمة كمنجزة
Route::post('tasks/{task}/complete', [TaskController::class, 'complete']);

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });

});

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});