<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // Register global middleware here if needed. We intentionally do NOT append CheckIfAlive globally
    // so it will be applied as a per-route middleware (after 'auth') to ensure the session and user are available.
    ->withMiddleware(function (Middleware $middleware) {
        // no global middleware appended here
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
