<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalPemeliharaanResource\Pages;
use App\Models\JadwalPemeliharaan;
use App\Models\Barang; // Relasi dengan Barang
use App\Models\User;  // Relasi dengan User
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class JadwalPemeliharaanResource extends Resource
{
    protected static ?string $model = JadwalPemeliharaan::class;
    protected static ?string $modelLabel = 'Jadwal Pemeliharaan';
    protected static ?string $pluralModelLabel = 'Jadwal Pemeliharaan';
    protected static ?string $navigationLabel = 'Jadwal Pemeliharaan';
    protected static ?string $navigationGroup = 'Events';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_barang')
                    ->label('Barang')
                    ->options(Barang::all()->pluck('nama', 'id')) // Mengambil nama barang
                    ->required(),
                Forms\Components\Select::make('id_user')
                    ->label('User')
                    ->options(User::all()->pluck('name', 'id')) // Mengambil nama user
                    ->required(),
                Forms\Components\DatePicker::make('jadwal_pemeliharaan')
                    ->label('Jadwal Pemeliharaan')
                    ->required(),
                Forms\Components\Select::make('frekuensi_pemeliharaan')
                    ->label('Frekuensi Pemeliharaan')
                    ->options([
                        'bulanan' => 'Bulanan',
                        'mingguan' => 'Mingguan',
                        '3 bulan sekali' => '3 Bulan Sekali',
                        'setahun sekali' => 'Setahun Sekali',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('barang.nama') // Menampilkan nama barang
                    ->label('Barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name') // Menampilkan nama user
                    ->label('User')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jadwal_pemeliharaan') // Menampilkan jadwal pemeliharaan
                    ->label('Jadwal Pemeliharaan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('frekuensi_pemeliharaan') // Menampilkan frekuensi pemeliharaan
                    ->label('Frekuensi Pemeliharaan')
                    ->sortable(),
            ])
            ->filters([
                // Bisa menambahkan filter jika diperlukan
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListJadwalPemeliharaans::route('/'),
            'create' => Pages\CreateJadwalPemeliharaan::route('/create'),
            'edit' => Pages\EditJadwalPemeliharaan::route('/{record}/edit'),
            'view' => Pages\ViewJadwalPemeliharaan::route('/{record}'),
        ];
    }
}
