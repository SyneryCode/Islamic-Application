<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuranController;

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

Route::prefix('quran')->group(function () {
    Route::get('/surah/{surahId}', [QuranController::class, 'surah']);
    Route::get('/ayah/{reference}', [QuranController::class, 'verse']); // مثال: /ayah/112:1
    Route::get('/search', [QuranController::class, 'search']);
});

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});