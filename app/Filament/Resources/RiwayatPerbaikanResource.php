<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiwayatPerbaikanResource\Pages;
use App\Models\RiwayatPerbaikan;
use App\Models\Barang;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;

class RiwayatPerbaikanResource extends Resource
{
    protected static ?string $model = RiwayatPerbaikan::class;
    protected static ?string $modelLabel = 'Riwayat Perbaikan';
    protected static ?string $pluralModelLabel = 'Riwayat Perbaikan';
    protected static ?string $navigationLabel = 'Riwayat Perbaikan';
    protected static ?string $navigationGroup = 'Events';
    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Perbaikan')
                    ->schema([
                        Select::make('id_barang')
                            ->label('Barang')
                            ->relationship('barang', 'nama') // Sesuaikan nama kolom barang
                            ->required(),

                        Select::make('id_user')
                            ->label('User')
                            ->relationship('user', 'name') // Sesuaikan nama kolom user
                            ->required(),

                        DatePicker::make('tanggal_perbaikan')
                            ->label('Tanggal Perbaikan')
                            ->required(),

                        Textarea::make('deskripsi_kerusakan')
                            ->label('Deskripsi Kerusakan')
                            ->nullable(),

                        Textarea::make('solusi_perbaikan')
                            ->label('Solusi Perbaikan')
                            ->nullable(),

                        Forms\Components\TextInput::make('biaya_perbaikan')
                            ->label('Biaya Perbaikan')
                            ->numeric()
                            ->nullable(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'selesai' => 'Selesai',
                                'dalam proses' => 'Dalam Proses',
                                'gagal' => 'Gagal',
                            ])
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('barang.nama')
                    ->label('Barang')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_perbaikan')
                    ->label('Tanggal Perbaikan')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi_kerusakan')
                    ->label('Deskripsi Kerusakan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('solusi_perbaikan')
                    ->label('Solusi Perbaikan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('biaya_perbaikan')
                    ->label('Biaya Perbaikan')
                    ->numeric(),

                // Fixed the badge with correct method
                Tables\Columns\TextColumn::make('status')
                    ->badge(
                        fn ($state) => match ($state) {
                            'selesai' => 'success',
                            'dalam proses' => 'warning',
                            'gagal' => 'danger',
                            default => 'secondary',
                        }
                    )
                    ->label('Status')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add relations if necessary
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRiwayatPerbaikans::route('/'),
            'create' => Pages\CreateRiwayatPerbaikan::route('/create'),
            'edit' => Pages\EditRiwayatPerbaikan::route('/{record}/edit'),
            'view' => Pages\ViewRiwayatPerbaikan::route('/{record}'),
        ];
    }
}
