<?php

namespace App\Filament\Sarpras\Resources\MaintenanceSarprasResource\Pages;

use App\Filament\Sarpras\Resources\MaintenanceSarprasResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Textarea;

class ViewMaintenance extends ViewRecord
{
    protected static string $resource = MaintenanceSarprasResource::class;

    protected function getHeaderActions(): array
{
    return [
        // Badge Status
        Actions\Action::make('Status')
            ->color(fn ($record) => match ($record->status) {
                'sedang diproses' => 'gray',
                'dalam perbaikan' => 'warning',
                'selesai' => 'success',
            })
            ->label(fn ($record) => ucfirst($record->status))
            ->disabled()
            ->icon(fn ($record) => match ($record->status) {
                'sedang diproses' => 'heroicon-o-clock',
                'dalam perbaikan' => 'heroicon-o-wrench',
                'selesai' => 'heroicon-o-check-circle',
            }),

        // Action: Mulai Perbaikan
        Actions\Action::make('Mulai Perbaikan')
            ->visible(fn ($record) => $record->status === 'sedang diproses')
            ->action(function ($record) {
                $record->update(['status' => 'dalam perbaikan']);

                Notification::make()
                    ->title('Perbaikan Dimulai')
                    ->body('Status maintenance telah diubah menjadi "Dalam Perbaikan".')
                    ->success()
                    ->send();
            })
            ->requiresConfirmation()
            ->modalHeading('Mulai Perbaikan')
            ->modalDescription('Apakah Anda yakin ingin memulai perbaikan?')
            ->color('warning')
            ->icon('heroicon-o-wrench'),

        // Action: Selesaikan Perbaikan
        Actions\Action::make('Selesai')
            ->visible(fn ($record) => $record->status === 'dalam perbaikan')
            ->action(function ($record) {
                $record->update(['status' => 'selesai']);

                Notification::make()
                    ->title('Perbaikan Selesai')
                    ->body('Status maintenance telah diubah menjadi "Selesai".')
                    ->success()
                    ->send();
            })
            ->requiresConfirmation()
            ->modalHeading('Selesaikan Perbaikan')
            ->modalDescription('Apakah Anda yakin perbaikan sudah selesai?')
            ->color('success')
            ->icon('heroicon-o-check-circle'),

        Actions\Action::make('Hasil maintenance')
            ->visible(fn($record) => $record->status === 'selesai' && !$record->hasil_maintenance)
            ->form([
                Textarea::make('hasil_maintenance')
                    ->label('Hasil maintenance')
                    ->required()
                    ->maxLength(255),
            ])
            ->action(function ($record, array $data) {
                $record->update([
                    'hasil_maintenance' => $data['hasil_maintenance'],
                ]);
                Notification::make()
                    ->title('Barang Selesai')
                    ->body('Hasil maintenance Berhasil diBuat')
                    ->success()
                    ->send();
            })

            ->requiresConfirmation()
            ->modalHeading('Selesai')
            ->modalDescription('Apakah Anda yakin barang ini sudah selesai diproses?')
            ->color('info')
            ->icon('heroicon-o-check-circle'),
    ];
}
}
