<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController, ServiceController, ProjectController, TeamController,
    NewsController, GalleryController, PageController, MediaController,
    AdminContentController, SiteController
};

Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::get('/site', [SiteController::class, 'show']);
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

        Route::get('/admin/services', [AdminContentController::class, 'services']);
        Route::get('/admin/services/{id}', [AdminContentController::class, 'service']);
        Route::get('/admin/projects', [AdminContentController::class, 'projects']);
        Route::get('/admin/projects/{id}', [AdminContentController::class, 'project']);
        Route::get('/admin/team', [AdminContentController::class, 'team']);
        Route::get('/admin/team/{id}', [AdminContentController::class, 'teamMember']);
        Route::get('/admin/news', [AdminContentController::class, 'news']);
        Route::get('/admin/news/{id}', [AdminContentController::class, 'newsItem']);

        Route::post('/admin/pages/{page}/sections', [PageController::class, 'storeSection']);
        Route::put('/admin/sections/{section}', [PageController::class, 'updateSection']);
        Route::delete('/admin/sections/{section}', [PageController::class, 'destroySection']);
        Route::get('/admin/media', [MediaController::class, 'index']);
        Route::post('/admin/media', [MediaController::class, 'store']);
        Route::delete('/admin/media/{id}', [MediaController::class, 'destroy'])->whereNumber('id');

        Route::get('/admin/site', [SiteController::class, 'adminShow']);
        Route::put('/admin/site/settings', [SiteController::class, 'updateSettings']);
        Route::put('/admin/site/navigation', [SiteController::class, 'updateNavigation']);

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
