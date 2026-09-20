<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);

        // There is no web login page: unauthenticated API calls get a plain 401 instead of a redirect.
        $middleware->redirectGuestsTo(fn () => null);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // This application is a JSON API: always answer /api requests with JSON (401, 422, ...),
        // never with redirects to a non-existent login page, even when no "Accept" header is sent.
        $exceptions->shouldRenderJsonWhen(fn ($request, Throwable $e) => $request->is('api/*') || $request->expectsJson());
    })
    ->create();
