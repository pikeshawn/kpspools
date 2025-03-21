<?php

use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \Spatie\Permission\PermissionServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(AppServiceProvider::HOME);

        $middleware->validateCsrfTokens(except: [
            //
            'register',
        ]);

        $middleware->web([
            \Laravel\Jetstream\Http\Middleware\AuthenticateSession::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);

        $middleware->throttleApi();

        $middleware->alias([
            'customer' => \App\Http\Middleware\CustomerMiddleware::class,
            'prospective' => \App\Http\Middleware\Prospective::class,
            'serviceman' => \App\Http\Middleware\Serviceman::class,
            'subscribed' => \App\Http\Middleware\Subscribed::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
