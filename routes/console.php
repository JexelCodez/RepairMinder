<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\SyncBarangJob;
use Illuminate\Support\Facades\Schedule;

// Command untuk men-schedule job sync barang
// Artisan::command('sync:barang', function () {
//     SyncBarangJob::dispatch();
// })->purpose('Sinkronisasi barang dari API A ke aplikasi B');

// Schedule::command('maintenance:reminder')->everyMinute();
// Schedule::command('maintenance:overdue')->everyMinute();

Schedule::everyMinute()
    ->group(function () {
        Schedule::command('maintenance:reminder');
        Schedule::command('maintenance:overdue');
    });