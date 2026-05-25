<?php

namespace App\Filament\Resources\Orders\Orders;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkAction;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Pages\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationParentItem = "Order Masters";
    protected static string | \UnitEnum | null $navigationGroup = 'Sales';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('orderMaster.payments');
    }


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                Section::make('Customer Details')
                    ->schema([
                        TextInput::make('customer_name')
                            ->label('Customer Name'),

                        TextInput::make('address')
                            ->label('Address'),

                            Grid::make(2)->schema([
                                Select::make('province_id')
                                    ->relationship('province', 'name')
                                    ->label('Province')
                                    ->searchable()
                                    ->live(),
                            
                                Select::make('regency_id')
                                    ->relationship('regency', 'name')
                                    ->label('Regency')
                                    ->searchable()
                                    ->live()
                                    ->rules([
                                        fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                                            $exists = Regency::query()
                                                ->where('id', $value)
                                                ->where('province_id', $get('province_id'))
                                                ->exists();
                            
                                            if (! $exists) {
                                                $fail('The selected regency does not belong to the selected province.');
                                            }
                                        }
                                    ]),


                                
                            
                                Select::make('district_id')
                                    ->relationship('district', 'name')
                                    ->label('District')
                                    ->searchable()
                                    ->live()
                                    ->rules([
                                        fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                                            $exists = District::query()
                                                ->where('id', $value)
                                                ->where('regency_id', $get('regency_id'))
                                                ->exists();
                            
                                            if (! $exists) {
                                                $fail('The selected district does not belong to the selected regency.');
                                            }
                                        }
                                    ]),
                            
                                Select::make('village_id')
                                    ->relationship('village', 'name')
                                    ->label('Village')
                                    ->searchable()
                                    ->live()
                                    ->rules([
                                        fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                                            $exists = Village::query()
                                                ->where('id', $value)
                                                ->where('district_id', $get('district_id'))
                                                ->exists();
                            
                                            if (! $exists) {
                                                $fail('The selected village does not belong to the selected district.');
                                            }
                                        }
                                    ]),
                            ]),
                    ]),


                    
                    Section::make('Product and Delivery Details')
                    ->schema([

                        Grid::make(2) // 💡 Two-column layout inside the section
                        ->schema([

                            Repeater::make('orderProducts')
                            ->label('Order Products')
                            ->relationship() // ✅ Correct way to handle the relationship
                           
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name') // ✅ Ensure it references the correct Product relationship
                                    ->required(),
                        
                                TextInput::make('qty')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required()
                                    ->live()
                                    ->minValue(0)
                                    ->debounce(500)
                                    ->afterStateUpdated(function (?string $state, callable $set) {
                                        // Remove non-numeric characters
                                        $clean = preg_replace('/[^0-9]/', '', $state);
                                        $set('qty', $clean);
                                    }),
        
                                    TextInput::make('product_price')
                                    ->label('Product Price')
                                    ->numeric()
                                    ->live()
                                    ->debounce(500) // Adds a 500ms delay before triggering the calculation
                                    ->minValue(0)
                                    ->prefix('Rp')
                                    ->afterStateUpdated(function (?string $state, callable $set) {
                                        $clean = preg_replace('/[^0-9]/', '', $state);
                                        $set('product_price', $clean);
                                    }),

                                    TextInput::make('free_allowance_percentage')
                                    ->label('Free Allowance (%)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->step(0.01)
                                    ->suffix('%')
                                    ->required()
                                    ->default(5),// default only for new records
                            ])
                            ->reactive()
                            ->live()
                            ->reorderableWithDragAndDrop(false)
                            ->defaultItems(1)
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
                            ->label('Delivery Date')
                            ->required(),

                        ]),
                    ]),
                

                // Forms\Components\Section::make('Payment Details')
                //     ->schema([

                //         Forms\Components\Grid::make(2) // 💡 Two-column layout inside the section
                //         ->schema([
                //             FileUpload::make('payment_photo')
                //                 ->image()
                //                 ->imageEditor()
                //                 ->downloadable()
                //                 ->disk('local')
                //                 ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                //                 ->maxSize(2048)
                //                 ->directory('private/documents')
                //                 ->visibility('private')
                //                 ->openable()
                //                 ->fetchFileInformation(false)
                //                 ->acceptedFileTypes(['image/*'])
                //                 ->label('Payment Proof')
                //                 ->nullable(),

                //             DatePicker::make('payment_date')
                //                 ->label('Payment Date')
                //                 ->nullable(),
    
                //         ]),

                //     ]),
                
                
    

                    Section::make('Required Documents')
                        ->hidden(function ($livewire) {
                           return ($livewire->record?->orderMaster->customer->status) !== 'private';
                        })
                    ->schema([
                        Grid::make(2) // 💡 Two-column layout inside the section
                            ->schema([
                                FileUpload::make('landcertificate_photo')
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
                                    ->label('Land Certificate')
                                    ->multiple()
                                    ->nullable(),
                
                                FileUpload::make('statementletter_photo')
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
                                    ->label('Statement Letter')
                                    ->multiple()
                                    ->nullable(),

                                FileUpload::make('KTP_photo')
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
                                    ->label('KTP Photo')
                                    ->multiple()
                                    ->nullable(),

                                FileUpload::make('kartu_keluarga')
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
                                    ->label('Kartu Keluarga')
                                    ->multiple()
                                    ->nullable(),

                            ]),
                    ]),
                
                    Section::make('Order Status')
                    ->schema([
                        Grid::make(2) // 💡 Two-column layout inside the section
                            ->schema([
                                // Select::make('status')
                                // ->label('Order Status')
                                // ->options([
                                //     'pending' => 'Pending',
                                //     'paid' => 'Paid',
                                //     'processing' => 'Processing',
                                //     'shipped' => 'Shipped',
                                //     'delivered' => 'Delivered',
                                //     'canceled' => 'Canceled',
                                //     'delayed' => 'Delayed',
                                // ])
                                // ->required(),
                                
                                Textarea::make('notes')
                                ->label('Order Notes')
                                ->rows(3)
                                ->nullable(),
                            ]),
                    ]),
            
                ]);

    }

    public static function updateTotalPrice(callable $set, callable $get): void
    {
        $total = collect($get('orderProducts'))
            ->map(fn ($item) => (int) ($item['qty'] ?? 0) * (int) ($item['product_price'] ?? 0))
            ->sum();
    
        $set('total_price', $total);
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
                TextColumn::make('orderMaster.id') // Assuming 'orderMaster' is the relationship method name
                    ->label('Order Master ID')
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
                // Tables\Columns\TextColumn::make('stage.code')
                //     ->label('Status')
                //     ->badge()
                //     ->color(fn ($record) => $record->stage?->color ?? 'secondary')
                //     ->sortable(),
                TextColumn::make('orderDocumentStage.code')
                    ->label('Documents Status')
                    ->badge()
                    ->color(fn ($record) => $record->orderDocumentStage->color ?? 'secondary')
                    ->sortable(),
                 TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->getStateUsing(function ($record) {

                        // 1. Get the parent OrderMaster
                        $orderMaster = $record->orderMaster; // assumes your relationship is named 'orderMaster'

                        if (!$orderMaster) {
                            return '-';
                        }

                        // 2. Calculate total order amount from all orders of this OrderMaster
                        $orderTotal = $orderMaster->orders
                            ->flatMap(fn ($order) => $order->orderProducts) // all products
                            ->sum(fn ($product) => $product->qty * $product->product_price);

                        // 3. Calculate total verified payments
                        $paymentTotal = $orderMaster->payments
                            ->where('order_payment_stage_id', 2)
                            ->sum('amount');

                        // 4. Determine status
                        if ($paymentTotal === 0) {
                            return 'No Payments';
                        }

                        if ($paymentTotal > $orderTotal) {
                            return 'Over Paid';
                        }

                        if ($paymentTotal == $orderTotal) {
                            return 'Paid';
                        }

                        if ($paymentTotal < $orderTotal) {
                            return 'Partially Paid';
                        }

                        return '-'; // fallback
                    })
                      ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Paid' => 'success',
                        'Partially Paid' => 'warning',
                        'Over Paid' => 'danger',
                        'No Payments' => 'warning',
                        default => 'secondary',
                    }),


                TextColumn::make('orderMaster.customer.telephone_number')
                    ->label('Customer Phone')
                    ->url(fn ($record) => 
                        optional($record->orderMaster?->customer?->telephone_number) ? 
                            'https://wa.me/' . preg_replace(
                                '/[^0-9]/',
                                '',
                                ($record->orderMaster->customer->phone_code ?? '') . 
                                ($record->orderMaster->customer->telephone_number ?? '')
                            ) : null
                    )
                    ->openUrlInNewTab()
                    ->formatStateUsing(fn ($state) => $state),

                TextColumn::make('orderMaster.user.name')
                    ->label('Sales Representative')->sortable(),
                ])
                
                ->filters([
                    SelectFilter::make('order_stage_id')
                        ->label('Order Status')
                        ->multiple()
                        ->options([
                            '1' => 'Waiting for Document',
                            '2' => 'Verifying Docs',
                            '3' => 'Documents Verified',
                            '4' => 'Verifying DisBun',
                            '5' => 'Waiting PO',
                            '6' => 'Complete',
                            '7' => 'Document Revision',
                            '8' => 'Canceled',
                        ]),
                    SelectFilter::make('orderMaster.user.name')
                        ->label('Sales Representative')
                        ->relationship('orderMaster.user', 'name')
                        ->multiple(),
                ])
            
                ->recordActions([
                    ActionGroup::make([
                        EditAction::make(),
                        \Filament\Actions\Action::make('document_verification')
                            ->label('Verify Document')
                            ->color('warning')
                            ->icon('heroicon-m-check-circle')
                            ->requiresConfirmation()
                            ->visible(fn (Order $record): bool => 
                                $record->orderDocumentStage->code === 'waiting_docs' 
                                || $record->orderDocumentStage->code === 'document_revision'
                            )
                             ->action(function (Order $record, $action)  {

                                // Continue with valid stage
                                $record->incrementOrderStage('Applying for document verification');
                                
                                  Notification::make()
                                ->title('Success')
                                ->body('Application submitted successfully.')
                                ->success()
                                ->send();
                            }),

                    ])->iconButton(), // Make sure the iconButton is placed correctly here
                ])
                ->toolbarActions([
                    DeleteBulkAction::make(),
                    BulkAction::make('showTotal')
                ->label('Show Total')
                ->action(function (Collection $records) {
                    foreach ($records as $order) {
            foreach ($order->orderProducts as $product) {
                $pid = $product->product_id;
                $pname = $product->product->name ?? 'Unknown Product';

                if (! isset($totals[$pid])) {
                    $totals[$pid] = [
                        'product_id'   => $pid,
                        'product_name' => $pname,
                        'sum_qty'      => 0,
                    ];
                }

                $totals[$pid]['sum_qty'] += $product->qty;
            }

            
        }   

                     // Build notification body
            $body = collect($totals)
                ->map(fn ($item) => "{$item['product_name']} : {$item['sum_qty']}")
                ->implode("\n");

        Notification::make()
            ->title('Total Quantity by Product')
            ->body($body)
            ->success()
            ->send();
                }),
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

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }



}
