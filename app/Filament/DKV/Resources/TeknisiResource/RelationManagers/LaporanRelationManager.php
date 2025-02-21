<?php

namespace App\Filament\DKV\Resources\TeknisiResource\RelationManagers;

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
use Filament\Tables\Columns\ImageColumn;


use App\Models\Inventaris;
use App\Models\InventarisDKV;
use App\Models\InventarisSarpras;

class LaporanRelationManager extends RelationManager
{
    use CanBeEmbeddedInModals;

    protected static string $relationship = 'laporan';

    // public function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('deskripsi_laporan')
    //                 ->required()
    //                 ->maxLength(255),
    //         ]);
    // }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('deskripsi_laporan')
            ->columns([
                TextColumn::make('user.name')
                    ->sortable(),
                TextColumn::make('teknisi.nama')
                    ->sortable(),
                TextColumn::make('nama_barang')
                    ->searchable(),
                TextColumn::make('merk_barang')
                    ->searchable(),
                TextColumn::make('kode_barang')
                    ->searchable(),
                ImageColumn::make('bukti_laporan')
                    ->searchable(),
                TextColumn::make('lokasi_barang')
                    ->searchable(),
                TextColumn::make('status'),
                BadgeColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'done' => 'Done',
                        'processed' => 'Processed',
                        'pending' => 'Pending',
                        default => $state,
                    })
                    ->icons([
                        'heroicon-o-check-circle' => 'done',   // Ikon untuk Done
                        'heroicon-o-arrow-path' => 'processed', // Ikon untuk Processed
                        'heroicon-o-clock' => 'pending',       // Ikon untuk Pending
                    ])
                    ->colors([
                        'success' => 'done',
                        'warning' => 'processed',
                        'danger' => 'pending',
                    ]),
                TextColumn::make('tanggal_laporan')
                    ->date()
                    ->sortable(),
                TextColumn::make('hasil_laporan')
                    ->label('Hasil Laporan')
                    ->placeholder('Belum Diisi')
                    ->limit(50),    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ]);
    }
}
