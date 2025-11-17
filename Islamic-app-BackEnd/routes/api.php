<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\QuranAudioController;

// Auth Routes (Public)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// Auth Routes (Protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});

// Quran Main Data
Route::prefix('quran')->group(function () {
    Route::get('/surah/{surahId}', [QuranController::class, 'surah']);
    Route::get('/ayah/{reference}', [QuranController::class, 'verse']); // /ayah/112:1
    Route::get('/search', [QuranController::class, 'search']);
});

// Quran Audio
Route::prefix('quran/audio')->controller(QuranAudioController::class)->group(function () {
    Route::get('{surah}/{ayah}', 'ayah');       // آية
    Route::get('surah/{surah}', 'surah');       // سورة كاملة
});

Route::get('/test', fn () => response()->json(['message' => 'API is working!']));
