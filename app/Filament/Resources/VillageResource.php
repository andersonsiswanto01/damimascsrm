<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VillageResource\Pages;
use App\Models\Village;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class VillageResource extends Resource
{
    protected static ?string $model = Village::class;

    protected static ?string $navigationGroup = 'Region Settings'; // ✅ Grouping it here
    
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('district_id')
                ->relationship('district', 'name')
                ->required()
                ->label('District'),
    
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->label('Village Name'),
    
            TextInput::make('district.regency.province.name')
                ->disabled()
                ->label('Province')
                ->dehydrated(false),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
        ->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('district.name')->label('District')->sortable(),
            TextColumn::make('district.regency.name')->label('Regency')->sortable(),
            TextColumn::make('district.regency.province.name')->label('Province')->sortable(),
            TextColumn::make('name')->label('Village')->sortable(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVillages::route('/'),
            'create' => Pages\CreateVillage::route('/create'),
            'edit' => Pages\EditVillage::route('/{record}/edit'),
        ];
    }
}
