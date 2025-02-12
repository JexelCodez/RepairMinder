<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ZoneUserResource\Pages;
use App\Filament\Resources\ZoneUserResource\RelationManagers;
use App\Models\ZoneUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ZoneUserResource extends Resource
{
    protected static ?string $model = ZoneUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $modelLabel = 'Area User';
    protected static ?string $pluralModelLabel = 'Area User';
    protected static ?string $navigationLabel = 'Area User';
    protected static ?string $navigationGroup = 'Manage User';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    // Tambahkan metode ini agar hanya admin yang bisa mengakses resource ini
    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('zone_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('zone_name')
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
            'index' => Pages\ListZoneUsers::route('/'),
            // 'create' => Pages\CreateZoneUser::route('/create'),
            'view' => Pages\ViewZoneUser::route('/{record}'),
            // 'edit' => Pages\EditZoneUser::route('/{record}/edit'),
        ];
    }
}
