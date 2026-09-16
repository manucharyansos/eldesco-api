<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    ServiceController,
    ProjectController,
    TeamController,
    NewsController,
    GalleryController
};

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Public API endpoints
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{id}', [ProjectController::class, 'show']);

Route::get('/team', [TeamController::class, 'index']);
Route::get('/team/{id}', [TeamController::class, 'show']);

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{slugOrId}', [NewsController::class, 'show']);

Route::get('/gallery', [GalleryController::class, 'index']);
Route::get('/gallery/categories', [GalleryController::class, 'categories']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/refresh', [AuthController::class, 'refreshToken']);

    // Admin endpoints
    Route::middleware('admin')->group(function () {
        // Services
        Route::post('/services', [ServiceController::class, 'store']);
        Route::put('/services/{id}', [ServiceController::class, 'update']);
        Route::delete('/services/{id}', [ServiceController::class, 'destroy']);

        // Projects
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::put('/projects/{id}', [ProjectController::class, 'update']);
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

        // Team
        Route::post('/team', [TeamController::class, 'store']);
        Route::put('/team/{id}', [TeamController::class, 'update']);
        Route::delete('/team/{id}', [TeamController::class, 'destroy']);

        // News
        Route::post('/news', [NewsController::class, 'store']);
        Route::put('/news/{id}', [NewsController::class, 'update']);
        Route::delete('/news/{id}', [NewsController::class, 'destroy']);

        // Gallery
        Route::post('/gallery', [GalleryController::class, 'store']);
        Route::put('/gallery/{id}', [GalleryController::class, 'update']);
        Route::delete('/gallery/{id}', [GalleryController::class, 'destroy']);
    });
});

// Fallback for health check
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
