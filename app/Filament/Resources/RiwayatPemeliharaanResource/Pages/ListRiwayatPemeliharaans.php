<?php

namespace App\Filament\Resources\RiwayatPemeliharaanResource\Pages;

use App\Filament\Resources\RiwayatPemeliharaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiwayatPemeliharaans extends ListRecords
{
    protected static string $resource = RiwayatPemeliharaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
