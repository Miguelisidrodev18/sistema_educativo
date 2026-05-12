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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn() => route('login'));

        $middleware->alias([
            'role'                => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'          => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission'  => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'require.password'    => \App\Http\Middleware\RequirePasswordChange::class,
        ]);

        // Aplicar el middleware a todas las rutas autenticadas
        $middleware->appendToGroup('web', \App\Http\Middleware\RequirePasswordChange::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Token CSRF expirado (419) → redirigir al login con mensaje
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            return redirect()->route('login')
                ->with('error', 'Tu sesión expiró. Por favor inicia sesión nuevamente.');
        });
    })->create();
