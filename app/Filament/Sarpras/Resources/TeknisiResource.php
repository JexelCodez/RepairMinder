<?php

namespace App\Filament\Sarpras\Resources;

use App\Filament\Sarpras\Resources\TeknisiResource\Pages;
use App\Filament\Sarpras\Resources\TeknisiResource\RelationManagers;
use App\Models\Teknisi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;
use Guava\FilamentModalRelationManagers\Actions\Table\RelationManagerAction;


class TeknisiResource extends Resource
{
    protected static ?string $model = Teknisi::class;
    protected static ?string $modelLabel = 'Teknisi';
    protected static ?string $pluralModelLabel = 'Teknisi';
    protected static ?string $navigationLabel = 'Teknisi';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Teknisi';

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('nama')
                        ->schema([
                            Forms\Components\TextInput::make('nama')
                                ->required()
                                ->maxLength(255),
                        ]),
                    Wizard\Step::make('informasi')
                        ->schema([                                            
                            Forms\Components\Textarea::make('informasi')
                                ->required(),
                        ]),
                ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('nama')
                            ->searchable()
                            ->sortable()
                            ->weight('medium')
                            ->alignLeft(),
                    ])->space(),

                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('informasi')
                            ->label('Informasi')
                            ->searchable()
                            ->sortable()
                            ->color('gray')
                            ->alignLeft(),
                    ])->space(2),
                ])->from('md'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                RelationManagerAction::make('maintenance-relation-manager')
                    ->label('Hasil Maintenance')
                    ->icon('heroicon-o-wrench')
                    ->relationManager(RelationManagers\MaintenanceRelationManager::make()),
                RelationManagerAction::make('laporan-relation-manager')
                    ->label('Hasil Laporan')
                    ->icon('heroicon-o-document-text')
                    ->relationManager(RelationManagers\LaporanRelationManager::make()),
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
            RelationManagers\MaintenanceRelationManager::class,
            RelationManagers\LaporanRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeknisis::route('/'),
            // 'create' => Pages\CreateTeknisi::route('/create'),
            // 'view' => Pages\ViewTeknisi::route('/{record}'),
            // 'edit' => Pages\EditTeknisi::route('/{record}/edit'),
        ];
    }
}
