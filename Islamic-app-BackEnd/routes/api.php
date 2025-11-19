<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\QuranAudioController;
use App\Http\Controllers\QuranNavigatorController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

/*
|--------------------------------------------------------------------------
| Quran Data
|--------------------------------------------------------------------------
*/

Route::prefix('quran')->group(function () {

    // سورة - كامل السورة مع الآيات الـ 6236
    Route::get('/surah/{surahId}', [QuranController::class, 'surah']);

    // آية — مثال: /quran/ayah/36:12
    Route::get('/ayah/{reference}', [QuranController::class, 'verse']);

    // بحث عن آيات
    Route::get('/search', [QuranController::class, 'search']);

    /*
    |--------------------------------------------------------------------------
    | Navigation (Pages, Juz, Hizb)
    |--------------------------------------------------------------------------
    */

    Route::get('/page/{page}', [QuranNavigatorController::class, 'page']);
    Route::get('/juz/{juz}', [QuranNavigatorController::class, 'juz']);
    Route::get('/hizb/{hizb}', [QuranNavigatorController::class, 'hizb']);
    Route::get('/quarter/{quarter}', [QuranNavigatorController::class, 'quarter']);

    // Info
    Route::get('/surah/info/{id}', [QuranNavigatorController::class, 'surahInfo']);
    Route::get('/page/info/{page}', [QuranNavigatorController::class, 'pageInfo']);

    /*
    |--------------------------------------------------------------------------
    | Navigation Next / Previous
    |--------------------------------------------------------------------------
    */

    Route::get('/next/{surah}/{ayah}', [QuranNavigatorController::class, 'nextAyah']);
    Route::get('/prev/{surah}/{ayah}', [QuranNavigatorController::class, 'previousAyah']);

    /*
    |--------------------------------------------------------------------------
    | Audio
    |--------------------------------------------------------------------------
    */

    Route::prefix('audio')->controller(QuranAudioController::class)->group(function () {
        Route::get('/{surah}/{ayah}', 'ayah');        // آية بصوت قارئ
        Route::get('/surah/{surah}', 'surah');        // سورة كاملة
    });
});

// Test endpoint
Route::get('/test', fn () => response()->json(['message' => 'API is working!']));
