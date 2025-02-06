<?php

namespace App\Filament\Resources\PeriodePemeliharaanResource\Pages;

use App\Filament\Resources\PeriodePemeliharaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeriodePemeliharaan extends EditRecord
{
    protected static string $resource = PeriodePemeliharaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
