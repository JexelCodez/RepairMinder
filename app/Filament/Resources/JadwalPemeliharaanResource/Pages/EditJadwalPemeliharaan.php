<?php

namespace App\Filament\Resources\JadwalPemeliharaanResource\Pages;

use App\Filament\Resources\JadwalPemeliharaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalPemeliharaan extends EditRecord
{
    protected static string $resource = JadwalPemeliharaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
