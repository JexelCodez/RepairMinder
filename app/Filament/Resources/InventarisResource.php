<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarisResource\Pages;
use App\Filament\Resources\InventarisResource\RelationManagers;
use App\Models\Inventaris;
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

class InventarisResource extends Resource
{
    protected static ?string $model = Inventaris::class;
    protected static ?string $modelLabel = 'Inventaris';
    protected static ?string $pluralModelLabel = 'Inventaris';
    protected static ?string $navigationLabel = 'Inventaris';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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

                ImageColumn::make('qrcode_image')
                    ->label('QR Code'),

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
                    ->options(fn () => Inventaris::pluck('nama_ruangan', 'nama_ruangan')->toArray())
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
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
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
            'index' => Pages\ListInventaris::route('/'),
            'create' => Pages\CreateInventaris::route('/create'),
            // 'edit' => Pages\EditInventaris::route('/{record}/edit'),
        ];
    }
}