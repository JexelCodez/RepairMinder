<?php

namespace App\Filament\Resources\RiwayatPerbaikanResource\Pages;

use App\Filament\Resources\RiwayatPerbaikanResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewRiwayatPerbaikan extends ViewRecord
{
    protected static string $resource = RiwayatPerbaikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
