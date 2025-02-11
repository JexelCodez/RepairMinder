<?php

namespace App\Filament\DKV\Resources\PeriodePemeliharaanDKVResource\Pages;

use App\Filament\DKV\Resources\PeriodePemeliharaanDKVResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeriodePemeliharaanDKV extends EditRecord
{
    protected static string $resource = PeriodePemeliharaanDKVResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
