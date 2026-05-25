<?php

namespace App\Filament\Resources\Products\Products;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Exception;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-archive-box'; // Set a relevant icon
    protected static string | \UnitEnum | null $navigationGroup = 'Products';

    public static function form(Schema $schema): Schema
    {
        return $schema
        ->components([
            TextInput::make('name')
                ->label('Product Name')
                ->required()
                ->maxLength(255),

            TextInput::make('price')
                ->label('Price')
                ->numeric()
                ->minValue(0)
                ->required(),

            TextInput::make('stock')
                ->label('Stock')
                ->numeric()
                ->minValue(0)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('id')
                ->sortable()
                ->label('ID'),

            TextColumn::make('name')
                ->label('Product Name')
                ->searchable()
                ->sortable(),

            TextColumn::make('price')
                ->label('Price')
                ->sortable()
                ->money('IDR'), // Format as Indonesian Rupiah

            TextColumn::make('stock')
                ->label('Stock')
                ->sortable(),
        ])
        ->filters([])
        ->recordActions([
            EditAction::make(),
            DeleteAction::make(),
            Action::make('adjustStock')
                ->label('Adjust Stock')
                ->button()
                ->icon('heroicon-o-plus-circle')
                ->modalHeading('Adjust Product Stock')
                ->schema([
                    TextInput::make('quantity_change')
                        ->label('Quantity Change')
                        ->numeric()
                        ->required()
                        ->helperText('Use positive value to add stock, negative to reduce'),

                    Textarea::make('reason')
                        ->label('Reason')
                        ->required(),
                ])
                ->action(function (array $data, Product $record) {
                    try {
                        $record->adjustStock($data['quantity_change'], $data['reason'], auth()->id() ?? 1);

                        Notification::make()
                            ->title('Stock Updated')
                            ->body("Stock adjusted successfully. New stock: {$record->stock}")
                            ->success()
                            ->send();
                    } catch (Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
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
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
