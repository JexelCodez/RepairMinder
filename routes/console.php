<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\SyncBarangJob;

// Command untuk men-schedule job sync barang
Artisan::command('sync:barang', function () {
    SyncBarangJob::dispatch();
})->purpose('Sinkronisasi barang dari API A ke aplikasi B');
