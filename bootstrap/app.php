<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AdminOrTeknisiDkv;
use App\Http\Middleware\AdminOrTeknisiSarpras;
use App\Http\Middleware\AdminOrTeknisiSija;
use App\Http\Middleware\GuruMiddleware;
use App\Http\Middleware\RoleAdminOrTeknisiMiddleware;
use App\Http\Middleware\SiswaMiddleware;
use App\Http\Middleware\TeknisiMiddleware;
use App\Http\Middleware\CheckMaintenanceDueMiddleware; // pastikan import ini
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
        $middleware->alias([
            'guru' => GuruMiddleware::class,
            'siswa' => SiswaMiddleware::class,
            'teknisi' => TeknisiMiddleware::class,
            'role.admin_teknisi_sija' => AdminOrTeknisiSija::class,
            'role.admin_teknisi_dkv' => AdminOrTeknisiDkv::class,
            'role.admin_teknisi_sarpras' => AdminOrTeknisiSarpras::class,
            // Tambahkan alias middleware maintenance jika diperlukan
            'maintenance' => CheckMaintenanceDueMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();