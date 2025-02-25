<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceResource\Pages;
use App\Models\Maintenance;
use App\Models\PeriodePemeliharaan;
use App\Models\Inventaris;
use App\Models\InventarisDKV;
use App\Models\InventarisSarpras;
use App\Models\Teknisi;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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
use Illuminate\Support\Facades\Auth;

class MaintenanceResource extends Resource
{
    protected static ?string $model = Maintenance::class;
    protected static ?string $modelLabel = 'Maintenance';
    protected static ?string $pluralModelLabel = 'Maintenance';
    protected static ?string $navigationLabel = 'Maintenance';
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Penjadwalan & Perbaikan';

    // public static function shouldRegisterNavigation(): bool
    // {
    //     return auth()->check() && (
    //         auth()->user()->role === 'admin' ||
    //         (auth()->user()->role === 'teknisi' && strtolower(optional(auth()->user()->zoneUser)->zone_name) === 'sarpras')
    //     );
    // }
    
    // // Pastikan hanya admin dan teknisi dengan zone sarpras bisa melihat resource
    // public static function canViewAny(): bool
    // {
    //     return auth()->check() && (
    //         auth()->user()->role === 'admin' ||
    //         (auth()->user()->role === 'teknisi' && strtolower(optional(auth()->user()->zoneUser)->zone_name) === 'sarpras')
    //     );
    // }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_periode_pemeliharaan')
                    ->label('Barang')
                    ->options(function () {
                        return PeriodePemeliharaan::whereIn('kode_barang', Inventaris::pluck('kode_barang'))
                            ->with('inventaris') // Hanya ambil relasi inventaris
                            ->get()
                            ->mapWithKeys(function ($item) {
                                $namaBarang = $item->inventaris->nama_barang ?? 'Tanpa Nama';
                                return [$item->id => "{$item->kode_barang} ({$namaBarang})"];
                            });
                    })
                    ->searchable()
                    ->required()
                    ->placeholder('Pilih Barang')
                    ->rule(function (callable $get, ?Maintenance $record) {
                        return function (string $attribute, $value, callable $fail) use ($record, $get) {
                            // Ambil id_periode_pemeliharaan dari input saat ini
                            $periodeId = $get('id_periode_pemeliharaan');
                    
                            // Jika sedang membuat data baru
                            if (!$record) {
                                $existingMaintenance = Maintenance::where('id_periode_pemeliharaan', $periodeId)
                                    ->whereIn('status', ['sedang diproses', 'dalam perbaikan'])
                                    ->exists();
                    
                                if ($existingMaintenance) {
                                    $fail('Tidak dapat membuat maintenance baru karena masih ada maintenance yang sedang diproses atau dalam perbaikan.');
                                }
                            } 
                            // Jika sedang mengedit data
                            else {
                                // Status sebelumnya
                                $oldStatus = $record->getOriginal('status');
                    
                                // Cek apakah ada maintenance lain dalam periode ini yang sedang berjalan
                                $existingMaintenance = Maintenance::where('id_periode_pemeliharaan', $periodeId)
                                    ->where('id', '!=', $record->id) // Exclude record yang sedang diedit
                                    ->whereIn('status', ['sedang diproses', 'dalam perbaikan'])
                                    ->exists();
                    
                                // Jika status sebelumnya "selesai", dan mencoba mengubah ke "sedang diproses/dalam perbaikan"
                                if ($oldStatus === 'selesai' && in_array($value, ['sedang diproses', 'dalam perbaikan'])) {
                                    // Cek apakah ada maintenance lain yang sedang berjalan
                                    if ($existingMaintenance) {
                                        $fail('Tidak dapat mengubah status kembali ke sedang diproses/dalam perbaikan karena ada maintenance lain yang sedang berjalan.');
                                    }
                                }
                            }
                        };
                    }),


                Forms\Components\Select::make('id_user')
                    ->label('Assigned User')
                    ->options(User::pluck('name', 'id'))
                    ->default(fn() => auth()->id())
                    ->searchable()
                    ->required(),
                
                Forms\Components\Select::make('id_teknisi')
                    ->label('Teknisi')
                    ->options(Teknisi::pluck('nama', 'id'))
                    ->default(fn() => auth()->id())
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('deskripsi_tugas')
                    ->label('Task Description')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'sedang diproses' => 'Sedang Diproses',
                        'dalam perbaikan' => 'Dalam Perbaikan',
                        'selesai' => 'Selesai',
                    ])
                    ->required()
                    ->reactive()
                    ->rule(function (callable $get, ?Maintenance $record) {
                        return function (string $attribute, $value, callable $fail) use ($record, $get) {
                            $periodeId = $get('id_periode_pemeliharaan');
                            if (in_array($value, ['sedang diproses', 'dalam perbaikan'])) {
                                $existingMaintenance = Maintenance::where('id_periode_pemeliharaan', $periodeId)
                                    ->when($record, function ($query) use ($record) {
                                        return $query->where('id', '!=', $record->id);
                                    })
                                    ->whereIn('status', ['sedang diproses', 'dalam perbaikan'])
                                    ->exists();
                                
                                if ($existingMaintenance) {
                                    $fail('Tidak dapat mengubah status ke sedang diproses/dalam perbaikan karena ada maintenance lain yang sedang berjalan.');
                                }
                            }
                        };
                    }),

                Forms\Components\DatePicker::make('tanggal_pelaksanaan')
                    ->label('Tanggal Pelaksanaan')
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('periode.kode_barang')
                    ->label('Kode Barang')
                    ->sortable()
                    ->searchable(),
            
                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->sortable()
                    ->getStateUsing(fn($record) =>
                        $record->periode?->inventaris->nama_barang ??
                        $record->periode?->inventarisDKV->nama_barang ??
                        $record->periode?->inventarisSarpras->nama_barang ?? 'N/A'
                    ),
            
                TextColumn::make('merek')
                    ->label('Merk Barang')
                    ->sortable()
                    ->getStateUsing(fn($record) =>
                        $record->periode?->inventaris->merek ??
                        $record->periode?->inventarisDKV->merek ??
                        $record->periode?->inventarisSarpras->merek ?? 'N/A'
                    )
                    ->toggleable(isToggledHiddenByDefault: true),
            
                TextColumn::make('user.name')
                    ->label('User Pelaksana')
                    ->searchable(),
            
                TextColumn::make('teknisi.nama')
                    ->label('Teknisi Pelaksana')
                    ->searchable(),
                    
                TextColumn::make('deskripsi_tugas')
                    ->label('Deskripsi Tugas')
                    ->limit(50),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'sedang diproses' => 'Sedang Diproses',
                        'dalam perbaikan' => 'Dalam Perbaikan',
                        'selesai' => 'Selesai',
                        default => $state,
                    })
                    ->icons([
                        'heroicon-o-play' => 'sedang diproses',
                        'heroicon-o-wrench-screwdriver' => 'dalam perbaikan',
                        'heroicon-o-check-circle' => 'selesai',
                    ])
                    ->colors([
                        'success' => 'selesai',
                        'warning' => 'dalam perbaikan',
                        'danger' => 'sedang diproses',
                    ]),    
            
                TextColumn::make('tanggal_pelaksanaan')
                    ->label('Tanggal Pelaksanaan')
                    ->date(),

                TextColumn::make('hasil_maintenance')
                    ->label('Hasil Maintenance')
                    ->placeholder('Belum Diisi')
                    ->limit(50),    
            ])
            ->searchPlaceholder('(Kode Barang, Teknisi)')         
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
                        return $query->whereHas('periode', function ($q) {
                            $q->whereIn('kode_barang', Inventaris::pluck('kode_barang'));
                        });
                    } elseif ($data['value'] === 'dkv') {
                        return $query->whereHas('periode', function ($q) {
                            $q->whereIn('kode_barang', InventarisDKV::pluck('kode_barang'));
                        });
                    } elseif ($data['value'] === 'sarpras') {
                        return $query->whereHas('periode', function ($q) {
                            $q->whereIn('kode_barang', InventarisSarpras::pluck('kode_barang'));
                        });
                    }
                    return $query;
                    })
                    ->placeholder('Pilih Jurusan'),
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        ''                 => 'Semua',
                        'sedang diproses'  => 'Sedang Diproses',
                        'dalam perbaikan'  => 'Dalam Perbaikan',
                        'selesai'          => 'Selesai',
                    ])
                    ->default('')
                    ->query(function ($query, $data) {
                        if (isset($data['value']) && $data['value'] !== '') {
                            return $query->where('status', $data['value']);
                        }
                        return $query;
                    })
                    ->placeholder('Pilih Status'),    
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('isi_hasil_maintenance')
                    ->label('Isi Hasil')
                    ->icon('heroicon-o-document-text')
                    ->modalHeading('Isi Hasil Maintenance')
                    ->form([
                        Forms\Components\Textarea::make('hasil_maintenance')
                            ->label('Hasil Maintenance')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'hasil_maintenance' => $data['hasil_maintenance'],
                        ]);
                    })
                    ->visible(fn($record) => $record->status === 'selesai' && !$record->hasil_maintenance),
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
                Tabs::make('Informasi Maintenance')
                    ->tabs([
                        Tabs\Tab::make('Detail Maintenance')
                            ->schema([
                                Fieldset::make('Informasi Utama')
                                    ->schema([
                                        TextEntry::make('periode.kode_barang')
                                            ->label('Kode Barang'),
    
                                        TextEntry::make('periode.periode')
                                            ->label('Periode Pemeliharaan'),    
    
                                        TextEntry::make('user.name')
                                            ->label('Assigned User'),
    
                                        TextEntry::make('teknisi.nama')
                                            ->label('Teknisi'),
    
                                        TextEntry::make('status')
                                            ->badge()
                                            ->label('Status')
                                            ->colors([
                                                'info' => 'sedang diproses',
                                                'warning' => 'dalam perbaikan',
                                                'success'  => 'selesai',
                                            ]),
                                    ])->columns(2),
    
                                Section::make('Tanggal Pelaksanaan')
                                    ->schema([
                                        TextEntry::make('tanggal_pelaksanaan')
                                            ->label('Tanggal Pelaksanaan')
                                            ->date(),
                                    ]),
                            ]),
    
                        Tabs\Tab::make('Deskripsi Tugas')
                            ->schema([
                                Section::make('Detail Tugas')
                                    ->schema([
                                        TextEntry::make('deskripsi_tugas')
                                            ->label('Deskripsi Tugas')
                                            ->columnSpan(2),
                                    ]),
                            ]),
    
                        Tabs\Tab::make('Hasil Maintenance')
                            ->schema([
                                Section::make('Hasil')
                                    ->schema([
                                        TextEntry::make('hasil_maintenance')
                                            ->label('Hasil Maintenance')
                                            ->placeholder('Belum Diisi'),
                                    ]),
                            ]),
                    ]),
            ])->columns(1);
    }
    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaintenances::route('/'),
            'create' => Pages\CreateMaintenance::route('/create'),
            // 'edit' => Pages\EditMaintenance::route('/{record}/edit'),
            'view' => Pages\ViewMaintenance::route('/{record}'),
        ];
    }
}
