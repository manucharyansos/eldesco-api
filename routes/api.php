<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    ServiceController,
    ProjectController,
    TeamController,
    NewsController,
    GalleryController,
    PageController,
    SiteSettingController,
    MediaController
};

// Authentication. Admin accounts are created by seeder / server operator, not publicly.
Route::post('/auth/login', [AuthController::class, 'login']);

// Public CMS
Route::get('/pages', [PageController::class, 'index']);
Route::get('/pages/{slug}', [PageController::class, 'show']);
Route::get('/site-settings', [SiteSettingController::class, 'publicIndex']);

// Legacy/public content endpoints kept for compatibility.
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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/refresh', [AuthController::class, 'refreshToken']);

    Route::middleware('admin')->group(function () {
        // CMS pages and sections
        Route::get('/admin/pages', [PageController::class, 'index']);
        Route::get('/admin/pages/{slug}', [PageController::class, 'show']);
        Route::post('/admin/pages', [PageController::class, 'store']);
        Route::put('/admin/pages/{page}', [PageController::class, 'update']);
        Route::delete('/admin/pages/{page}', [PageController::class, 'destroy']);
        Route::post('/admin/pages/{page}/sections', [PageController::class, 'upsertSection']);
        Route::post('/admin/pages/{page}/sections/reorder', [PageController::class, 'reorderSections']);
        Route::put('/admin/pages/{page}/sections/{section}', [PageController::class, 'upsertSection']);
        Route::delete('/admin/pages/{page}/sections/{section}', [PageController::class, 'deleteSection']);

        // Global header/footer/contact/company settings
        Route::get('/admin/site-settings', [SiteSettingController::class, 'index']);
        Route::put('/admin/site-settings/{key}', [SiteSettingController::class, 'upsert']);
        Route::delete('/admin/site-settings/{key}', [SiteSettingController::class, 'destroy']);
        Route::post('/admin/media', [MediaController::class, 'store']);

        // Legacy CRUD
        Route::post('/services', [ServiceController::class, 'store']);
        Route::put('/services/{id}', [ServiceController::class, 'update']);
        Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::put('/projects/{id}', [ProjectController::class, 'update']);
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
        Route::post('/team', [TeamController::class, 'store']);
        Route::put('/team/{id}', [TeamController::class, 'update']);
        Route::delete('/team/{id}', [TeamController::class, 'destroy']);
        Route::post('/news', [NewsController::class, 'store']);
        Route::put('/news/{id}', [NewsController::class, 'update']);
        Route::delete('/news/{id}', [NewsController::class, 'destroy']);
        Route::post('/gallery', [GalleryController::class, 'store']);
        Route::put('/gallery/{id}', [GalleryController::class, 'update']);
        Route::delete('/gallery/{id}', [GalleryController::class, 'destroy']);
    });
});

Route::get('/health', fn () => response()->json(['status' => 'ok']));
