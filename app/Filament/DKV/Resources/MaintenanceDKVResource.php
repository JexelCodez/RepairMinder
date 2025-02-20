<?php

namespace App\Filament\DKV\Resources;

use App\Filament\DKV\Resources\MaintenanceDKVResource\Pages;
use App\Filament\DKV\Resources\MaintenanceDKVResource\RelationManagers;
use App\Models\Maintenance;
use App\Models\Inventaris;
use App\Models\InventarisDKV;
use App\Models\InventarisSarpras;
use App\Models\PeriodePemeliharaan;
use App\Models\Teknisi;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;


class MaintenanceDKVResource extends Resource
{
    protected static ?string $model = Maintenance::class;
    protected static ?string $modelLabel = 'Maintenance';
    protected static ?string $pluralModelLabel = 'Maintenance';
    protected static ?string $navigationLabel = 'Maintenance';
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Penjadwalan & Perbaikan';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && (
            auth()->user()->role === 'admin' ||
            (auth()->user()->role === 'teknisi' && strtolower(optional(auth()->user()->zoneUser)->zone_name) === 'sarpras')
        );
    }
    
    // Pastikan hanya admin dan teknisi dengan zone sarpras bisa melihat resource
    public static function canViewAny(): bool
    {
        return auth()->check() && (
            auth()->user()->role === 'admin' ||
            (auth()->user()->role === 'teknisi' && strtolower(optional(auth()->user()->zoneUser)->zone_name) === 'sarpras')
        );
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_periode_pemeliharaan')
                    ->label('Barang')
                    ->options(function () {
                        return PeriodePemeliharaan::whereIn('kode_barang', InventarisDKV::pluck('kode_barang'))
                            ->with('inventarisDKV') // Hanya ambil relasi inventaris
                            ->get()
                            ->mapWithKeys(function ($item) {
                                $namaBarang = $item->inventarisDKV->nama_barang ?? 'Tanpa Nama';
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

                Forms\Components\TextArea::make('hasil_maintenance')
                    ->label('Hasil Maintenance')
                    ->maxLength(255)
                    ->visible(fn (callable $get) => $get('status') === 'selesai')
                    ->required(fn (callable $get) => $get('status') === 'selesai')
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
                    ->searchable()
                    ->getStateUsing(fn($record) =>
                        $record->periode?->inventaris->nama_barang ??
                        $record->periode?->inventarisDKV->nama_barang ??
                        $record->periode?->inventarisSarpras->nama_barang ?? 'N/A'
                    ),
            
                TextColumn::make('merek')
                    ->label('Merk Barang')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(fn($record) =>
                        $record->periode?->inventaris->merek ??
                        $record->periode?->inventarisDKV->merek ??
                        $record->periode?->inventarisSarpras->merek ?? 'N/A'
                    ),
            
                TextColumn::make('user.name')
                    ->label('User Pelaksana')
                    ->searchable(),
            
                TextColumn::make('deskripsi_tugas')
                    ->label('Deskripsi Tugas')
                    ->limit(50),
            
                TextColumn::make('status')
                    ->label('Status'),
            
                TextColumn::make('tanggal_pelaksanaan')
                    ->label('Tanggal Pelaksanaan')
                    ->date(),
            ])        
            ->filters([
                SelectFilter::make('jurusan')
                    ->label('Filter Berdasarkan Jurusan')
                    ->options([
                        'sija'    => 'SIJA',
                        'dkv'     => 'DKV',
                        'sarpras' => 'SARPRAS',
                    ])
                    ->default('dkv')
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
            'index' => Pages\ListMaintenanceDKVS::route('/'),
            'create' => Pages\CreateMaintenanceDKV::route('/create'),
            'edit' => Pages\EditMaintenanceDKV::route('/{record}/edit'),
        ];
    }
}
