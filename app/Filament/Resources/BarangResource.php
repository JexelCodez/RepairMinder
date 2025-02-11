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
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Str;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Actions\BulkAction;

// INFOLIST
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\Grid;
use Filament\Tables\Columns\ImageColumn;
use Filament\Infolists\Components\ImageEntry;
 

use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $modelLabel = 'Barang';
    protected static ?string $pluralModelLabel = 'Barang';
    protected static ?string $navigationLabel = 'Barang';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Inventaris';
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('merek')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kode_barang')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stok_barang')
                    ->sortable(),
                ImageColumn::make('qrcode_image'),
                Tables\Columns\TextColumn::make('nama_jenis_barang'),
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
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            Fieldset::make('Informasi Barang')
                ->schema([
                    TextEntry::make('nama_barang'),
                    TextEntry::make('merek'),
                    TextEntry::make('stok_barang'),
                    TextEntry::make('kode_barang'),           
                    TextEntry::make('nama_jenis_barang'),           
                ])->columnSpan(2)->columns(2),
            Grid::make()
                ->schema([
                    Fieldset::make('QR Code')
                        ->schema([
                            ImageEntry::make('qrcode_image')
                                ->size('xl'),
                        ])->columns(1),
                    Fieldset::make('Status')
                        ->schema([
                            TextEntry::make('created_at')
                            ->dateTime(),
                            TextEntry::make('updated_at')
                            ->dateTime(),           
                        ])->columns(1),
                ])->columnSpan(1),
        ])->columns(3);
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
            // 'create' => Pages\CreateBarang::route('/create'),
            // 'view' => Pages\ViewBarang::route('/{record}'),
            // 'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
