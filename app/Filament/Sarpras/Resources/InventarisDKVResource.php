<?php

namespace App\Filament\Sarpras\Resources;

use App\Filament\Sarpras\Resources\InventarisDKVResource\Pages;
use App\Filament\Sarpras\Resources\InventarisDKVResource\RelationManagers;
use App\Models\InventarisDKV;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Http;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Facades\Log;

// INFOLIST
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;

class InventarisDKVResource extends Resource
{
    protected static ?string $model = InventarisDKV::class;
    protected static ?string $modelLabel = 'Barang DKV';
    protected static ?string $pluralModelLabel = 'Barang DKV';
    protected static ?string $navigationLabel = 'Barang DKV';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Inventaris';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kondisi_barang')
                    ->label('Kondisi Barang')
                    ->options([
                        'lengkap' => 'Lengkap',
                        'tidak_lengkap' => 'Tidak Lengkap',
                        'rusak' => 'Rusak',
                    ])
                    ->default(fn ($record) => $record->kondisi_barang)
                    ->required()
                    ->reactive(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_inventaris')
                    ->label('ID Inventaris')
                    ->sortable(),

                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('merek')
                    ->label('Merek')
                    ->sortable()
                    ->searchable(),

                // ImageColumn::make('qrcode_image')
                //     ->label('QR Code'),

                TextColumn::make('nama_ruangan')
                    ->label('Nama Ruangan')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('jumlah_barang')
                    ->label('Jumlah Barang')
                    ->sortable(),

                BadgeColumn::make('kondisi_barang')
                    ->label('Kondisi')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'lengkap'        => 'Lengkap',
                            'tidak_lengkap'  => 'Tidak Lengkap',
                            'rusak'          => 'Rusak',
                            default          => $state,
                        };
                    })
                    ->colors([
                        'success' => 'lengkap',
                        'warning' => 'tidak_lengkap',
                        'danger'  => 'rusak',
                    ]),

                TextColumn::make('ket_barang')
                    ->label('Keterangan')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->ket_barang),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('nama_ruangan')
                    ->label('Filter Ruangan')
                    ->options(fn () => InventarisDKV::pluck('nama_ruangan', 'nama_ruangan')->toArray())
                    ->searchable(),

                SelectFilter::make('kondisi_barang')
                    ->label('Filter Kondisi Barang')
                    ->options([
                        'lengkap' => 'Lengkap',
                        'tidak_lengkap' => 'Tidak Lengkap',
                        'rusak' => 'Rusak',
                    ])
                    ->placeholder('Pilih Kondisi'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Menggunakan modal untuk Edit
                EditAction::make()
                    ->modalHeading('Edit Kondisi Barang')
                    ->modalWidth('lg')
                    ->form(fn ($record) => [
                        Forms\Components\Select::make('kondisi_barang')
                            ->label('Kondisi Barang')
                            ->options([
                                'lengkap' => 'Lengkap',
                                'tidak_lengkap' => 'Tidak Lengkap',
                                'rusak' => 'Rusak',
                            ])
                            ->default($record->kondisi_barang)
                            ->required()
                            ->reactive(),
                    ])
                    ->action(function ($record, array $data) {
                        // Update kondisi barang di database
                        $record->update(['kondisi_barang' => $data['kondisi_barang']]);
                    
                        // Panggil method updateKondisiBarang untuk update API
                        $success = $record->updateKondisiBarang($data['kondisi_barang']);
                    
                        if ($success) {
                            session()->flash('success', 'Kondisi barang berhasil diperbarui!');
                        } else {
                            session()->flash('error', 'Gagal memperbarui Kondisi barang di API.');
                        }
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
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
                    TextEntry::make('nama_ruangan')
                        ->label('Lokasi'),
                    TextEntry::make('kondisi_barang')
                        ->badge()
                        ->formatStateUsing(function ($state) {
                            return match ($state) {
                                'lengkap'        => 'Lengkap',
                                'tidak_lengkap'  => 'Tidak Lengkap',
                                'rusak'          => 'Rusak',
                                default          => $state,
                            };
                        })
                        ->colors([
                            'success' => 'lengkap',
                            'warning' => 'tidak_lengkap',
                            'danger'  => 'rusak',
                        ]),
                        TextEntry::make('ket_barang')
                            ->label('Keterangan'),           
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
            'index' => Pages\ListInventarisDKVS::route('/'),
            'create' => Pages\CreateInventarisDKV::route('/create'),
            'view' => Pages\ViewInventarisDKV::route('/{record}'),
            'edit' => Pages\EditInventarisDKV::route('/{record}/edit'),
        ];
    }
}
