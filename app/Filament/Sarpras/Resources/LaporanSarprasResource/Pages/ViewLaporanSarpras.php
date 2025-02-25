<?php

namespace App\Filament\Sarpras\Resources\LaporanSarprasResource\Pages;

use App\Filament\Sarpras\Resources\LaporanSarprasResource;
use App\Models\Laporan;
use App\Models\Inventaris;
use App\Models\InventarisDKV;
use App\Models\InventarisSarpras;
use App\Models\Teknisi;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Select;

class ViewLaporanSarpras extends ViewRecord
{
    protected static string $resource = LaporanSarprasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Badge Status
            Actions\Action::make('Status')
                ->color(fn (Laporan $record) => match ($record->status) {
                    'pending' => 'gray',
                    'processed' => 'warning',
                    'done' => 'success',
                })
                ->label(fn (Laporan $record) => ucfirst($record->status))
                ->disabled()
                ->icon(fn (Laporan $record) => match ($record->status) {
                    'pending' => 'heroicon-o-clock',
                    'processed' => 'heroicon-o-arrow-path',
                    'done' => 'heroicon-o-check-circle',
                }),

            Actions\Action::make('Process')
                ->visible(fn (Laporan $record) => $record->status === 'pending')
                ->form([
                    Select::make('id_teknisi')
                        ->label('Pilih Teknisi')
                        ->options(Teknisi::pluck('nama', 'id'))
                        ->required(),
                ])
                ->action(function (Laporan $record, array $data) {
                    $record->update([
                        'status' => 'processed',
                        'id_teknisi' => $data['id_teknisi'], // Simpan teknisi yang dipilih
                    ]);
            
                    Notification::make()
                        ->title('Laporan Diproses')
                        ->body('Status laporan telah diubah menjadi "Processed" dan teknisi telah ditetapkan.')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Proses Laporan')
                ->modalDescription('Silakan pilih teknisi sebelum memproses laporan.')
                ->color('warning')
                ->icon('heroicon-o-arrow-path'),

            Actions\Action::make('Done')
                ->visible(fn (Laporan $record) => $record->status === 'processed')
                ->action(function (Laporan $record) {
                    // Update status laporan
                    $record->update(['status' => 'done']);

                    // Cari inventaris berdasarkan kode_barang
                    $inventaris = Inventaris::where('kode_barang', $record->kode_barang)->first();

                    // Jika ditemukan, update kondisi barang ke "Lengkap"
                    if ($inventaris) {
                        $inventaris->updateKondisiBarangToLengkap();
                    }

                    Notification::make()
                            ->title('Barang Selesai')
                            ->body('Status barang telah diubah menjadi "Done".')
                            ->success()
                            ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Selesai')
                ->modalDescription('Apakah Anda yakin barang ini sudah selesai diproses?')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
