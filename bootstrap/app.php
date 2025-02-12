<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\GuruMiddleware;
use App\Http\Middleware\RoleAdminOrTeknisiMiddleware;
use App\Http\Middleware\SiswaMiddleware;
use App\Http\Middleware\TeknisiMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware -> alias ([
            'guru' => GuruMiddleware :: class,
            'siswa' => SiswaMiddleware :: class,
            'role.teknisi' => TeknisiMiddleware :: class,
            'role.admin' => AdminMiddleware :: class,
            'role.admin_teknisi' => RoleAdminOrTeknisiMiddleware :: class,
       ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
