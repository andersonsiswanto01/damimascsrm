<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductQuantityHistoryResource\Pages;
use App\Filament\Resources\ProductQuantityHistoryResource\RelationManagers;
use App\Models\ProductQuantityHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;

class ProductQuantityHistoryResource extends Resource
{
    protected static ?string $model = ProductQuantityHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Products';


    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Select::make('product_id')
                ->relationship('product', 'name')
                ->label('Product')
                ->required(),

            TextInput::make('previous_quantity')
                ->numeric()
                ->label('Previous Quantity')
                ->disabled(), // Auto-filled, not editable

            TextInput::make('quantity_change')
                ->numeric()
                ->label('Quantity Change')
                ->required(),

            TextInput::make('new_quantity')
                ->numeric()
                ->label('New Quantity')
                ->disabled(), // Auto-filled, not editable

            Textarea::make('reason')
                ->label('Reason for Change')
                ->required(),

            Select::make('user_id')
                ->relationship('user', 'name')
                ->label('Updated By')
                ->default(auth()->id())
                ->disabled(), // Auto-filled with the logged-in user
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('id')
                ->sortable()
                ->label('ID'),

            TextColumn::make('product.name')
                ->label('Product')
                ->searchable()
                ->sortable(),

            TextColumn::make('previous_quantity')
                ->label('Previous Quantity')
                ->sortable(),

            TextColumn::make('quantity_change')
                ->label('Quantity Change')
                ->sortable(),

            TextColumn::make('new_quantity')
                ->label('New Quantity')
                ->sortable(),

            TextColumn::make('reason')
                ->label('Reason')
                ->limit(50)
                ->tooltip(fn ($record) => $record->reason),

            TextColumn::make('user.name')
                ->label('Updated By')
                ->sortable(),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProductQuantityHistories::route('/'),
            'create' => Pages\CreateProductQuantityHistory::route('/create'),
            'edit' => Pages\EditProductQuantityHistory::route('/{record}/edit'),
        ];
    }
}
