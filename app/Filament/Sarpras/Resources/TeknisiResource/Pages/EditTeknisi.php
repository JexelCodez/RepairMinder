<?php

namespace App\Filament\Sarpras\Resources\TeknisiResource\Pages;

use App\Filament\Sarpras\Resources\TeknisiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeknisi extends EditRecord
{
    protected static string $resource = TeknisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
