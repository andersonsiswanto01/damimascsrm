<?php

namespace App\Filament\Resources\Dispatches;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use App\Filament\Resources\Dispatches\Pages\ListDispatches;
use App\Filament\Resources\Dispatches\Pages\CreateDispatch;
use App\Filament\Resources\Dispatches\Pages\EditDispatch;
use App\Filament\Resources\DispatchResource\Pages;
use App\Filament\Resources\DispatchResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DispatchResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $modelLabel = 'Dispatch';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Seed Production Unit';

    public static function getEloquentQuery(): Builder
    {
          return Order::query()
            ->whereHas('orderDocumentStage', function ($q) {
            $q->where('code', 'deliver_ready');
        });
}

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                ->label('Order ID')
                ->sortable(),
                TextColumn::make('orderMaster.user.name')
                ->label('Sales Name')
                ->sortable(),
                TextColumn::make('orderMaster.customer.customer_name')
                ->label('Order Owner')
                ->sortable(),
                TextColumn::make('customer_name')
                ->label('Order Name'),
                TextColumn::make('orderProducts.product.name')
                ->label('Products Ordered')
                ->badge(),
                TextColumn::make('delivery_date')->label('Delivery Date')->sortable(),
                TextColumn::make('orderMaster.customer.status')
                    ->label('Customer Type')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
            ])
            ->toolbarActions([
    BulkAction::make('generateCertificates')
        ->label('Generate Certificates')
        ->icon('heroicon-o-document-text')
        ->form([
            Select::make('mode')
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

            Notification::make()
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
            'index' => ListDispatches::route('/'),
            'create' => CreateDispatch::route('/create'),
            'edit' => EditDispatch::route('/{record}/edit'),
        ];
    }
}
