<?php

namespace App\Filament\Resources\Provinces\Provinces;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\ProvinceS\Pages\ListProvinces;
use App\Filament\Resources\ProvinceS\Pages\CreateProvince;
use App\Filament\Resources\ProvinceS\Pages\EditProvince;
use App\Filament\Resources\ProvinceS\Pages;
use App\Models\Province;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class ProvinceResource extends Resource
{
    protected static ?string $model = Province::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Region Settings'; // ✅ Grouping it here

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

   
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique()
                    ->label('Province Name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
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
            'index' => ListProvinces::route('/'),
            'create' => CreateProvince::route('/create'),
            'edit' => EditProvince::route('/{record}/edit'),
        ];
    }
}
