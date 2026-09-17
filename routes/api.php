<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController, ServiceController, ProjectController, TeamController,
    NewsController, GalleryController, PageController, MediaController
};

Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/pages', [PageController::class, 'index']);
Route::get('/pages/{slug}', [PageController::class, 'show']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/categories', [ProjectController::class, 'categories']);
Route::get('/projects/{id}', [ProjectController::class, 'show']);
Route::get('/team', [TeamController::class, 'index']);
Route::get('/team/{id}', [TeamController::class, 'show']);
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{slugOrId}', [NewsController::class, 'show']);
Route::get('/gallery', [GalleryController::class, 'index']);
Route::get('/gallery/categories', [GalleryController::class, 'categories']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/refresh', [AuthController::class, 'refreshToken']);

    Route::middleware('admin')->group(function () {
        Route::get('/admin/pages', [PageController::class, 'adminIndex']);
        Route::get('/admin/pages/{page}', [PageController::class, 'adminShow']);
        Route::post('/admin/pages', [PageController::class, 'store']);
        Route::put('/admin/pages/{page}', [PageController::class, 'update']);
        Route::delete('/admin/pages/{page}', [PageController::class, 'destroy']);

        Route::post('/admin/pages/{page}/sections', [PageController::class, 'storeSection']);
        Route::put('/admin/sections/{section}', [PageController::class, 'updateSection']);
        Route::delete('/admin/sections/{section}', [PageController::class, 'destroySection']);
        Route::post('/admin/media', [MediaController::class, 'store']);

        foreach ([
            'services' => ServiceController::class,
            'projects' => ProjectController::class,
            'team' => TeamController::class,
            'news' => NewsController::class,
            'gallery' => GalleryController::class,
        ] as $uri => $controller) {
            Route::post("/$uri", [$controller, 'store']);
            Route::put("/$uri/{id}", [$controller, 'update']);
            Route::delete("/$uri/{id}", [$controller, 'destroy']);
        }
    });
});

Route::get('/health', fn () => response()->json(['status' => 'ok']));
