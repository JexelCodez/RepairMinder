<?php

namespace App\Filament\Resources\JadwalPemeliharaanResource\Pages;

use App\Filament\Resources\JadwalPemeliharaanResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewJadwalPemeliharaan extends ViewRecord
{
    protected static string $resource = JadwalPemeliharaanResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
