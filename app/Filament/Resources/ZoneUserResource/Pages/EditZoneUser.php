<?php

namespace App\Filament\Resources\ZoneUserResource\Pages;

use App\Filament\Resources\ZoneUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditZoneUser extends EditRecord
{
    protected static string $resource = ZoneUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
