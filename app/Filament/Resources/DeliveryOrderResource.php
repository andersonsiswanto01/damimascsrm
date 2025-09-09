<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryOrderResource\Pages;
use App\Filament\Resources\DeliveryOrderResource\RelationManagers;
use App\Models\DeliveryOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use App\Models\Order;
use App\Models\OrderStage;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ActionGroup;

class DeliveryOrderResource extends Resource
{

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            TextColumn::make('id')->label('Order ID')->sortable(),
            TextColumn::make('orderMaster.customer.customer_name')->label('Customer Name'),
            TextColumn::make('stage.code')->label('Current Status'),
            TextColumn::make('orderMaster.user_id')->label('Sales Person')
                ->getStateUsing(function (Order $record) {
                    return User::find($record->orderMaster->user_id)->name;
                }),
          TextColumn::make('orderMaster.customer.telephone_number')
                ->label('Customer Phone')
                ->url(fn ($record) => 
                    'https://wa.me/' . preg_replace(
                        '/[^0-9]/', 
                        '', 
                        $record->orderMaster->customer->phone_code . $record->orderMaster->customer->telephone_number
                    )
                )
                ->openUrlInNewTab()
                ->formatStateUsing(fn ($state) => $state),
            TextColumn::make('created_at')->label('Order Date')->dateTime(),
        ])
      ->actions([
    ActionGroup::make([
        Action::make('accept_document')
            ->label('Verify Document')
            ->color('success')
            ->icon('heroicon-m-check-circle')
            ->requiresConfirmation()
            ->action(function (Order $record) {
                $record->incrementOrderStage('confirmed! Payment Verified, Seller is preparing the order');
            }),

        Action::make('view_orders')
            ->label('View Order')
            ->icon('heroicon-o-eye')
            ->color('warning')
            ->form(function (Order $record) {
                return [
                    TextInput::make('orderMaster.customer.customer_name')
                        ->label('Owner Name')
                        ->disabled()
                        ->default($record->orderMaster->customer->customer_name)
                        ->readOnly(),

                    TextInput::make('customer_name')
                        ->label('Name')
                        ->disabled()
                        ->default($record->customer_name)
                        ->readOnly(),

                    Repeater::make('orderProducts')
                        ->label('Products')
                        ->disableItemDeletion()
                        ->disableItemMovement()
                        ->disableItemCreation()
                        ->schema([
                            Select::make('product_id')
                                ->label('Product')
                                ->relationship('product', 'name')
                                ->disabled(),

                            TextInput::make('qty')
                                ->numeric()
                                ->minValue(1)
                                ->disabled(),

                            TextInput::make('product_price')
                                ->label('Product Price')
                                ->numeric()
                                ->prefix('Rp')
                                ->disabled(),
                        ])
                        ->default(function ($record) {
                            return $record->orderProducts->map(function ($item) {
                                return [
                                    'product_id' => $item->product_id,
                                    'qty' => $item->qty,
                                    'product_price' => $item->product_price,
                                ];
                            })->toArray();
                        })
                        ->columns(3)
                        ->columnSpanFull(),

                    FileUpload::make('landcertificate_photo')
                        ->image()
                        ->label('Land Certificate')
                        ->downloadable()
                        ->disk('local')
                        ->directory('private/documents')
                        ->visibility('private')
                        ->openable()
                        ->fetchFileInformation(false)
                        ->multiple()
                        ->default(fn($record) => $record->landcertificate_photo)
                        ->nullable()
                        ->disabled(),

                    FileUpload::make('KTP_photo')
                        ->image()
                        ->label('KTP Photo')
                        ->downloadable()
                        ->disk('local')
                        ->directory('private/documents')
                        ->visibility('private')
                        ->openable()
                        ->fetchFileInformation(false)
                        ->multiple()
                        ->default(fn($record) => $record->KTP_photo)
                        ->nullable()
                        ->disabled(),

                    FileUpload::make('statementleter_photo')
                        ->image()
                        ->label('Statement Letter')
                        ->downloadable()
                        ->disk('local')
                        ->directory('private/documents')
                        ->visibility('private')
                        ->openable()
                        ->fetchFileInformation(false)
                        ->default(fn($record) => $record->statementleter_photo)
                        ->nullable()
                        ->disabled(),
                ];
            })
            ->modalSubmitAction(false)
            ->modalAlignment(Alignment::Center),

        Action::make('returnOrder')
            ->label('Return Order')
            ->icon('heroicon-o-arrow-uturn-left')
            ->color('danger')
            ->requiresConfirmation()
            ->form([
                Textarea::make('notes')
                    ->label('Reason for Returning')
                    ->placeholder('Please provide a reason')
                    ->required(),
            ])
            ->action(function ($record, array $data) {
                $record->decrementOrderStage($data['notes'] ?? 'Returned by admin');
            }),
    ])
    ->label('Order Actions') // Optional: label for the group dropdown
    // ->icon('heroicon-m-document-text') // Optional: icon for the group
])

        ->recordAction('view_orders')
        ->recordUrl(null); // <--- activates the action on row click;
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
            'index' => Pages\ListDeliveryOrders::route('/'),
            'create' => Pages\CreateDeliveryOrder::route('/create'),
            'edit' => Pages\EditDeliveryOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return Order::query()
            ->whereHas('stage', fn ($q) => $q->where('code', 'waiting_shipping'));
    }
    
    public static function getModel(): string
    {
        return \App\Models\Order::class;
    }
    public static function getNavigationLabel(): string
    {
        return 'Delivery Orders';
    }
    public static function getNavigationGroup(): ?string
    {
        return 'Sales';
    }


}
