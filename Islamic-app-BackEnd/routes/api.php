<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\QuranAudioController;
use App\Http\Controllers\QuranNavigatorController;
use App\Http\Controllers\ZakatController;


/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Http\Request;

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
/*huhuh*/
Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

/*
|--------------------------------------------------------------------------
| Quran Data Routes
|--------------------------------------------------------------------------
*/

Route::prefix('quran')->group(function () {

    // سورة / آية / بحث
    Route::get('/surah/{surahId}', [QuranController::class, 'surah']);
    Route::get('/ayah/{reference}', [QuranController::class, 'verse']);
    Route::get('/search', [QuranController::class, 'search']);

    // التصفح
    Route::get('/page/{page}', [QuranNavigatorController::class, 'page']);
    Route::get('/juz/{juz}', [QuranNavigatorController::class, 'juz']);
    Route::get('/hizb/{hizb}', [QuranNavigatorController::class, 'hizb']);
    Route::get('/quarter/{quarter}', [QuranNavigatorController::class, 'quarter']);

    // معلومات
    Route::get('/surah/info/{id}', [QuranNavigatorController::class, 'surahInfo']);
    Route::get('/page/info/{page}', [QuranNavigatorController::class, 'pageInfo']);

    // التالي / السابق
    Route::get('/next/{surah}/{ayah}', [QuranNavigatorController::class, 'nextAyah']);
    Route::get('/prev/{surah}/{ayah}', [QuranNavigatorController::class, 'previousAyah']);

    // AUDIO
    Route::get('/audio/ayah/{surah}/{ayah}', [QuranAudioController::class, 'ayah'])
        ->whereNumber('surah')->whereNumber('ayah');

    Route::get('/audio/surah/{surah}', [QuranAudioController::class, 'surah'])
        ->whereNumber('surah');
});


/*
|--------------------------------------------------------------------------
| Zakat Endpoint
|--------------------------------------------------------------------------
*/
Route::post('/zakat/calculate', [ZakatController::class, 'calculate']);


/*
|--------------------------------------------------------------------------
| Test Endpoint
|--------------------------------------------------------------------------
*/
Route::get('/test', fn () => response()->json(['message' => 'API is working!']));
