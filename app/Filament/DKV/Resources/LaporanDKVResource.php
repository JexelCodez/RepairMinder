<?php

namespace App\Filament\DKV\Resources;

use App\Filament\DKV\Resources\LaporanDKVResource\Pages;
use App\Filament\DKV\Resources\LaporanDKVResource\RelationManagers;
use App\Models\Laporan;
use App\Models\Inventaris;
use App\Models\InventarisDKV;
use App\Models\InventarisSarpras;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;

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

class LaporanDKVResource extends Resource
{
    protected static ?string $model = Laporan::class;

    protected static ?string $modelLabel = 'Laporan';
    protected static ?string $pluralModelLabel = 'Laporan';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Laporan';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }
    protected static ?string $navigationBadgeTooltip = 'Laporan Pending';

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
            ->filters([
                SelectFilter::make('jurusan')
                    ->label('Filter Berdasarkan Jurusan')
                    ->options([
                        'sija'   => 'SIJA',
                        'dkv'    => 'DKV',
                        'sarpras'=> 'SARPRAS',
                    ])
                    ->default('dkv')
                    ->query(function ($query, $data) {
                        if ($data['value'] === 'sija') {
                            return $query->whereIn('kode_barang', Inventaris::pluck('kode_barang'));
                        } elseif ($data['value'] === 'dkv') {
                            return $query->whereIn('kode_barang', InventarisDKV::pluck('kode_barang'));
                        } elseif ($data['value'] === 'sarpras') {
                            return $query->whereIn('kode_barang', InventarisSarpras::pluck('kode_barang'));
                        }
                        return $query;
                    })
                    ->placeholder('Pilih Jurusan'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            
                Action::make('Process')
                    ->visible(fn (Laporan $record) => $record->status === 'pending')
                    ->action(function (Laporan $record) {
                        $record->update(['status' => 'processed']);
            
                        Notification::make()
                            ->title('Barang Diproses')
                            ->body('Status barang telah diubah menjadi "Processed".')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Proses Barang')
                    ->modalDescription('Apakah Anda yakin ingin memproses barang ini?')
                    ->color('warning')
                    ->icon('heroicon-o-arrow-path'),
            
                Action::make('Done')
                    ->visible(fn (Laporan $record) => $record->status === 'processed')
                    ->action(function (Laporan $record) {
                        $record->update(['status' => 'done']);
            
                        $inventaris = InventarisDKV::where('kode_barang', $record->kode_barang)->first();
                        if ($inventaris) {
                            $inventaris->updateKondisiBarangToLengkap();
                        }
            
                        Notification::make()
                            ->title('Barang Selesai')
                            ->body('Status barang telah diubah menjadi "Done".')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Selesai')
                    ->modalDescription('Apakah Anda yakin barang ini sudah selesai diproses?')
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),

                Action::make('isi_hasil_laporan')
                    ->label('Isi Hasil')
                    ->icon('heroicon-o-document-text')
                    ->modalHeading('Isi Hasil Laporan')
                    ->form([
                        Forms\Components\Textarea::make('hasil_laporan')
                            ->label('Hasil Laporan')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'hasil_laporan' => $data['hasil_laporan'],
                        ]);
                    })
                    ->visible(fn($record) => $record->status === 'done' && !$record->hasil_laporan),
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

                    Tabs\Tab::make('Hasil Laporan')
                        ->schema([
                            Section::make('Hasil Laporan')
                                ->label('')
                                ->description('')
                                ->schema([
                                    TextEntry::make('hasil_laporan')
                                    ->label('')
                                    ->placeholder('Belum Diisi'),
                                ]),
                        ]),
                ]),    
        ])->columns(1);
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
            'index' => Pages\ListLaporanDKVS::route('/'),
            'view' => Pages\ViewLaporanDKV::route('/{record}'),
        ];
    }
}
