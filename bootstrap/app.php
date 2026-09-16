<?php

use App\Http\Middleware\AdminToken;
use App\Http\Middleware\CorsMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(CorsMiddleware::class);
        $middleware->alias([
            'admin.token' => AdminToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // API errors are rendered as JSON by Laravel when the request expects JSON.
    })->create();
