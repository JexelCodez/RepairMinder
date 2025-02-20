<?php

namespace App\Filament\Resources\TeknisiResource\Pages;

use App\Filament\Resources\TeknisiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTeknisi extends ViewRecord
{
    protected static string $resource = TeknisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
