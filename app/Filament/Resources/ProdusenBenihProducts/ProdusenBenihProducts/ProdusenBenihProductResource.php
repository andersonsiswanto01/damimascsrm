<?php

namespace App\Filament\Resources\ProdusenBenihProducts\ProdusenBenihProducts;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\ProdusenBenihProducts\Pages\ListProdusenBenihProducts;
use App\Filament\Resources\ProdusenBenihProducts\Pages;
use App\Filament\Resources\ProdusenBenihProductResource\RelationManagers;
use App\Models\ProdusenBenihProduct;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;



class ProdusenBenihProductResource extends Resource
{
    protected static ?string $model = ProdusenBenihProduct::class;
    protected static string | \UnitEnum | null $navigationGroup = 'Corporate Analyzer'; // This groups it under 'Corporate Resources'
        protected static ?string $navigationParentItem = "Produsen Benih";
    protected static string | \BackedEnum | null $navigationIcon = 'hugeicons-product-loading';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('product_name')->required(),
                Select::make('produsen_benih_id')
                    ->label('Producer')
                    ->relationship('produsen', 'name') // Assuming your product belongsTo producer
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product_name')
                ->label('Product Name')
                ->sortable()
                ->searchable(),

                TextColumn::make('produsen.name') // assuming 'name' in Produsen model
                    ->label('Produsen')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('quantity_sum')
                    ->label('Total Quantity')
                    ->getStateUsing(function ($record) {
                        return $record->sp2bksProducts()->sum('quantity');
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
               Action::make('viewBuyers')
    ->label('View Buyers')
    ->icon('heroicon-o-user-group')
    ->modalHeading('Top Buyers')
    ->modalSubmitAction(false)
    ->modalCancelActionLabel('Close')
    ->mountUsing(function ($record, $livewire) {
        $record->load('sp2bksProducts');

        $buyers = $record->sp2bksProducts
            ->filter(fn ($product) => $product->sp2bks && $product->sp2bks->customer)
            ->groupBy(fn ($product) => $product->sp2bks->customer->id)
            ->map(function ($group) {
                $customer = $group->first()->sp2bks->customer;

                return [
                    'customer_id' => $customer->id,
                    'company_name' => $customer->company_name,
                    'quantity' => $group->sum('quantity'),
                ];
            })
            ->values()
            ->sortByDesc('quantity')
            ->take(5)
            ->all();

        $livewire->topBuyers = $buyers; // save to Livewire property
    })
    ->modalContent(fn () => view('filament.custom.top-buyers')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListProdusenBenihProducts::route('/'),
            // 'create' => Pages\CreateProdusenBenihProduct::route('/create'),
            // 'edit' => Pages\EditProdusenBenihProduct::route('/{record}/edit'),
        ];
    }

    public static function eagerLoadRelations(): array
{
    return ['produsen', 'sp2bksProducts'];
}
}
