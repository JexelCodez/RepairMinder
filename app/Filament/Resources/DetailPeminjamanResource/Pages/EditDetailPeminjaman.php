<?php

namespace App\Filament\Resources\DetailPeminjamanResource\Pages;

use App\Filament\Resources\DetailPeminjamanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDetailPeminjaman extends EditRecord
{
    protected static string $resource = DetailPeminjamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
