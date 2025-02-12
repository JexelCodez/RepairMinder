<?php

namespace App\Filament\Resources\ZoneUserResource\Pages;

use App\Filament\Resources\ZoneUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewZoneUser extends ViewRecord
{
    protected static string $resource = ZoneUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
