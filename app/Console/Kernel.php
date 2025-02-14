<?php
// filepath: /c:/LARAVEL/RepairMinder/app/Console/Kernel.php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\User;
use App\Models\PeriodePemeliharaan;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\SendMaintenanceReminder::class,
        \App\Console\Commands\FilamentUser::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('maintenance:reminder')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}