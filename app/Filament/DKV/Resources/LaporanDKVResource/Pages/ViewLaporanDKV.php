<?php

namespace App\Filament\DKV\Resources\LaporanDKVResource\Pages;

use App\Filament\DKV\Resources\LaporanDKVResource;
use App\Models\InventarisDKV;
use App\Models\Laporan;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Notifications\Notification;

class ViewLaporanDKV extends ViewRecord
{
    protected static string $resource = LaporanDKVResource::class;

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
                ->action(function (Laporan $record) {
                    $record->update(['status' => 'processed']);

                    Notification::make()
                            ->title('Barang Diproses')
                            ->body('Status barang telah diubah menjadi "Processed".')
                            ->success()
                            ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Proses Barang')
                ->modalDescription('Apakah Anda yakin ingin memproses barang ini?')
                ->color('warning')
                ->icon('heroicon-o-arrow-path'),

            Actions\Action::make('Done')
                ->visible(fn (Laporan $record) => $record->status === 'processed')
                ->action(function (Laporan $record) {
                    // Update status laporan
                    $record->update(['status' => 'done']);

                    // Cari inventaris berdasarkan kode_barang
                    $inventaris = InventarisDKV::where('kode_barang', $record->kode_barang)->first();

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
