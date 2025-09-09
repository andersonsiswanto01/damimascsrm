<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisBunVerificationResource\Pages;
use App\Filament\Resources\DisBunVerificationResource\RelationManagers;
use App\Jobs\CompileOrderDocuments;
use App\Models\DisBunVerification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\BulkAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use App\Jobs\GenerateDocumentBundle;
use Dom\Text;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\Alignment;

class DisBunVerificationResource extends Resource
{
    protected static string $resource = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

        public static function getNavigationLabel(): string
    {
        return 'Disbun Verification';
    }
    public static function getNavigationGroup(): ?string
    {
        return 'Seed Production Unit';
    }

        public static function getModel(): string
        {
            return \App\Models\Order::class;
        }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                TextInput::make('customer_name')
                    ->label('Customer Name')
                    ->disabled(),

                TextInput::make('address')
                    ->label('Address')
                    ->disabled(),

                Grid::make(2)->schema([
                    Select::make('province_id')
                        ->label('Province')
                        ->relationship('province', 'name')
                        ->disabled(),

                Select::make('regency_id')
                        ->relationship('regency', 'name')
                        ->label('Regency')
                        ->disabled(),

                 Select::make('district_id')
                                    ->relationship('district', 'name')
                                    ->label('District')
                                    ->disabled(),
                
                 Select::make('village_id')
                                    ->relationship('village', 'name')
                                    ->label('Village')
                                    ->disabled()                  
                ]),

                Forms\Components\Section::make('Product and Delivery Details')
                    ->schema([

                        Forms\Components\Grid::make(2) // 💡 Two-column layout inside the section
                        ->schema([

                            Repeater::make('orderProducts')
                            ->label('Order Products')
                            ->relationship() // ✅ Correct way to handle the relationship
                           
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name') // ✅ Ensure it references the correct Product relationship
                                    ->disabled(),
                        
                                TextInput::make('qty')
                                    ->numeric()
                                    ->minValue(1)
                                    ->disabled(),
        
                                TextInput::make('product_price')
                                    ->label('Product Price')
                                    ->disabled(),
        
                            ])
                            ->disabled()
                            ->columns(3)
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                // Get the order products data
                                $orderProducts = $get('orderProducts');
                                
                                // Debug: Dump the qty and product_price values for each item in the repeater
                                $filteredData = collect($orderProducts)->map(function ($item) {
                                    return [
                                        'qty' => $item['qty'],
                                        'product_price' => $item['product_price'],
                                    ];
                                });
                                
                                // Calculate total price (you can still do this after the dd)
                                $total = collect($orderProducts)
                                    ->map(fn ($item) => 
                                        (float) preg_replace('/[^\d.]/', '', $item['qty'] ?? 0) * 
                                        (float) preg_replace('/[^\d.]/', '', $item['product_price'] ?? 0)
                                    )
                                    ->sum();
                        
                                // Set the total price after the calculation
                                $set('total_price', number_format($total, 0, ',', '.'));
                            })
                            ->afterStateHydrated(function (callable $set, callable $get) {
                                // On form load, calculate the total price based on the existing data
                                $orderProducts = $get('orderProducts');
                                
                                $total = collect($orderProducts)
                                    ->map(fn ($item) => ($item['qty'] ?? 0) * ($item['product_price'] ?? 0))
                                    ->sum();
                        
                                // Set the initial total price
                                $set('total_price', number_format($total, 0, ',', '.'));
                            }),

                            TextInput::make('total_price')
                            ->label('Total Price')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false)
                            ->reactive(),  // Make it reactive  

                            DatePicker::make('delivery_date')
                            ->disabled(),
                        ]),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('id')->label('ID')->sortable(),
            TextColumn::make('orderMaster.id')->label('Order ID')->sortable(),
            TextColumn::make('orderMaster.customer.customer_name')
                ->label('Customer Name')
                ->sortable()
                ->searchable(),
            TextColumn::make('orderMaster.user.name')->label('Sales'),
            // TextColumn::make('orderProducts.product.name')
            //     ->label('Products Ordered')
            //     ->badge(),
            TextColumn::make('orderProducts')
                ->label('Products Ordered')
                ->formatStateUsing(function ($state, $record) {
                    return $record->orderProducts
                        ->map(fn ($op) => optional($op->product)->name . ' (' . $op->qty . ')')
                        ->implode(', ');
                })
                ->wrap(),
            TextColumn::make('orderMaster.customer.status')->label('Type'),
            TextColumn::make('delivery_date')->label('Delivery Date'),
            ])
            ->filters([
            
            SelectFilter::make('product_id')
                ->label('Filter by Product')
                ->relationship('orderProducts.product', 'name'),
            ])
            ->actions([
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

                      Grid::make(2)->schema([
                TextInput::make('province')
                        ->label('Province')
                        ->disabled()
                        ->default($record->province->name)
                        ->readOnly(),
                               
                ]),

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
                    
                    FileUpload::make('kartu_keluarga')
                        ->image()
                        ->label('Kartu Keluarga')
                        ->downloadable()
                        ->disk('local')
                        ->directory('private/documents')
                        ->visibility('private')
                        ->openable()
                        ->fetchFileInformation(false)
                        ->multiple()
                        ->default(fn($record) => $record->kartu_keluarga)
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

                    FileUpload::make('statementletter_photo')
                        ->image()
                        ->label('Statement Letter')
                        ->downloadable()
                        ->disk('local')
                        ->directory('private/documents')
                        ->visibility('private')
                        ->openable()
                        ->multiple()
                        ->fetchFileInformation(false)
                        ->default(fn($record) => $record->statementletter_photo)
                        ->nullable()
                        ->disabled(),
                ];
            })
            ->modalSubmitAction(false)
            ->modalAlignment(Alignment::Center),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                BulkAction::make('download_documents')
    ->label('Download PDF Bundle')
    ->icon('heroicon-m-arrow-down-tray')
    ->action(function (Collection $records): void {
        
    CompileOrderDocuments::dispatch($records, auth()->user());

    Notification::make()
        ->title('Document generation started')
        ->success()
        ->send();
        })
    ->requiresConfirmation()
    ->deselectRecordsAfterCompletion(),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getFormActions(): array
{
    return [];
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDisBunVerifications::route('/'),
        ];
    }
}
