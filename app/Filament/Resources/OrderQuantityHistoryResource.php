<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderQuantityHistoryResource\Pages;
use App\Filament\Resources\OrderQuantityHistoryResource\RelationManagers;
use App\Models\OrderQuantityHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderQuantityHistoryResource extends Resource
{
    protected static ?string $model = OrderQuantityHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationGroup = 'Sales';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('order_id')
                ->relationship('order', 'id')
                ->required()
                ->label('Order ID'),
            Forms\Components\DatePicker::make('allocated_date')
                ->required(),
            Forms\Components\TextInput::make('allocated_quantity')
                ->required()
                ->numeric(),
            Forms\Components\TextInput::make('remaining_balance')
                ->required()
                ->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
       return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')->label('Order ID'),
                Tables\Columns\TextColumn::make('allocated_date')->label('Allocated Date'),
                Tables\Columns\TextColumn::make('allocated_quantity')->label('Allocated Quantity'),
                Tables\Columns\TextColumn::make('remaining_balance')->label('Remaining Balance'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOrderQuantityHistories::route('/'),
            'create' => Pages\CreateOrderQuantityHistory::route('/create'),
            'edit' => Pages\EditOrderQuantityHistory::route('/{record}/edit'),
        ];
    }
}
