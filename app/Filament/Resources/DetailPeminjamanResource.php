<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DetailPeminjamanResource\Pages;
use App\Filament\Resources\DetailPeminjamanResource\RelationManagers;
use App\Models\DetailPeminjaman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailPeminjamanResource extends Resource
{
    protected static ?string $model = DetailPeminjaman::class;

    protected static ?string $modelLabel = 'Detail Peminjaman';

    protected static ?string $pluralModelLabel = 'Detail Peminjaman';

    protected static ?string $slug = 'detail-peminjaman';

    protected static ?string $navigationLabel = 'Detail Peminjaman';

    protected static ?string $navigationGroup = 'Events';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            // Bagian Informasi Peminjaman
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Select::make('id_peminjaman')
                        ->label('Peminjaman')
                        ->relationship('peminjaman', 'keterangan_peminjaman')
                        ->required()
                        ->columnSpan(2), // Lebar penuh pada layout dua kolom

                    Forms\Components\DatePicker::make('tgl_kembali')
                        ->label('Tanggal Kembali')
                        ->required()
                        ->columnSpan(1),
                ])
                ->columns(3)
                ->columnSpan('full'),

            // Bagian Status & Kondisi Barang
            Forms\Components\Section::make('Status & Kondisi Barang')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'proses_pengajuan' => 'Proses Pengajuan',
                            'sudah_dikembalikan' => 'Sudah Dikembalikan',
                            'dipinjam' => 'Dipinjam',
                            'pengajuan_ditolak' => 'Pengajuan Ditolak',
                        ])
                        ->required()
                        ->columnSpan(2),

                    Forms\Components\Select::make('kondisi_barang_akhir')
                        ->label('Kondisi Barang Akhir')
                        ->options([
                            'lengkap' => 'Lengkap',
                            'tidak_lengkap' => 'Tidak Lengkap',
                            'rusak' => 'Rusak',
                        ])
                        ->required()
                        ->columnSpan(2),
                ])
                ->columns(4)
                ->columnSpan('full'),

            // Bagian Keterangan
            Forms\Components\Section::make('Keterangan')
                ->schema([

                    Forms\Components\Textarea::make('ket_tidak_lengkap_awal')
                    ->label('Keterangan Ketidaksesuaian Awal')
                    ->maxLength(100),

                    Forms\Components\Textarea::make('ket_tidak_lengkap_akhir')
                    ->label('Keterangan Ketidaksesuaian Akhir')
                    ->maxLength(100),

                    Forms\Components\Textarea::make('ket_ditolak_pengajuan')
                        ->label('Keterangan Penolakan Pengajuan')
                        ->maxLength(100),
                ])
                ->columns(2)
                ->collapsible(), // Tambahkan fitur collapsible untuk bagian ini
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_peminjaman')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_kembali')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('ket_ditolak_pengajuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kondisi_barang_akhir'),
                Tables\Columns\TextColumn::make('ket_tidak_lengkap_awal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ket_tidak_lengkap_akhir')
                    ->searchable(),
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
            'index' => Pages\ListDetailPeminjaman::route('/'),
            'create' => Pages\CreateDetailPeminjaman::route('/create'),
            'view' => Pages\ViewDetailPeminjaman::route('/{record}'),
            'edit' => Pages\EditDetailPeminjaman::route('/{record}/edit'),
        ];
    }
}
