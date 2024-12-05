<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeminjamanResource\Pages;
use App\Filament\Resources\PeminjamanResource\RelationManagers;
use App\Models\Peminjaman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PeminjamanResource extends Resource
{
    protected static ?string $model = Peminjaman::class;

    protected static ?string $modelLabel = 'Peminjaman';

    protected static ?string $navigationLabel = 'Peminjaman';

    protected static ?string $navigationGroup = 'Events';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Select::make('id_users')
                        ->label('Pengguna')
                        ->relationship('users', 'name')
                        ->required()
                        ->columnSpan(1),

                    Forms\Components\Select::make('id_siswas')
                        ->label('Siswa')
                        ->relationship('siswas', 'nama')
                        ->required()
                        ->columnSpan(1),
                ])
                ->columns(2)
                ->columnSpan('full'),

            Forms\Components\Section::make('Detail Peminjaman')
                ->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\DatePicker::make('tgl_pinjam')
                                ->label('Tanggal Pinjam')
                                ->required()
                                ->columnSpan(6),

                            Forms\Components\DatePicker::make('tgl_kembali')
                                ->label('Tanggal Kembali')
                                ->required()
                                ->columnSpan(6),
                        ])
                        ->columns(12)
                        ->columnSpan(6),

                    Forms\Components\Textarea::make('keterangan_peminjaman')
                        ->label('Keterangan Peminjaman')
                        ->maxLength(50)
                        ->placeholder('Isi keterangan peminjaman (opsional)')
                        ->columnSpan(6),
                ])
                ->columns(12)
                ->columns(3)
                ->collapsible()
                ->columnSpan('full'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_users')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_siswas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan_peminjaman')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_pinjam')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_kembali')
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
            'index' => Pages\ListPeminjamen::route('/'),
            'create' => Pages\CreatePeminjaman::route('/create'),
            'view' => Pages\ViewPeminjaman::route('/{record}'),
            'edit' => Pages\EditPeminjaman::route('/{record}/edit'),
        ];
    }
}
