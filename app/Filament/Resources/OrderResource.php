<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
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
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\ActionGroup;
use Filament\Pages\Actions\Action;
use Filament\Notifications\Notification;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Sales';
   
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                Section::make('Customer Details')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Customer Name'),

                        Forms\Components\TextInput::make('address')
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
                                            $exists = \App\Models\Regency::query()
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
                                            $exists = \App\Models\District::query()
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
                                            $exists = \App\Models\Village::query()
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
                

                Forms\Components\Section::make('Payment Details')
                    ->schema([

                        Forms\Components\Grid::make(2) // 💡 Two-column layout inside the section
                        ->schema([
                            FileUpload::make('payment_photo')
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
                                ->nullable(),

                            DatePicker::make('payment_date')
                                ->label('Payment Date')
                                ->nullable(),
    
                        ]),

                    ]),
                
                
    
                Forms\Components\Section::make('Required Documents')
                    ->schema([
                        Forms\Components\Grid::make(2) // 💡 Two-column layout inside the section
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
                
                    Forms\Components\Section::make('Order Status')
                    ->schema([
                        Forms\Components\Grid::make(2) // 💡 Two-column layout inside the section
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
                Tables\Columns\TextColumn::make('id')
                ->label('Order ID')
                ->sortable(),
                Tables\Columns\TextColumn::make('orderMaster.id') // Assuming 'orderMaster' is the relationship method name
                    ->label('Order Master ID')
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
                Tables\Columns\TextColumn::make('stage.code')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record) => $record->stage?->color ?? 'secondary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('orderMaster.payment_verified')
                    ->label('Payment Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'verified' : 'not_verified')
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),

                Tables\Columns\TextColumn::make('orderMaster.customer.telephone_number')
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

                Tables\Columns\TextColumn::make('orderMaster.user.name')
                    ->label('Sales Representative')->sortable(),
                ])
                
                ->filters([
                    Tables\Filters\SelectFilter::make('order_stage_id')
                        ->label('Order Status')
                        ->options([
                            '1' => 'Menunggu Dokument Lengkap',
                            '2' => 'Sedang Verifikasi Dokumen',
                            '3' => 'Menunggu Pembayaran',
                            '4' => 'Menunggu Pengiriman',
                            '5' => 'Persiapan Kirim',
                            '6' => 'Selesai',
                            '7' => 'Batal',
                        ]),
                ])
            
                ->actions([
                    ActionGroup::make([
                        Actions\EditAction::make(),
                        Actions\Action::make('accept_document')
                            ->label('Verify Document')
                            ->color('success')
                            ->icon('heroicon-m-check-circle')
                            ->requiresConfirmation()
                            ->visible(fn (Order $record): bool => $record->order_stage_id == 1)
                             ->action(function (Order $record, $action)  {
                                if ($record->order_stage_id >= 2) {
                                     Notification::make()
                                    ->title('Submission Failed')
                                    ->body('You cannot submit the application at this stage.')
                                    ->danger()
                                    ->send();
                                    return;
                                }

                                // Continue with valid stage
                                $record->incrementOrderStage('Applying for document verification');

                                  Notification::make()
                                ->title('Success')
                                ->body('Application submitted successfully.')
                                ->success()
                                ->send();
                            }),

                        Actions\Action::make('Generate PI PDF')
                            ->url(fn (Order $record): string => route('order.pi.pdf', $record->id))
                            ->icon('heroicon-o-pencil')
                            ->color('success')
                            ->requiresConfirmation()
                            ->label('PI')
                            ->visible(fn (Order $record): bool => $record->order_stage_id >= 2),
                
                    ])->iconButton(), // Make sure the iconButton is placed correctly here
                ])
                ->bulkActions([
                    Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }



}
