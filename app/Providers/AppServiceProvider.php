<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SyncBarangService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SyncBarangService::class, function ($app) {
            return new SyncBarangService();
        });
    }

    public function boot()
    {
        //
    }
}