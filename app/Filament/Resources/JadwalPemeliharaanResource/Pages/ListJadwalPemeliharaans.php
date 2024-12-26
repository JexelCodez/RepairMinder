<?php

namespace App\Filament\Resources\JadwalPemeliharaanResource\Pages;

use App\Filament\Resources\JadwalPemeliharaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwalPemeliharaans extends ListRecords
{
    protected static string $resource = JadwalPemeliharaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
