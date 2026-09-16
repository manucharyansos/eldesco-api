<?php

use App\Http\Controllers\Api\Admin\MediaController;
use App\Http\Controllers\Api\Admin\PageController as AdminPageController;
use App\Http\Controllers\Api\Admin\SettingController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PublicSiteController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json(['status' => 'ok']));

Route::prefix('v1')->group(function () {
    Route::get('/site', [PublicSiteController::class, 'site']);
    Route::get('/pages', [PublicSiteController::class, 'pages']);
    Route::get('/pages/{slug}', [PublicSiteController::class, 'page']);

    Route::post('/admin/login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/pages', [AdminPageController::class, 'index']);
        Route::post('/pages', [AdminPageController::class, 'store']);
        Route::get('/pages/{page}', [AdminPageController::class, 'show']);
        Route::put('/pages/{page}', [AdminPageController::class, 'update']);
        Route::delete('/pages/{page}', [AdminPageController::class, 'destroy']);

        Route::get('/settings', [SettingController::class, 'index']);
        Route::put('/settings', [SettingController::class, 'update']);

        Route::get('/media', [MediaController::class, 'index']);
        Route::post('/media', [MediaController::class, 'store']);
        Route::delete('/media/{media}', [MediaController::class, 'destroy']);
    });
});
