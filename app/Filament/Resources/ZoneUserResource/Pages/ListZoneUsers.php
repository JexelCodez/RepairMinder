<?php

namespace App\Filament\Resources\ZoneUserResource\Pages;

use App\Filament\Resources\ZoneUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListZoneUsers extends ListRecords
{
    protected static string $resource = ZoneUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
