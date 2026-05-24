<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DispatchResource\Pages;
use App\Filament\Resources\DispatchResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DispatchResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $modelLabel = 'Dispatch';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Seed Production Unit';

    public static function getEloquentQuery(): Builder
    {
          return Order::query()
            ->whereHas('orderDocumentStage', function ($q) {
            $q->where('code', 'deliver_ready');
        });
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('Order ID')
                ->sortable(),
                Tables\Columns\TextColumn::make('orderMaster.user.name')
                ->label('Sales Name')
                ->sortable(),
                Tables\Columns\TextColumn::make('orderMaster.customer.customer_name')
                ->label('Order Owner')
                ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                ->label('Order Name'),
                Tables\Columns\TextColumn::make('orderProducts.product.name')
                ->label('Products Ordered')
                ->badge(),
                Tables\Columns\TextColumn::make('delivery_date')->label('Delivery Date')->sortable(),
                Tables\Columns\TextColumn::make('orderMaster.customer.status')
                    ->label('Customer Type')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
            ])
            ->bulkActions([
    Tables\Actions\BulkAction::make('generateCertificates')
        ->label('Generate Certificates')
        ->icon('heroicon-o-document-text')
        ->form([
            Forms\Components\Select::make('mode')
                ->label('Action')
                ->options([
                    'generate_only' => 'Generate Certificates Only',
                    'generate_dispatch' => 'Generate + Dispatch Orders',
                    'dispatch' => 'Dispatch Only'
                ])
                ->required(),
        ])
        ->action(function ($records, array $data) {

            foreach ($records as $order) {

                GenerateCertificateJob::dispatch(
                    orderId: $order->id,
                    dispatchAfter: $data['mode'] === 'generate_dispatch'
                );
            }

            \Filament\Notifications\Notification::make()
                ->title('Certificate generation queued')
                ->success()
                ->send();
        })
        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListDispatches::route('/'),
            'create' => Pages\CreateDispatch::route('/create'),
            'edit' => Pages\EditDispatch::route('/{record}/edit'),
        ];
    }
}
