<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentVerificationResource\Pages;
use App\Filament\Resources\PaymentVerificationResource\RelationManagers;
use App\Models\PaymentVerification;
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


use App\Models\OrderMaster;
use App\Models\OrderStage;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class PaymentVerificationResource extends Resource
{


    protected static ?string $navigationIcon = 'heroicon-s-shield-check';
    protected static ?string $navigationGroup = 'Admin';



    public static function canCreate(): bool
{
    return false;
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
            TextColumn::make('id')->label('Order ID')->sortable(),
            TextColumn::make('customer.customer_name')->label('Customer Name'),
            TextColumn::make('total_order_price')
                ->label('Total Price')
                ->getStateUsing(function ($record) {
                    $totalPrice = 0;

                    // Iterate through each order
                    foreach ($record->orders as $order) {
                        // Iterate through each product in the order
                        foreach ($order->orderProducts as $product) {
                            // Add the product of price and quantity to totalPrice
                            $totalPrice += $product->product_price * $product->qty;
                        }
                    }

                    // Return the total price formatted as Rupiah
                    return number_format($totalPrice, 0, ',', '.');
                })
                ->prefix('Rp. ') // Add Rupiah symbol as a prefix
                ->alignCenter(),
         ])
        ->actions([
            Action::make('accept_document')
                ->label('Verify Document')
                ->color('success')
                ->icon('heroicon-m-check-circle')
                ->requiresConfirmation()
                ->action(function (OrderMaster $record) {
                    $record->payment_verified = true;
                    $record->incrementOrderStage('Payment Verified'); // Increment the order stage to 'Payment Verified'
                    $record->save();
                }),

                Action::make('view_documents')
                ->label('View Documents')
                ->icon('heroicon-o-eye')
                ->color('warning')
                ->form(function (OrderMaster $record) {
                    return [
                        TextInput::make('customer_id')
                            ->label('Customer')
                            ->default($record->customer->customer_name) // Populate customer_name from the record
                            ->readOnly(),
                        
                
                        FileUpload::make('payment_picture')
                            ->image()
                            ->imageEditor()
                            ->downloadable()
                            ->disk('local')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                            ->maxSize(2048)
                            ->directory('private/documents')
                            ->visibility('private')
                            ->openable()
                            ->fetchFileInformation(false)
                            ->acceptedFileTypes(['image/*'])
                            ->label('Payment Proof')
                            ->multiple()
                            ->nullable()
                            ->default(fn ($record) => $record?->payment_picture ?? []),// Populate landcertificate_photo from the record
                
                    ];
                })
                ->modalSubmitAction(false) // 🚫 hides the submit button
                ->modalAlignment(Alignment::Center),

        ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return OrderMaster::query()
            ->where('payment_verified', false)
            ->with([
                'orders.orderProducts', // <-- eager loads all orders and their products
            ]);;
    }
    
    
    public static function getModel(): string
    {
        return \App\Models\OrderMaster::class;
    }

    public static function getNavigationLabel(): string
{
    return 'Payment Verification';
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentVerifications::route('/'),
            'create' => Pages\CreatePaymentVerification::route('/create'),
            'edit' => Pages\EditPaymentVerification::route('/{record}/edit'),
        ];
    }
}
