<?php

namespace App\Filament\Resources\RiwayatPemeliharaanResource\Pages;

use App\Filament\Resources\RiwayatPemeliharaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiwayatPemeliharaan extends EditRecord
{
    protected static string $resource = RiwayatPemeliharaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
