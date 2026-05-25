<?php

namespace App\Filament\Resources\OrderQuantityHistories\OrderQuantityHistories;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\OrderQuantityHistories\Pages\ListOrderQuantityHistories;
use App\Filament\Resources\OrderQuantityHistories\Pages\CreateOrderQuantityHistory;
use App\Filament\Resources\OrderQuantityHistories\Pages\EditOrderQuantityHistory;
use App\Filament\Resources\OrderQuantityHistories\Pages;
use App\Filament\Resources\OrderQuantityHistoryResource\RelationManagers;
use App\Models\OrderQuantityHistory;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderQuantityHistoryResource extends Resource
{
    protected static ?string $model = OrderQuantityHistory::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static string | \UnitEnum | null $navigationGroup = 'Sales';

    public static function form(Schema $schema): Schema
    {
        return $schema
        ->components([
            Select::make('order_id')
                ->relationship('order', 'id')
                ->required()
                ->label('Order ID'),
            DatePicker::make('allocated_date')
                ->required(),
            TextInput::make('allocated_quantity')
                ->required()
                ->numeric(),
            TextInput::make('remaining_balance')
                ->required()
                ->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
       return $table
            ->columns([
                TextColumn::make('order_id')->label('Order ID'),
                TextColumn::make('allocated_date')->label('Allocated Date'),
                TextColumn::make('allocated_quantity')->label('Allocated Quantity'),
                TextColumn::make('remaining_balance')->label('Remaining Balance'),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
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
            'index' => ListOrderQuantityHistories::route('/'),
            'create' => CreateOrderQuantityHistory::route('/create'),
            'edit' => EditOrderQuantityHistory::route('/{record}/edit'),
        ];
    }
}
