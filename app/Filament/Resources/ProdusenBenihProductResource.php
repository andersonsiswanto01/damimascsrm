<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdusenBenihProductResource\Pages;
use App\Filament\Resources\ProdusenBenihProductResource\RelationManagers;
use App\Models\ProdusenBenihProduct;
use Filament\Forms;
use Filament\Forms\Form;
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
    protected static ?string $navigationGroup = 'Corporate Analyzer'; // This groups it under 'Corporate Resources'
    protected static ?string $navigationIcon = 'hugeicons-product-loading';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ->actions([
                Tables\Actions\EditAction::make(),
               Tables\Actions\Action::make('viewBuyers')
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProdusenBenihProducts::route('/'),
            // 'create' => Pages\CreateProdusenBenihProduct::route('/create'),
            // 'edit' => Pages\EditProdusenBenihProduct::route('/{record}/edit'),
        ];
    }

    public static function eagerLoadRelations(): array
{
    return ['produsen', 'sp2bksProducts'];
}
}
