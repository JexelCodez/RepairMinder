<?php

namespace App\Filament\Resources\LokasiResource\Pages;

use App\Filament\Resources\LokasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;

class ListLokasis extends ListRecords
{
    protected static string $resource = LokasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Lokasi')
                ->modalHeading('Tambah Lokasi Baru')
                ->modalWidth('lg') // Opsional: Atur lebar modal
                ->form([
                    Section::make('Nama Lokasi')
                        ->description('Lokasi barang')
                        ->schema([
                            TextInput::make('nama_lokasi')
                            ->required()
                            ->unique()
                            ->maxLength(255),
                        ]),
                ])
                ->successNotification(
                    Notification::make()
                         ->success()
                         ->title('Berhasil')
                         ->body('Lokasi Baru berhasil ditambahkan.'),
                 )
        ];
    }
}
