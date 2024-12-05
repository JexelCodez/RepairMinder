<?php

namespace App\Filament\Resources\KategoriResource\Pages;

use App\Filament\Resources\KategoriResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Section;

class ListKategoris extends ListRecords
{
    protected static string $resource = KategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kategori')
                ->modalHeading('Tambah Kategori Baru')
                ->modalWidth('lg') // Opsional: Atur lebar modal
                ->form([
                    Section::make('Nama Kategori')
                        ->description('Kategori barang')
                        ->schema([
                            TextInput::make('nama_kategori')
                            ->required()
                            ->unique()
                            ->maxLength(255),
                        ]),
                ])
                ->successNotification(
                    Notification::make()
                         ->success()
                         ->title('Berhasil')
                         ->body('Kategori Baru berhasil ditambahkan.'),
                 )
        ];
    }
}