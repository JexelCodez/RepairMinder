<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeriodePemeliharaanResource\Pages;
use App\Models\PeriodePemeliharaan;
use App\Models\Inventaris;
use App\Models\InventarisDKV;
use App\Models\InventarisSarpras;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Filters\Filter;

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

class PeriodePemeliharaanResource extends Resource
{
    protected static ?string $model = PeriodePemeliharaan::class;
    protected static ?string $modelLabel = 'Periode Pemeliharaan';
    protected static ?string $pluralModelLabel = 'Periode Pemeliharaan';
    protected static ?string $navigationLabel = 'Periode Pemeliharaan';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Penjadwalan & Perbaikan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kode_barang')
                    ->label('Barang')
                    ->options(Inventaris::all()->mapWithKeys(function ($item) {
                        return [$item->kode_barang => "{$item->kode_barang} ({$item->nama_barang})"];
                    }))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('periode')
                    ->label('Periode (dalam hari)')
                    ->numeric()
                    ->minValue(1)
                    ->suffix('Hari')
                    ->suffixIcon('heroicon-o-calendar-days')
                    ->placeholder('Masukkan jumlah hari'),
                

                Forms\Components\TextInput::make('deskripsi')
                    ->label('Deskripsi')
                    ->nullable()
                    ->placeholder('Provide maintenance description'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->sortable()
                    // ->searchable()
                    ->getStateUsing(fn($record) =>
                        $record->inventaris->nama_barang ?? 
                        $record->inventarisDKV->nama_barang ?? 
                        $record->inventarisSarpras->nama_barang ?? 'N/A'
                    ),
                TextColumn::make('merek')
                    ->label('Merk Barang')
                    ->sortable()
                    // ->searchable()
                    ->getStateUsing(fn($record) =>
                        $record->inventaris->merek ?? 
                        $record->inventarisDKV->merek ?? 
                        $record->inventarisSarpras->merek ?? 'N/A'
                    ),
                TextColumn::make('kode_barang')
                    ->label('Kode Barang')
                    ->sortable()
                    ->searchable(),
    
                TextColumn::make('periode')
                    ->label('Periode Pemeliharaan')
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state . ' Hari'),
    
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->deskripsi),
    
                TextColumn::make('tanggal_maintenance_selanjutnya')
                    ->label('Maintenance Selanjutnya')
                    ->date()
                    ->sortable()
                    ->placeholder('Belum tersedia'),
    
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
    
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable(),
            ])
            ->searchPlaceholder('(Kode Barang)')  
            ->filters([
                SelectFilter::make('jurusan')
                    ->label('Filter Berdasarkan Jurusan')
                    ->options([
                        'sija'   => 'SIJA',
                        'dkv'    => 'DKV',
                        'sarpras'=> 'SARPRAS',
                    ])
                    ->default('sija')
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
            
                // Filter opsi periode standar
                SelectFilter::make('periode_filter')
                    ->label('Filter Periode Umum')
                    ->options([
                        '1' => '1 Bulan',
                        '3' => '3 Bulan',
                        '6' => '6 Bulan',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === '1') {
                            return $query->where('periode', 30);
                        } elseif ($data['value'] === '3') {
                            return $query->where('periode', 90);
                        } elseif ($data['value'] === '6') {
                            return $query->where('periode', 180);
                        }
                        return $query;
                    })
                    ->placeholder('Pilih Periode'),
            
                // Filter custom input periode
                Filter::make('custom_periode')
                    ->label('Filter Periode Custom')
                    ->form([
                        Forms\Components\TextInput::make('periode_value')
                            ->numeric()
                            ->label('Periode (dalam hari)'),
                    ])
                    ->query(function ($query, array $data) {
                        if (! empty($data['periode_value'])) {
                            return $query->where('periode', $data['periode_value']);
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                EditAction::make()
                    ->modalHeading('Edit Periode Pemeliharaan')
                    ->modalWidth('lg')
                    ->form(fn ($record) => [
                        Forms\Components\Select::make('id_barang')
                            ->label('Barang')
                            ->options(function () {
                                return [];
                            })
                            ->default($record->id_barang)
                            ->required(),
    
                        Forms\Components\TextInput::make('periode')
                            ->label('Periode Pemeliharaan')
                            ->default($record->periode)
                            ->required(),
    
                        Forms\Components\TextInput::make('deskripsi')
                            ->label('Deskripsi')
                            ->nullable()
                            ->default($record->deskripsi),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'id_barang'  => $data['id_barang'],
                            'periode'    => $data['periode'],
                            'deskripsi'  => $data['deskripsi'],
                        ]);
                    }),
            ]);
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            Tabs::make('Tabs')
                ->tabs([
                    Tabs\Tab::make('Informasi Pemeliharaan')
                        ->schema([
                            Fieldset::make('Informasi Pemeliharaan')
                                ->schema([
                                    TextEntry::make('nama_barang')
                                        ->label('Nama Barang')
                                        ->getStateUsing(fn($record) =>
                                            $record->inventaris->nama_barang ?? 
                                            $record->inventarisDKV->nama_barang ?? 
                                            $record->inventarisSarpras->nama_barang ?? 'N/A'
                                        ),
                                    TextEntry::make('merek')
                                        ->label('Merek Barang')
                                        ->getStateUsing(fn($record) =>
                                            $record->inventaris->merek ?? 
                                            $record->inventarisDKV->merek ?? 
                                            $record->inventarisSarpras->merek ?? 'N/A'
                                        ),
                                    TextEntry::make('periode'),
                                    TextEntry::make('kode_barang'),                                                              
                                ])->columnSpan(2)->columns(2),
                            Grid::make()
                                ->schema([
                                    Section::make('Tanggal Maintenance')
                                        ->schema([
                                            TextEntry::make('tanggal_maintenance_selanjutnya')
                                                ->label('Maintenance Selanjutnya')
                                                ->date(),         
                                        ])->columns(1),
                                ])->columnSpan(1),
                        ])->columns(3),
                    Tabs\Tab::make('Deksripsi Maintenance')
                        ->schema([
                            Section::make('Deskripsi')
                                ->description('Deskripsi Maintenance')
                                ->schema([
                                    TextEntry::make('deskripsi')
                                        ->label('')
                                        ->columnSpan(2),
                                ]),
                        ]),
                ]),    
        ])->columns(1);
    }
    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeriodePemeliharaans::route('/'),
            'create' => Pages\CreatePeriodePemeliharaan::route('/create'),
            'edit' => Pages\EditPeriodePemeliharaan::route('/{record}/edit'),
            'view' => Pages\ViewPeriodePemeliharaan::route('/{record}'),
        ];
    }
}
