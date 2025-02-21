<?php

namespace App\Filament\Sarpras\Resources\TeknisiResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Guava\FilamentModalRelationManagers\Concerns\CanBeEmbeddedInModals;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;


use App\Models\Inventaris;
use App\Models\InventarisDKV;
use App\Models\InventarisSarpras;

class MaintenanceRelationManager extends RelationManager
{
    use CanBeEmbeddedInModals;

    protected static string $relationship = 'maintenance';

    // public function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('deskripsi_tugas')
    //                 ->required()
    //                 ->maxLength(255),
    //         ]);
    // }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('deskripsi_tugas')
            ->columns([
                TextColumn::make('periode.kode_barang')
                    ->label('Kode Barang')
                    ->sortable()
                    ->searchable(),
            
                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->sortable()
                    ->getStateUsing(fn($record) =>
                        $record->periode?->inventaris->nama_barang ??
                        $record->periode?->inventarisDKV->nama_barang ??
                        $record->periode?->inventarisSarpras->nama_barang ?? 'N/A'
                    ),
            
                TextColumn::make('merek')
                    ->label('Merk Barang')
                    ->sortable()
                    ->getStateUsing(fn($record) =>
                        $record->periode?->inventaris->merek ??
                        $record->periode?->inventarisDKV->merek ??
                        $record->periode?->inventarisSarpras->merek ?? 'N/A'
                    )
                    ->toggleable(isToggledHiddenByDefault: true),
            
                TextColumn::make('user.name')
                    ->label('User Pelaksana')
                    ->searchable(),
            
                TextColumn::make('teknisi.nama')
                    ->label('Teknisi Pelaksana')
                    ->searchable(),
                    
                TextColumn::make('deskripsi_tugas')
                    ->label('Deskripsi Tugas')
                    ->limit(50),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'sedang diproses' => 'Sedang Diproses',
                        'dalam perbaikan' => 'Dalam Perbaikan',
                        'selesai' => 'Selesai',
                        default => $state,
                    })
                    ->icons([
                        'heroicon-o-play' => 'sedang diproses',
                        'heroicon-o-wrench-screwdriver' => 'dalam perbaikan',
                        'heroicon-o-check-circle' => 'selesai',
                    ])
                    ->colors([
                        'success' => 'selesai',
                        'warning' => 'dalam perbaikan',
                        'danger' => 'sedang diproses',
                    ]),    
            
                TextColumn::make('tanggal_pelaksanaan')
                    ->label('Tanggal Pelaksanaan')
                    ->date(),

                TextColumn::make('hasil_maintenance')
                    ->label('Hasil Maintenance')
                    ->placeholder('Belum Diisi')
                    ->limit(50),    
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ]);
    }
}
