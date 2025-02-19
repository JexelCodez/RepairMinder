<?php

namespace App\Filament\Sarpras\Resources\TeknisiResource\Pages;

use App\Filament\Sarpras\Resources\TeknisiResource;
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
