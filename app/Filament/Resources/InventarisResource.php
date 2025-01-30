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
                    ->colors([
                        'success' => 'lengkap',
                        'warning' => 'tidak_lengkap',
                        'danger' => 'rusak',
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
                Tables\Actions\EditAction::make()->action(function ($record, array $data) {
                    // Update field di database
                    $record->update(['kondisi_barang' => $data['kondisi_barang']]);
                
                    // Kirim ke API
                    $response = Http::put("https://zaikotrack-main.test/api/inventaris/{$record->id_inventaris}/kondisi", [
                        'kondisi_barang' => $data['kondisi_barang'],
                    ]);
                
                    Log::info("Update API response", [
                        'status' => $response->status(),
                        'body' => $response->json(),
                    ]);
                
                    if ($response->successful()) {
                        // Force reload the table data after successful update
                        return redirect()->route('filament.resources.inventarises.index')
                            ->with('success', 'Status barang berhasil diperbarui!')
                            ->with('reload', true); // Using a custom flag to reload data
                    } else {
                        return redirect()->route('filament.resources.inventarises.index')
                            ->with('error', 'Gagal memperbarui status barang. ' . $response->body());
                    }
                }),                             
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
            'index' => Pages\ListInventaris::route('/'),
            'create' => Pages\CreateInventaris::route('/create'),
            'edit' => Pages\EditInventaris::route('/{record}/edit'),
        ];
    }
}