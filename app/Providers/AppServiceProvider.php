<?php

namespace App\Providers;

use App\Models\PeriodePemeliharaan;
use Illuminate\Support\ServiceProvider;
use App\Services\SyncBarangService;
use Filament\Facades\Filament;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

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
        // Filament::serving(function () {
        //     if (Auth::check()) {
        //         $user = Auth::user();
        //         $today = now()->toDateString(); // Get today's date

        //         // Get all items that are due for maintenance
        //         $items = PeriodePemeliharaan::whereNotNull('tanggal_maintenance_selanjutnya')->get();

        //         foreach ($items as $item) {
        //             // Check if a notification for this item has already been sent today
        //             $existingNotification = $user->notifications()
        //                 ->whereJsonContains('data->kode_barang', $item->kode_barang) // Ensure JSON query works
        //                 ->where('notifiable_id', $user->id)
        //                 ->whereDate('created_at', $today)
        //                 ->first(); // Fetch first matching notification

        //             if (!$existingNotification) {
        //                 Notification::make()
        //                     ->title('⚠️ Maintenance Due!')
        //                     ->color('warning')
        //                     ->body("🛠️ Maintenance untuk {$item->kode_barang} sudah jatuh tempo pada {$item->tanggal_maintenance_selanjutnya}.")
        //                     ->actions([
        //                         Action::make('Lihat Detail')->icon('heroicon-o-eye')
        //                     ])
        //                     ->sendToDatabase($user);
        //             }

        //         }
        //     }
        // });
    }



}
