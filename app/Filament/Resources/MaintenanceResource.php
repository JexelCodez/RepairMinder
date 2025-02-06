<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceResource\Pages;
use App\Models\Maintenance;
use App\Models\PeriodePemeliharaan;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class MaintenanceResource extends Resource
{
    protected static ?string $model = Maintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Maintenance';
    protected static ?string $navigationLabel = 'Maintenance Barang';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_periode_pemeliharaan')
                    ->label('Periode Pemeliharaan')
                    ->options(PeriodePemeliharaan::all()->pluck('periode', 'id'))
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
                Tables\Columns\TextColumn::make('periode_pemeliharaan.periode')
                    ->label('Periode Pemeliharaan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Assigned User')
                    ->searchable(),

                Tables\Columns\TextColumn::make('deskripsi_tugas')
                    ->label('Task Description')
                    ->limit(50),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status'),

                Tables\Columns\TextColumn::make('tanggal_pelaksanaan')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaintenances::route('/'),
            'create' => Pages\CreateMaintenance::route('/create'),
            'edit' => Pages\EditMaintenance::route('/{record}/edit'),
        ];
    }
}
