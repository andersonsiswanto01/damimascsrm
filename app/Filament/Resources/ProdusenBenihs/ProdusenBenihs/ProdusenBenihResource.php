<?php

namespace App\Filament\Resources\ProdusenBenihs\ProdusenBenihs;

use Filament\Schemas\Schema;
use App\Filament\Resources\ProdusenBenihs\Pages\ListProdusenBenihs;
use App\Filament\Resources\ProdusenBenihs\Pages\CreateProdusenBenih;
use App\Filament\Resources\ProdusenBenihs\Pages;
use App\Filament\Resources\ProdusenBenihResource\RelationManagers;
use App\Models\ProdusenBenih;
use App\Models\Sp2bksProduct;
use Dom\Text;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;


class ProdusenBenihResource extends Resource
{
    protected static ?string $model = ProdusenBenih::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel = 'Produsen Benih';
    protected static string | \UnitEnum | null $navigationGroup = 'Corporate Analyzer'; // This groups it under 'Corporate Resources'

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('address')
                    ->label('Address')
                    ->required()
                    ->maxLength(255),
            ])
            
            ;
    }

    protected function getTableActions(): array
    {
        return [
            EditAction::make(), // This opens the 'edit' modal per row
        ];
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Name')->searchable(),
                TextColumn::make('address')->label('Address')->searchable(),
                TextColumn::make('total_sp2bks_quantity')
                ->label('Total Quantity (SP2BKS)')
                ->getStateUsing(fn ($record) => $record->getTotalQuantity()),
            ])
            ->recordActions([
                EditAction::make()->modal(),
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
            'index' => ListProdusenBenihs::route('/'),
            'create' => CreateProdusenBenih::route('/create'),
            // 'edit' => Pages\EditProdusenBenih::route('/{record}/edit'),
        ];
    }
}
