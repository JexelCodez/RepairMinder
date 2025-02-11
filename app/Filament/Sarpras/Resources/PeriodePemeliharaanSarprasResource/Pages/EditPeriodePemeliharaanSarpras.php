<?php

namespace App\Filament\Sarpras\Resources\PeriodePemeliharaanSarprasResource\Pages;

use App\Filament\Sarpras\Resources\PeriodePemeliharaanSarprasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeriodePemeliharaanSarpras extends EditRecord
{
    protected static string $resource = PeriodePemeliharaanSarprasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
