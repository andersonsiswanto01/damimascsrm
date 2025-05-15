<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderDocumentVerificationResource\Pages;
use App\Filament\Resources\OrderDocumentVerificationResource\RelationManagers;
use App\Models\OrderDocumentVerification;
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
use Filament\Forms\Components\DatePicker;


class OrderDocumentVerificationResource extends Resource
{

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Admin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    
    public static function canCreate(): bool
{
    return false;
}

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('id')->label('Order ID')->sortable(),
            TextColumn::make('orderMaster.customer.customer_name')->label('Owner Name'),
            TextColumn::make('customer_name')->label('Customer Name'),
            TextColumn::make('stage.name')->label('Current Status'),
            TextColumn::make('created_at')->label('Order Date')->dateTime(),
        ])
        ->actions([
            Action::make('accept_document')
                ->label('Verify Document')
                ->color('success')
                ->icon('heroicon-m-check-circle')
                ->requiresConfirmation()
                ->action(function ($record) {

                    // First increment: Confirm document verification
                    $record->incrementOrderStage('confirmed! Verifying Payment');
                    // Check related orderMaster payment_status
                    if ($record->orderMaster->payment_verified === 1) {
                        // Second increment: Because payment is already confirmed
                        $record->incrementOrderStage('payment already confirmed, skipping...');
                    }
                }),
        
            Action::make('view_documents')
                ->label('View Documents')
                ->icon('heroicon-o-eye')
                ->color('warning')
                ->form(fn (Order $record) => [
                    TextInput::make('customer_name')
                        ->label('Customer')
                        ->default($record->customer_name ?? '-')
                        ->disabled(),
        
                    FileUpload::make('KTP_photo')
                        ->image()
                        ->label('KTP Photo')
                        ->disk('local')
                        ->directory('private/documents')
                        ->visibility('private')
                        ->deletable(false)
                        ->downloadable()
                        ->openable()
                        ->multiple()
                        ->fetchFileInformation(false)
                        ->default($record->KTP_photo ?? []), // Needs to be wrapped in an array if using FileUpload with single file
        
                    FileUpload::make('landcertificate_photo')
                        ->image()
                        ->label('Land Certificate')
                        ->disk('local')
                        ->directory('private/documents')
                        ->visibility('private')
                        ->downloadable()
                        ->openable()
                        ->fetchFileInformation(false)
                        ->multiple()
                        ->default($record->landcertificate_photo ?? []), // Ensure it's an array if multiple
        
                    FileUpload::make('statementletter_photo')
                        ->image()
                        ->label('Statement Letter')
                        ->disk('local')
                        ->directory('private/documents')
                        ->visibility('private')
                        ->deletable(false)
                        ->downloadable()
                        ->openable()
                        ->default($record->statementletter_photo?? []), // Wrap in array for single file
                ])
                ->modalSubmitAction(false)
                ->modalAlignment(Alignment::Center)
                ->modalWidth('4xl'),
        
            Action::make('returnOrder')
                ->label('Return Order')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('danger')
                ->requiresConfirmation() // Optional confirmation step
                ->form([
                    Textarea::make('notes')
                        ->label('Reason for Returning')
                        ->placeholder('Please provide a reason')
                        ->required(),
                ])
                ->action(function ($record, array $data) {
                    $record->decrementOrderStage($data['notes'] ?? 'Returned by admin');
                }),
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
    return Order::query()
        ->whereHas('stage', fn ($q) => $q->where('code', 'verifying_docs'));
}

public static function getModel(): string
{
    return \App\Models\Order::class;
}

public static function getNavigationLabel(): string
{
    return 'Document Verification';
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderDocumentVerifications::route('/'),
            'create' => Pages\CreateOrderDocumentVerification::route('/create'),
            'edit' => Pages\EditOrderDocumentVerification::route('/{record}/edit'),
        ];
    }
}
