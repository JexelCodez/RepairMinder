<?php

namespace App\Filament\Resources\RiwayatPemeliharaanResource\Pages;

use App\Filament\Resources\RiwayatPemeliharaanResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewRiwayatPemeliharaan extends ViewRecord
{
    protected static string $resource = RiwayatPemeliharaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
