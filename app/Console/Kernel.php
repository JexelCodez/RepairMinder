<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\SyncBarangJob;

class Kernel extends ConsoleKernel
{
    /**
     * Daftar command artisan.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\SyncBarangCommand::class,
        \App\Console\Commands\FilamentUser::class,
    ];

    /**
     * Mendefinisikan jadwal cron aplikasi.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Menjalankan SyncBarangJob setiap menit
        $schedule->job(new SyncBarangJob())->everyMinute();
    }

    /**
     * Mendefinisikan command console.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');  // Memuat rute command
    }
}

