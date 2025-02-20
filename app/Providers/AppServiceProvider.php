<?php

namespace App\Providers;

use App\Models\PeriodePemeliharaan;
use Illuminate\Support\ServiceProvider;
use App\Services\SyncBarangService;
use Filament\Facades\Filament;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

use App\Models\Maintenance;
use App\Observers\MaintenanceObserver;

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
        Maintenance::observe(MaintenanceObserver::class);
    }



}
