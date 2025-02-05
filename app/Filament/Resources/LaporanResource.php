<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Pages;
use App\Filament\Resources\LaporanResource\RelationManagers;
use App\Models\Laporan;
use App\Models\Inventaris;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;

// INFOLIST
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Section;

//Plugin
use Awcodes\Overlook\Contracts\CustomizeOverlookWidget;
use Awcodes\Overlook\Concerns\HandlesOverlookWidgetCustomization;

class LaporanResource extends Resource implements CustomizeOverlookWidget
{
    use HandlesOverlookWidgetCustomization;
    protected static ?string $model = Laporan::class;

    protected static ?string $modelLabel = 'Laporan';
    protected static ?string $pluralModelLabel = 'Laporan';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('merk_barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode_barang')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('bukti_laporan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi_barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                BadgeColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'done' => 'Done',
                        'processed' => 'Processed',
                        'pending' => 'Pending',
                        default => $state,
                    })
                    ->icons([
                        'heroicon-o-check-circle' => 'done',   // Ikon untuk Done
                        'heroicon-o-arrow-path' => 'processed', // Ikon untuk Processed
                        'heroicon-o-clock' => 'pending',       // Ikon untuk Pending
                    ])
                    ->colors([
                        'success' => 'done',
                        'warning' => 'processed',
                        'danger' => 'pending',
                    ]),
                Tables\Columns\TextColumn::make('tanggal_laporan')
                    ->date()
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('Process')
                    ->visible(fn (Laporan $record) => $record->status === 'pending')
                    ->action(function (Laporan $record) {
                        $record->update(['status' => 'processed']);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Proses Barang')
                    ->modalDescription('Apakah Anda yakin ingin memproses barang ini?')
                    ->color('warning')
                    ->icon('heroicon-o-arrow-path'),

                Action::make('Done')
                    ->visible(fn (Laporan $record) => $record->status === 'processed')
                    ->action(function (Laporan $record) {
                        // Update status laporan
                        $record->update(['status' => 'done']);

                        // Cari inventaris berdasarkan kode_barang
                        $inventaris = Inventaris::where('kode_barang', $record->kode_barang)->first();

                        // Jika ditemukan, update kondisi barang ke "Lengkap"
                        if ($inventaris) {
                            $inventaris->updateKondisiBarangToLengkap();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Selesai')
                    ->modalDescription('Apakah Anda yakin barang ini sudah selesai diproses?')
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            Tabs::make('Tabs')
                ->tabs([
                    Tabs\Tab::make('Informasi Laporan')
                        ->schema([
                            Fieldset::make('Informasi Laporan')
                                ->schema([
                                    TextEntry::make('user.name'),
                                    TextEntry::make('merk_barang'),
                                    TextEntry::make('nama_barang'),
                                    TextEntry::make('kode_barang'),           
                                    TextEntry::make('status')
                                        ->badge()
                                        ->formatStateUsing(function ($state) {
                                            return match ($state) {
                                                'done'        => 'Done',
                                                'processed'  => 'Processed',
                                                'pending'          => 'Pending',
                                                default          => $state,
                                            };
                                        })
                                        ->icons([
                                            'heroicon-o-check-circle' => 'done',   // Ikon untuk Done
                                            'heroicon-o-arrow-path' => 'processed', // Ikon untuk Processed
                                            'heroicon-o-clock' => 'pending',       // Ikon untuk Pending
                                        ])
                                        ->colors([
                                            'success' => 'done',
                                            'warning' => 'processed',
                                            'danger'  => 'pending',
                                        ]),
                                    TextEntry::make('lokasi_barang')
                                        ->label('Lokasi Barang'),
                                    TextEntry::make('deskripsi_laporan')
                                        ->columnSpan(2),               
                                ])->columnSpan(2)->columns(2),
                            Grid::make()
                                ->schema([
                                    Section::make('Tanggal Laporan')
                                        ->schema([
                                            TextEntry::make('tanggal_laporan')
                                            ->label('')
                                            ->dateTime(),         
                                        ])->columns(1),
                                ])->columnSpan(1),
                        ])->columns(3),
                    Tabs\Tab::make('Bukti Laporan')
                        ->schema([
                            Section::make('Bukti Laporan')
                                ->description('Foto bukti laporan')
                                ->schema([
                                    ImageEntry::make('bukti_laporan')
                                        ->size('md'),
                                ]),
                        ]),
                ]),    
        ])->columns(1);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporans::route('/'),
            'view' => Pages\ViewLaporan::route('/{record}'),
        ];
    }

    public static function getOverlookWidgetQuery(Builder $query): Builder
    {
        return $query->where('status','=','pending');
    }
    public static function getOverlookWidgetTitle(): string
    {
        return 'Laporan Pending';
    }
}