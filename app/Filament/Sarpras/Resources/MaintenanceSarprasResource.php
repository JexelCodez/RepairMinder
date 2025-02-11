<?php

namespace App\Filament\Sarpras\Resources;

use App\Filament\Sarpras\Resources\MaintenanceSarprasResource\Pages;
use App\Filament\Sarpras\Resources\MaintenanceSarprasResource\RelationManagers;
use App\Models\Maintenance;
use App\Models\InventarisSarpras;
use App\Models\PeriodePemeliharaan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class MaintenanceSarprasResource extends Resource
{
    protected static ?string $model = Maintenance::class;
    protected static ?string $modelLabel = 'Maintenance';
    protected static ?string $pluralModelLabel = 'Maintenance';
    protected static ?string $navigationLabel = 'Maintenance';
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Penjadwalan & Perbaikan';

    public static function form(Forms\Form $form): Forms\Form
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

                Forms\Components\Select::make('id_user')
                    ->label('Assigned User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->required(),

                Forms\Components\TextInput::make('deskripsi_tugas')
                    ->label('Task Description')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'sedang diproses' => 'Sedang Diproses',
                        'dalam perbaikan' => 'Dalam Perbaikan',
                        'selesai' => 'Selesai',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_pelaksanaan')
                    ->label('Tanggal Pelaksanaan')
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
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

                TextColumn::make('user.name')
                    ->label('User Pelaksana')
                    ->searchable(),

                TextColumn::make('deskripsi_tugas')
                    ->label('Deskripsi Tugas')
                    ->limit(50),

                TextColumn::make('status')
                    ->label('Status'),

                TextColumn::make('tanggal_pelaksanaan')
                    ->label('Tanggal Pelaksanaan')
                    ->date(),
            ])
            ->filters([
                // Define any filters if necessary
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListMaintenanceSarpras::route('/'),
            'create' => Pages\CreateMaintenanceSarpras::route('/create'),
            'edit' => Pages\EditMaintenanceSarpras::route('/{record}/edit'),
        ];
    }
}
