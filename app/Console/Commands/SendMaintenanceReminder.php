<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeriodePemeliharaan;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Carbon\Carbon;

class SendMaintenanceReminder extends Command
{
    protected $signature = 'maintenance:reminder';
    protected $description = 'Kirim notifikasi maintenance jika tanggal maintenance selanjutnya antara 1 hingga 10 hari dari sekarang';

    public function handle(): int
    {
        \Log::info('Maintenance reminder command triggered at: ' . now()->toDateTimeString());

        // Ambil data maintenance antara 1 dan 10 hari dari sekarang
        $maintenanceList = PeriodePemeliharaan::whereBetween('tanggal_maintenance_selanjutnya', [
            Carbon::now()->addDays(1)->toDateString(),
            Carbon::now()->addDays(10)->toDateString()
        ])->get();

        \Log::info('Total maintenance list matching criteria: ' . $maintenanceList->count());
        if ($maintenanceList->isEmpty()) {
            \Log::info('No maintenance items match the criteria.');
        }

        // Ambil semua user yang role teknisi
        $teknisiUsers = User::where('role', ['teknisi', 'admin'])->get();
        \Log::info('Found ' . $teknisiUsers->count() . ' teknisi users.');

        foreach ($maintenanceList as $maintenance) {
            \Log::info("Maintenance for {$maintenance->kode_barang}: scheduled date {$maintenance->tanggal_maintenance_selanjutnya}");

            foreach ($teknisiUsers as $user) {
                \Log::info("Notifying teknisi {$user->email} for maintenance {$maintenance->kode_barang}");
                Notification::make()
                    ->title('🔧 Maintenance Reminder')
                    ->color('info')
                    ->body("Barang {$maintenance->kode_barang} perlu maintenance dalam 1-10 hari. Klik untuk melihat detail.")
                    ->actions([
                        Action::make('Lihat')->icon('heroicon-o-eye')->url('/admin/maintenance'),
                    ])
                    ->sendToDatabase($user);
            }
        }

        return self::SUCCESS;
    }
}