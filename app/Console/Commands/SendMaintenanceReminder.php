<?php
namespace App\Console\Commands;

use App\Filament\DKV\Resources\PeriodePemeliharaanDKVResource;
use App\Filament\Resources\PeriodePemeliharaanResource;
use App\Filament\Sarpras\Resources\PeriodePemeliharaanSarprasResource;
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

    $sevenDaysAgo = Carbon::now()->subDays(7)->toDateString();

    $maintenanceList = PeriodePemeliharaan::whereDate('tanggal_maintenance_selanjutnya', $sevenDaysAgo)->get();


        \Log::info('Total maintenance list matching criteria: ' . $maintenanceList->count());

        if ($maintenanceList->isEmpty()) {
            \Log::info('No maintenance items match the criteria.');
            return self::SUCCESS;
        }

        foreach ($maintenanceList as $maintenance) {
            $teknisiUsers = collect();
        
            // Cek barang ada di model inventaris mana
            if (InventarisDKV::where('kode_barang', $maintenance->kode_barang)->exists()) {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) => 
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'dkv'))
                    )
                    ->orWhere(fn($query) =>
                        $query->where('role', 'teknisi')
                            ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sarpras'))
                    )
                    ->get();
                    
        
                // Menggunakan LaporanDKVResource untuk route laporan DKV
                $laporUrl = PeriodePemeliharaanDKVResource::getUrl('view', ['record' => $maintenance], panel: 'dKV');
            } elseif (InventarisSarpras::where('kode_barang', $maintenance->kode_barang)->exists()) {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) => 
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sarpras'))
                    )->get();
        
                $laporUrl = PeriodePemeliharaanSarprasResource::getUrl('view', ['record' => $maintenance], panel: 'sarpras');
            } else {
                $teknisiUsers = User::where('role', 'admin')
                    ->orWhere(fn($query) =>
                        $query->where('role', 'teknisi')
                              ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sija'))
                    )
                    ->orWhere(fn($query) =>
                        $query->where('role', 'teknisi')
                            ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sarpras'))
                    )
                    ->get();
        
                $laporUrl = PeriodePemeliharaanResource::getUrl('view', ['record' => $maintenance], panel: 'admin');
            }
        
            foreach ($teknisiUsers as $user) {
                Notification::make()
                    ->title('🔧 Maintenance Reminder')
                    ->color('info')
                    ->body("Barang {$maintenance->kode_barang} perlu maintenance dalam 7 hari terakhir hingga hari ini. Klik untuk melihat detail.")
                    ->actions([
                        Action::make('Lihat')->icon('heroicon-o-eye')->url($laporUrl),
                    ])
                    ->sendToDatabase($user);
            }
        }
        

        return self::SUCCESS;
    }
}
