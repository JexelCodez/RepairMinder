<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeriodePemeliharaan;
use App\Models\InventarisDKV;
use App\Models\InventarisSarpras;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Carbon\Carbon;

class SendMaintenanceReminder extends Command
{
    protected $signature = 'maintenance:reminder';
    protected $description = 'Kirim notifikasi maintenance sesuai dengan kode barang ke teknisi yang tepat';

    public function handle(): int
    {
        \Log::info('Maintenance reminder command triggered at: ' . now()->toDateTimeString());

    // Ambil data maintenance tepat 7 hari ke depan
    $maintenanceDate = Carbon::now()->addDays(7)->toDateString();
    $maintenanceList = PeriodePemeliharaan::whereDate('tanggal_maintenance_selanjutnya', $maintenanceDate)->get();


        \Log::info('Total maintenance list matching criteria: ' . $maintenanceList->count());

        if ($maintenanceList->isEmpty()) {
            \Log::info('No maintenance items match the criteria.');
            return self::SUCCESS;
        }

        foreach ($maintenanceList as $maintenance) {
            $teknisiUsers = collect();
            $laporUrl = '/admin/maintenance';

            // Cek barang ada di model inventaris mana
            if (InventarisDKV::where('kode_barang', $maintenance->kode_barang)->exists()) {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) => 
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'dkv'))
                    )->get();

                $laporUrl = "/admin/maintenance/dkv";
            } elseif (InventarisSarpras::where('kode_barang', $maintenance->kode_barang)->exists()) {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) => 
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sarpras'))
                    )->get();

                $laporUrl = "/admin/maintenance/sarpras";
            } else {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) =>
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sija'))
                    )->get();

                $laporUrl = "/admin/maintenance/sija";
            }

            \Log::info("Maintenance for {$maintenance->kode_barang}: scheduled date {$maintenance->tanggal_maintenance_selanjutnya}");

            foreach ($teknisiUsers as $user) {
                \Log::info("Notifying teknisi {$user->email} for maintenance {$maintenance->kode_barang}");

                Notification::make()
                    ->title('🔧 Maintenance Reminder')
                    ->color('info')
                    ->body("Barang {$maintenance->kode_barang} perlu maintenance dalam 1-10 hari. Klik untuk melihat detail.")
                    ->actions([
                        Action::make('Lihat')->icon('heroicon-o-eye')->url($laporUrl),
                    ])
                    ->sendToDatabase($user);
            }
        }

        return self::SUCCESS;
    }
}
