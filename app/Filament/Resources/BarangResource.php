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

use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $modelLabel = 'Barang';
    protected static ?string $pluralModelLabel = 'Barang';
    protected static ?string $navigationLabel = 'Barang';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
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
                    ->searchable()
                    ->view('qr-barang.qr-code'),
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
                // Action baru untuk mendownload QR Code
                Action::make('print_qr')
                    ->label('Print QR Code')
                    ->icon('heroicon-o-printer') // Ikon opsional
                    ->url(fn ($record) => route('barang.download-qr', $record->id))
                    ->color('success'), // Warna tombol opsional
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // Bulk Action untuk mendownload QR Codes secara massal dalam satu PDF
                    BulkAction::make('download_bulk_qr')
                        ->label('Print QR Codes')
                        ->icon('heroicon-o-printer')
                        ->action(function (EloquentCollection $records) {
                            // Mengambil semua ID dari records yang dipilih
                            $ids = $records->pluck('id')->implode(',');

                            // Redirect ke route download bulk QR Codes dengan parameter IDs
                            return redirect()->route('barang.download-bulk-qrs', ['ids' => $ids]);
                        })
                        ->requiresConfirmation()
                        ->color('primary'),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            Fieldset::make('Informasi Barang')
                ->schema([
                    TextEntry::make('nama'),
                    TextEntry::make('stok_barang'),
                    TextEntry::make('kategori.nama_kategori'),
                    TextEntry::make('lokasi.nama_lokasi'),
                    RepeatableEntry::make('mac_address')
                        ->schema([
                            TextEntry::make('mac_addresses'),
                        ])->columnSpanFull()           
                ])->columnSpan(2)->columns(2),
            Grid::make()
                ->schema([
                    Fieldset::make('Status Barang')
                        ->schema([
                            TextEntry::make('status')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'Dalam Perbaikan' => 'warning',
                                    'Bagus' => 'success',
                                    'Rusak' => 'danger',
                                }),   
                        ]),
                    Fieldset::make('QR Code Barang')
                        ->schema([
                            ViewEntry::make('token_qr')
                                ->view('qr-barang.infolist_qr-code'),  
                        ]),
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
            'create' => Pages\CreateBarang::route('/create'),
            // 'view' => Pages\ViewBarang::route('/{record}'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
