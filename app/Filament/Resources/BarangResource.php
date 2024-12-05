<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Filament\Resources\BarangResource\RelationManagers;
use App\Models\Barang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Str;
use Filament\Forms\Components\Hidden;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $modelLabel = 'Barang';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Barang')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required(),
                        Forms\Components\TextInput::make('stok_barang')
                            ->numeric()
                            ->default(null),
                        Forms\Components\Select::make('id_kategori')
                            ->required()
                            ->relationship('kategori', 'nama_kategori'),
                        Forms\Components\Select::make('id_lokasi')
                            ->required()
                            ->relationship('lokasi', 'nama_lokasi'),
                        Hidden::make('token_qr') // Ubah TextInput menjadi Hidden
                            ->default(function () {
                                return Str::random(10); // Atur panjang string sesuai kebutuhan
                            }),
                        Repeater::make('mac_address')
                            ->schema([
                                Forms\Components\TextInput::make('mac_addresses')
                                    ->nullable()
                                    ->hint('Masukkan jika Barang memiliki MAC address'),
                            ])->columnSpanFull()->addActionLabel('Tambah Mac Address Lainnya')             
                    ])->columnSpan(2)->columns(2),
                Section::make('Story Status')
                    ->schema([
                        Forms\Components\Radio::make('status')
                        ->options([
                            'Bagus' => 'Bagus',
                            'Dalam Perbaikan' => 'Dalam Perbaikan',
                            'Rusak' => 'Rusak',
                        ])
                        ->inline()
                        ->default('Bagus')
                        ->required(),
                    ])->columnSpan(1),    
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stok_barang')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('token_qr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('lokasi.nama_lokasi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'view' => Pages\ViewBarang::route('/{record}'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
