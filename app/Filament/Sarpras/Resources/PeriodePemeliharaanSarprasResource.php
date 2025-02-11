<?php

namespace App\Filament\Sarpras\Resources;

use App\Filament\Sarpras\Resources\PeriodePemeliharaanSarprasResource\Pages;
use App\Filament\Sarpras\Resources\PeriodePemeliharaanSarprasResource\RelationManagers;
use App\Models\PeriodePemeliharaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;

class PeriodePemeliharaanSarprasResource extends Resource
{
    protected static ?string $model = PeriodePemeliharaan::class;
    protected static ?string $modelLabel = 'Periode Pemeliharaan';
    protected static ?string $pluralModelLabel = 'Periode Pemeliharaan';
    protected static ?string $navigationLabel = 'Periode Pemeliharaan';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Penjadwalan & Perbaikan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kode_barang')
                    ->label('Barang')
                    ->options(InventarisSarpras::all()->mapWithKeys(function ($item) {
                        return [$item->kode_barang => "{$item->kode_barang} ({$item->nama_barang})"];
                    }))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('periode')
                    ->label('Periode (dalam hari)')
                    ->numeric()
                    ->minValue(1)
                    ->placeholder('Masukkan jumlah hari'),
                

                Forms\Components\TextInput::make('deskripsi')
                    ->label('Deskripsi')
                    ->nullable()
                    ->placeholder('Provide maintenance description'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('inventaris.nama_barang')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('inventaris.merek')
                    ->label('Merk Barang')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('kode_barang')
                    ->label('Kode Barang')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('periode')
                    ->label('Periode Pemeliharaan')
                    ->formatStateUsing(fn($state) => $state . ' Hari'),
                    ->sortable(),

                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->deskripsi),

                TextColumn::make('tanggal_maintenance_selanjutnya')
                    ->label('Maintenance Selanjutnya')
                    ->date()
                    ->sortable()
                    ->placeholder('Belum tersedia'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('periode')
                    ->label('Filter Periode')
                    ->options([
                        '3 months' => '3 months',
                        '6 months' => '6 months',
                        '12 months' => '12 months',
                    ])
                    ->placeholder('Select Period'),
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading('Edit Periode Pemeliharaan')
                    ->modalWidth('lg')
                    ->form(fn ($record) => [
                        Forms\Components\Select::make('id_barang')
                            ->label('Barang')
                            ->options(function () {
                                // Fetch fresh list of barang
                                return []; // Return an empty array for fresh data
                            })
                            ->default($record->id_barang)
                            ->required(),

                        Forms\Components\TextInput::make('periode')
                            ->label('Periode Pemeliharaan')
                            ->default($record->periode)
                            ->required(),

                        Forms\Components\TextInput::make('deskripsi')
                            ->label('Deskripsi')
                            ->nullable()
                            ->default($record->deskripsi),
                    ])
                    ->action(function ($record, array $data) {
                        // Update the PeriodePemeliharaan
                        $record->update([
                            'id_barang' => $data['id_barang'],
                            'periode' => $data['periode'],
                            'deskripsi' => $data['deskripsi'],
                        ]);
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeriodePemeliharaanSarpras::route('/'),
            'create' => Pages\CreatePeriodePemeliharaanSarpras::route('/create'),
            'edit' => Pages\EditPeriodePemeliharaanSarpras::route('/{record}/edit'),
        ];
    }
}
