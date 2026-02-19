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

use App\Models\OrderPayment;
use App\Models\OrderStage;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

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
            TextColumn::make('id')->label('Payment ID')->sortable(),
            TextColumn::make('orderMaster.customer.customer_name')->label('Customer Name'),
            TextColumn::make('payment_amount')
                ->label('Payment Amount')
                ->getStateUsing(function ($record) {

                    return number_format($record->amount, 0, ',', '.');
                })
                ->prefix('Rp. ') // Add Rupiah symbol as a prefix
                ->alignCenter(),

            TextColumn::make('payment_type')
                ->label('Payment Type')
                ->getStateUsing(function ($record) {
                    return $record->paymentType->name ?? 'N/A';
                })

         ])
        ->actions([
            Action::make('accept_payment')
                ->label('Accept Payment')
                ->color('success')
                ->icon('heroicon-m-check-circle')
                ->requiresConfirmation()
                ->action(function (OrderPayment $record) {
                    $record->incrementOrderStage(); // Increment the order stage to 'Payment Verified'
                    $record->save();
                    Notification::make()
                        ->title('Payment Has Been Verified')
                        ->success() // styles it as green ✅
                        ->send();
                }),

            Action::make('reject_payment')
                ->label('Reject Payment')
                ->color('danger')
                ->icon('heroicon-m-x-circle')
                ->requiresConfirmation()
                ->action(function (OrderPayment $record) {
                    $record->rejectOrderStage(); // Increment the order stage to 'Payment Verified'
                Notification::make()
                        ->title('Payment Has Been Rejected')
                        ->danger() // styles it as red ❌
                        ->send();
                }),

               Action::make('view_documents')
                ->label('View Documents')
                ->icon('heroicon-o-eye')
                ->color('warning')
                ->form(function (OrderPayment $record) {
                    return [
                        // Customer Name
                        TextInput::make('customer_id')
                            ->label('Customer')
                            ->default($record->orderMaster->customer->customer_name ?? '-') // safe fallback
                            ->disabled(), // readOnly alternative

                        // Payment Proofs
                        FileUpload::make('payment_proof')
                            ->label('Payment Proof')
                            ->image()
                            ->imageEditor()
                            ->downloadable()
                            ->openable()
                            ->disk('local')
                            ->directory('private/documents')
                            ->visibility('private')
                            ->multiple()
                            ->nullable()
                            ->fetchFileInformation(false)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                            ->default(fn ($record) => $record?->payment_proof ?? []),
                    ];
                })
                ->modalSubmitAction(false) // hide submit button
                ->modalAlignment(Alignment::Center),


        ]);
    }


    public static function getRelations(): array
    {
        return [
            
        ];
    }

 public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery() // important to keep default Filament filters
        ->with([
            'orderMaster.orders.orderProducts', // eager load products too if needed
            'orderMaster.customer', // if you want customer data
        ])
        ->where('order_payment_stage_id', 1); // only show payments pending verification
}
    
    
    public static function getModel(): string
    {
        return \App\Models\OrderPayment::class;
    }

    public static function getNavigationLabel(): string
{
    return 'Payment Verification';
}

public static function getNavigationBadge(): ?string
{
    // Count all records that match the same condition as your table query
    $count = static::getModel()::where('order_payment_stage_id', 1)->count();

    // Only show badge if there are pending verifications
    return $count > 0 ? (string) $count : null;
}

public static function getNavigationBadgeColor(): ?string
{
    // You can use 'danger', 'warning', 'primary', 'success', or 'info'
    return 'warning';
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
