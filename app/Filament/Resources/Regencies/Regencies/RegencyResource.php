<?php

namespace App\Filament\Resources\Regencies\Regencies;

use Filament\Schemas\Schema;
use App\Filament\Resources\Regencies\Pages\ListRegencies;
use App\Filament\Resources\Regencies\Pages\CreateRegency;
use App\Filament\Resources\Regencies\Pages\EditRegency;
use App\Filament\Resources\Regencies\Pages;
use App\Filament\Resources\RegencyResource\RelationManagers;
use App\Models\Regency;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegencyResource extends Resource
{
    protected static ?string $model = Regency::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Region Settings'; // ✅ Grouping it here
    
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('province_id')
                    ->label('Province')
                    ->relationship('province', 'name')
                    ->required(),
                TextInput::make('name')
                    ->label('Regency Name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('province.name')->label('Province')->sortable()->searchable(),
            TextColumn::make('name')->sortable()->searchable(),
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
            'index' => ListRegencies::route('/'),
            'create' => CreateRegency::route('/create'),
            'edit' => EditRegency::route('/{record}/edit'),
        ];
    }
}
