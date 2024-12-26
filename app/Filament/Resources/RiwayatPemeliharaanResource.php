<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiwayatPemeliharaanResource\Pages;
use App\Models\RiwayatPemeliharaan;
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

class RiwayatPemeliharaanResource extends Resource
{
    protected static ?string $model = RiwayatPemeliharaan::class;
    protected static ?string $modelLabel = 'Riwayat Pemeliharaan';
    protected static ?string $pluralModelLabel = 'Riwayat Pemeliharaan';
    protected static ?string $navigationLabel = 'Riwayat Pemeliharaan';
    protected static ?string $navigationGroup = 'Events';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pemeliharaan')
                    ->schema([
                        Select::make('id_barang')
                            ->label('Barang')
                            ->relationship('barang', 'nama') // Sesuaikan nama kolom barang
                            ->required(),

                        Select::make('id_user')
                            ->label('User')
                            ->relationship('user', 'name') // Sesuaikan nama kolom user
                            ->required(),

                        DatePicker::make('tanggal_pemeliharaan')
                            ->label('Tanggal Pemeliharaan')
                            ->required(),

                        Textarea::make('kegiatan_pemeliharaan')
                            ->label('Kegiatan Pemeliharaan')
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

                Tables\Columns\TextColumn::make('tanggal_pemeliharaan')
                    ->label('Tanggal Pemeliharaan')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kegiatan_pemeliharaan')
                    ->label('Kegiatan Pemeliharaan')
                    ->limit(50),

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
            // Tambahkan relasi jika diperlukan
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRiwayatPemeliharaans::route('/'),
            'create' => Pages\CreateRiwayatPemeliharaan::route('/create'),
            'edit' => Pages\EditRiwayatPemeliharaan::route('/{record}/edit'),
            'view' => Pages\ViewRiwayatPemeliharaan::route('/{record}'),
        ];
    }
}
