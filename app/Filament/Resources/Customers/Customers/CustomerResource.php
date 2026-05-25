<?php

namespace App\Filament\Resources\Customers\Customers;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Fieldset;
use App\Models\InternalCorporate;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ExportAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Select;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use App\Models\OrderMaster;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textinput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use App\Models\SalesInteraction;
use App\Models\SalesInteractionParticipant;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Support\Enums\FontWeight;
use App\Filament\Exports\ProductExporter;
use Filament\Forms\Set;

class CustomerResource extends Resource
{



    protected static ?string $model = Customer::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Customer Lead Management';
    
    protected static string | \BackedEnum | null $navigationIcon = 'fluentui-people-audience-24-o';

    public static function form(Schema $schema): Schema
    {
        return $schema
        ->components([
            Tabs::make('Customer Details')
                ->tabs([
                    Tab::make('Personal Info')
                        ->schema([
                            Grid::make(2) // 💡 Two-column layout
                                ->schema([
                                    Forms\Components\TextInput::make('customer_name')
                                        ->label('Full Name')
                                        ->required()
                                        ->placeholder('e.g., John Doe')
                                        ->columnSpan(2), // 🔹 Half width
                                        Fieldset::make('Phone Number')
                                        ->schema([
                                            Grid::make(3) // Adjusts layout for better alignment
                                                ->schema([
                                                    Select::make('phone_code')
                                                        ->label('Code')
                                                        ->options([
                                                            '+1'    => '🇺🇸 +1',
                                                            '+7'    => '🇷🇺 +7',
                                                            '+20'   => '🇪🇬 +20',
                                                            '+27'   => '🇿🇦 +27',
                                                            '+30'   => '🇬🇷 +30',
                                                            '+31'   => '🇳🇱 +31',
                                                            '+32'   => '🇧🇪 +32',
                                                            '+33'   => '🇫🇷 +33',
                                                            '+34'   => '🇪🇸 +34',
                                                            '+36'   => '🇭🇺 +36',
                                                            '+39'   => '🇮🇹 +39',
                                                            '+40'   => '🇷🇴 +40',
                                                            '+41'   => '🇨🇭 +41',
                                                            '+43'   => '🇦🇹 +43',
                                                            '+44'   => '🇬🇧 +44',
                                                            '+45'   => '🇩🇰 +45',
                                                            '+46'   => '🇸🇪 +46',
                                                            '+47'   => '🇳🇴 +47',
                                                            '+48'   => '🇵🇱 +48',
                                                            '+49'   => '🇩🇪 +49',
                                                            '+51'   => '🇵🇪 +51',
                                                            '+52'   => '🇲🇽 +52',
                                                            '+54'   => '🇦🇷 +54',
                                                            '+55'   => '🇧🇷 +55',
                                                            '+60'   => '🇲🇾 +60',
                                                            '+61'   => '🇦🇺 +61',
                                                            '+62'   => '🇮🇩 +62',
                                                            '+63'   => '🇵🇭 +63',
                                                            '+64'   => '🇳🇿 +64',
                                                            '+65'   => '🇸🇬 +65',
                                                            '+66'   => '🇹🇭 +66',
                                                            '+81'   => '🇯🇵 +81',
                                                            '+82'   => '🇰🇷 +82',
                                                            '+84'   => '🇻🇳 +84',
                                                            '+86'   => '🇨🇳 +86',
                                                            '+90'   => '🇹🇷 +90',
                                                            '+91'   => '🇮🇳 +91',
                                                            '+92'   => '🇵🇰 +92',
                                                            '+94'   => '🇱🇰 +94',
                                                            '+98'   => '🇮🇷 +98',
                                                        ])
                                                        ->default('+62')
                                                        ->required()
                                                        ->columnSpan(1), // Takes 1 column space
                                    
                                                    Forms\Components\TextInput::make('telephone_number')
                                                        ->label('Phone Number')
                                                        ->required()
                                                        ->columnSpan(2), // Takes 2 column spaces to align with the dropdown
                                                ]),
                                            ]),
    
                                    Select::make('status')
                                        ->label('Customer Type')
                                        ->options([
                                            'private'  => 'Private',
                                            'corporate' => 'Corporate',
                                            'distributor' => 'Distributor',
                                            'breeder'  => 'Breeder',
                                        ])
                                        ->required()
                                        ->live()
                                        ->columnSpanFull(), // 🔹 Full width

                                    Select::make('internal_corporates_id')
                                            ->label('Internal Corporate')
                                            ->searchable()
                                            ->preload()
                                            ->relationship('internalCorporate', 'name') // Still required for saving
                                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->initial} - {$record->name}")
                                            ->getSearchResultsUsing(function (string $search) {
                                                return InternalCorporate::query()
                                                    ->where('initial', 'like', "%{$search}%")
                                                    ->orWhere('name', 'like', "%{$search}%")
                                                    ->limit(50)
                                                    ->get()
                                                    ->mapWithKeys(fn ($record) => [
                                                        $record->id => "{$record->initial} - {$record->name}",
                                                    ]);
                                            })
                                          ->columnSpanFull()
                                           ->hidden(fn ($get) => !in_array($get('status'), ['corporate', 'breeder', 'distributor']))
                                         ->afterStateUpdated(function ($state, $set) {
                                            $corporate = InternalCorporate::find($state);

                                            if ($corporate) {
                                                $set('organization_name', 'Sinarmas Group'); // Auto-fill organization name
                                                $set('company_name', $corporate->pt_name);
                                                $set('legal_form', $corporate->legal_form);
                                            }
                                        })
                                        ->live(),


                                        Select::make('legal_form')
                                        ->label('Legal Form') // Label untuk form
                                        ->options([
                                           'pt'       => 'Perseroan Terbatas (PT)',
                                            'pt_tbk'   => 'Perseroan Terbatas Terbuka (PT Tbk)',
                                            'pt_pma'   => 'Perseroan Terbatas Penanaman Modal Asing (PT PMA)',
                                            'pt_pmdn'  => 'Perseroan Terbatas Penanaman Modal Dalam Negeri (PT PMDN)',
                                            'cv'       => 'Persekutuan Komanditer (CV)',
                                            'firma'    => 'Firma (Fa)',
                                            'bumn'     => 'Badan Usaha Milik Negara (BUMN)',
                                            'perum'    => 'Perusahaan Umum (Perum)',
                                            'koperasi' => 'Koperasi',
                                            'yayasan'  => 'Yayasan',
                                            // 🔹 Perusahaan Tidak Berbadan Hukum
                                            'ud'        => 'Usaha Dagang (UD)',
                                            'perorangan' => 'Perseorangan',
                                            // 🔹 Bentuk Hukum Internasional
                                            'llc'       => 'Limited Liability Company (LLC) - USA',
                                            'corp'      => 'Corporation (Corp) - USA',
                                            'ltd'       => 'Private Limited Company (Ltd) - UK, Singapore',
                                            'plc'       => 'Public Limited Company (PLC) - UK',
                                            'gmbh'      => 'Gesellschaft mit beschränkter Haftung (GmbH) - Germany',
                                            'sarl'      => 'Société à Responsabilité Limitée (SARL) - France',
                                            'sa'        => 'Société Anonyme (SA) - France, Spain',
                                            'pvt_ltd'   => 'Private Limited (Pvt Ltd) - India',
                                            'llp'       => 'Limited Liability Partnership (LLP) - Global',
                                            'sole_trader' => 'Sole Trader - UK, Australia',
                                        ])
                                        ->required()
                                        ->hidden(fn ($get) => !in_array($get('status'), ['corporate', 'breeder', 'distributor']))
                                        ->columnSpanFull(), // 🔹 Full width
    
                                        Forms\Components\TextInput::make('company_name')
                                        ->label('Company Name')
                                        ->hidden(fn ($get) => !in_array($get('status'), ['corporate', 'breeder','distributor']))
                                        ->placeholder('e.g., Sinarmas Agro Resources and Technology (SMART)') // Example input
                                        ->columnSpanFull(),
                                    
                                Forms\Components\TextInput::make('organization_name')
                                        ->label('Organization Name')
                                        ->placeholder('e.g., Sinarmas Group') // Auto-fill
                                        ->hidden(fn ($get) => !in_array($get('status'), ['corporate', 'breeder', 'distributor']))
                                        ->columnSpanFull(),
                                ]),
    
                            Grid::make(2) // 💡 Another two-column layout for address fields
                                ->schema([
                                    Select::make('province_id')
                                    ->label('Province')
                                    ->options(Province::pluck('name', 'id'))
                                    ->live()
                                    ->required()
                                    ->columnSpan(1)
                                    ->afterStateUpdated(function ($set) {
                                        $set('regency_id', null);
                                        $set('district_id', null);
                                        $set('village_id', null);
                                    }), // ✅ Reset City, District and Village
                                
                                    Select::make('regency_id')
                                    ->label('City')
                                    ->options(fn ($get) =>
                                        Regency::where('province_id', $get('province_id'))->pluck('name', 'id')
                                    )
                                    ->live()
                                    ->required()
                                    ->disabled(fn ($get) => !$get('province_id'))
                                    ->columnSpan(1)
                                    ->afterStateUpdated(fn ($set) => $set('district_id', null)), // ✅ Reset District,
                                
                                    Select::make('district_id')
                                    ->label('District')
                                    ->options(fn ($get) =>
                                        District::where('regency_id', $get('regency_id'))->pluck('name', 'id')
                                    )
                                    ->live()
                                    ->required()
                                    ->disabled(fn ($get) => !$get('regency_id'))
                                    ->columnSpan(1)
                                    ->afterStateUpdated(fn ($set) => $set('village_id', null)), // ✅ Reset Village,
                                
                                    Select::make('village_id')
                                    ->label('Village')
                                    ->options(fn ($get) =>
                                        Village::where('district_id', $get('district_id'))->pluck('name', 'id')
                                    )
                                    ->live()
                                    ->required()
                                    ->disabled(fn ($get) => !$get('district_id'))
                                    ->columnSpan(1)
                                    ->afterStateUpdated(fn ($set) => $set('address', null)) // ✅ Reset Address
                                ]),

                                Forms\Components\TextInput::make('address')
                                ->label('Address')
                                ->columnSpanFull(), // 🔹 Full width
                        ]),
    
                    Tab::make('Documents')
                        ->schema([

                            // Forms\Components\TextInput::make('nik_ktp')
                            //     ->label('NIK KTP')
                            //     ->columnSpanFull(),
       
                                // Forms\Components\FileUpload::make('KTP_photo')
                                // ->image()
                                // ->imageEditor()
                                // ->downloadable()
                                // ->fetchFileInformation(false)
                                // ->label('KTP Photo')
                                // ->directory('private/documents') // Store in storage/app/private/documents
                                // ->visibility('private') // Make it private
                                // ->disk('local') // Use the local disk
                                // ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                                // ->maxSize(2048)
                                // ->columnSpanFull()
                                // ->openable(),
                            
                            Forms\Components\TextInput::make('npwp')
                            ->label('NPWP')
                            ->columnSpanFull(),

                            FileUpload::make('NPWP_photo')
                            ->image()
                            ->imageEditor()
                                ->label('NPWP Photo')
                                ->image()
                                ->disk('local') // Local disk (private storage)
                                ->downloadable()
                                ->directory('private/documents') // Store in storage/app/private/documents
                                ->visibility('private') // Make it private
                                ->moveFiles() // Ensure it's moved securely
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                                ->maxSize(2048)
                                ->preserveFilenames() // Keep original filename
                                ->columnSpanFull()
                                ->openable(),
                        
                                
                        ]),
    
                    Tab::make('SP2BKS Info')
                        ->schema([
                            Repeater::make('corporateSp2bks')
                            ->relationship('corporateSp2bk') // name of the relationship method
                            ->label('SP2BKS Files')
                            ->schema([
                                TextInput::make('id')
                                    ->label('SP2BKS ID')
                                    ->required(),

                                FileUpload::make('sp2bks_file')
                                    ->label('SP2BKS File(s)')
                                    ->multiple()
                                    ->downloadable()
                                    ->disk('local')
                                    ->openable()
                                    ->directory('private/documents')
                                    ->visibility('private')
                                    ->fetchFileInformation(false)
                                    ->preserveFilenames()
                                    ->nullable()
                                    ->acceptedFileTypes(['application/pdf']),

                                DatePicker::make('expiry_date')
                                    ->label('Expiry Date'),

                                Repeater::make('sp2bksProducts')
                                    ->label('Products in SP2BKS')
                                    ->relationship('sp2bksProducts') // nested relationship
                                    ->schema([
                                        Select::make('produsen_benih_product_id')
                                            ->label('Product')
                                            ->relationship('produsenBenihProduct', 'product_name')
                                            ->searchable()
                                            ->preload()
                                            ->getOptionLabelFromRecordUsing(function ($record) {
                                                return optional($record->produsen)->name . ' - ' . $record->product_name;
                                            })
                                            ->createOptionForm([
                                                TextInput::make('product_name')->required(),
                                                Select::make('produsen_benih_id')
                                                    ->label('Producer')
                                                    ->relationship('produsen', 'name') // Assuming your product belongsTo producer
                                                    ->searchable()
                                                    ->preload()
                                                    ->createOptionForm([
                                                        TextInput::make('name')->required(),
                                                    ])
                                            ]) ,
                        
                                        TextInput::make('quantity')
                                            ->label('Quantity')
                                            ->numeric()
                                            ->required(),
                                    ])
                                    ->collapsible()
                                    ->defaultItems(1)
                                    ->columns(2),

                            ])
                            ->columns(1)
                            ->collapsible(),

                            // Forms\Components\TextInput::make('SP2BKS_id')
                            //     ->label('SP2BKS ID')
                            //     ->required()
                            //     ->columnSpanFull(), // 🔹 Full width
    
                            // Forms\Components\DatePicker::make('SP2BKS_expired')
                            //     ->label('SP2BKS Expiry Date')
                            //     ->required()
                            //     ->columnSpan(1), // 🔹 Half width
    
                            // Forms\Components\FileUpload::make('SP2BKS_photo')
                            // ->image()
                            // ->imageEditor()
                            //     ->label('SP2BKS Photo')
                            //     ->image()
                            //     ->disk('local') // Use the local disk (not public)
                            //     ->downloadable()
                            //     ->directory('private/documents') // Store in storage/app/private/documents
                            //     ->visibility('private') // Make it private
                            //     ->moveFiles() // Ensure it's moved securely
                            //     ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                            //     ->maxSize(2048)
                            //     ->preserveFilenames() // Keep original filename
                            //     ->columnSpanFull() // 🔹 Full width
                            //     ->openable(),
                        ])
                        ->hidden(fn ($get) => !in_array($get('status'), ['corporate', 'breeder','distributor'])), // ✅ Hide if private
                ]),
        ]);
    
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Split::make([
                TextColumn::make('customer_name')
                ->label('Customer')
                ->weight(FontWeight::Bold)
                ->searchable(),

            ])->from('md'),

            Split::make([
                TextColumn::make('status')->label('Status'),
                TextColumn::make('registration_date')->label('Registered'),
                TextColumn::make('province.name')->label('Province'),
            TextColumn::make('customer_phone')
                ->label('Phone')
                ->getStateUsing(fn ($record) => 
                    '(' . $record->phone_code . ')' . $record->telephone_number
                )
            ])->from('md'),

            TextColumn::make('company_name')
            ->label('Company')
            ->searchable(),

            TextColumn::make('internalCorporate.initial')
                ->label('Internal Corporate')
                ->searchable()
                ->getStateUsing(fn ($record) => $record->internalCorporate ? "{$record->internalCorporate->initial} - {$record->internalCorporate->name}" : ''),
        
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'private' => 'Private',
                        'corporate' => 'Corporate',
                        'breeder' => 'Breeder',
                    ]),
                SelectFilter::make('province_id')
                    ->relationship('province', 'name'),
            ])
            ->headerActions([
            ExportAction::make()
                ->exporter(ProductExporter::class)
        ])
            ->recordActions([
                 ActionGroup::make([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
    
                // ✅ Action to LOG a New Interaction
                Action::make('log_interaction')
                ->label('Log Interaction')
                ->icon('heroicon-m-plus-circle')
                ->slideOver()
                ->schema([
                    Select::make('interaction_type')
                        ->label('Interaction Type')
                        ->options([
                            'call' => 'Call',
                            'email' => 'Email',
                            'meeting' => 'Meeting',
                            'whatsapp' => 'WhatsApp',
                        ])
                        ->required(),
            
                    DatePicker::make('interaction_date')
                        ->label('Interaction Date')
                        ->default(now())
                        ->required(),
            
                    DatePicker::make('follow_up_date')
                        ->label('Follow-Up Date')
                        ->nullable(),
            
                    Textarea::make('summary')
                        ->label('Summary')
                        ->rows(3)
                        ->required(),
                ])
                ->action(function (array $data, $record) {
                    // 🔹 Step 1: Create the sales interaction
                    $interaction = SalesInteraction::create([
                        'interaction_type' => $data['interaction_type'],
                        'interaction_date' => $data['interaction_date'],
                        'follow_up_date' => $data['follow_up_date'] ?? null,
                        'summary' => $data['summary'],
                        'created_by' => auth()->id(),
                        'created_at' => now(),
                    ]);
            
                    // 🔹 Step 2: Link the interaction to the customer in sales_interaction_participants
                    SalesInteractionParticipant::create([
                        'sales_interaction_id' => $interaction->id,
                        'participant_id' => $record->id,
                        'participant_type' => get_class($record), // Automatically determines if it's a Customer or LeadProspect
                    ]);
            
                    Notification::make()
                        ->title('Interaction Logged')
                        ->success()
                        ->send();
                }),
            
                // ✅ Separate Action to VIEW Interaction & Purchase History
                Action::make('view_history')
                    ->label('View History')
                    ->icon('heroicon-m-eye')
                    ->schema(fn ($record) => [
                        Tabs::make('History')
                            ->tabs([
                                // 🔹 TAB 1: Interaction History
                                Tab::make('Interaction History')
                                ->schema(
                                    SalesInteraction::whereIn('id', function ($query) use ($record) {
                                        $query->select('sales_interaction_id')
                                              ->from('sales_interaction_participants')
                                              ->where('participant_id', $record->id)
                                              ->where('participant_type', get_class($record));
                                    })
                                    ->latest()
                                    ->get()
                                    ->map(fn ($interaction) =>
                                        Fieldset::make("On {$interaction->interaction_date}")
                                            ->schema([
                                                Placeholder::make('interaction_type')
                                                    ->label('Type')
                                                    ->content(ucfirst($interaction->interaction_type)),
                            
                                                Placeholder::make('summary')
                                                    ->label('Summary')
                                                    ->content($interaction->summary),
                            
                                                Placeholder::make('follow_up_date')
                                                    ->label('Follow-Up Date')
                                                    ->content($interaction->follow_up_date ?? 'N/A'),
                            
                                                Placeholder::make('created_at')
                                                    ->label('Recorded At')
                                                    ->content($interaction->created_at->format('Y-m-d H:i:s')),
                                            ])
                                    )->toArray()
                                ),

                                // 🔹 TAB 2: Purchase History
                                Tab::make('Purchase History')
                                    ->schema(
                                        OrderMaster::where('customer_id', $record->id)
                                            ->with('orders.orderProducts.product')
                                            ->latest()
                                            ->get()
                                            ->map(fn ($order) =>
                                                Fieldset::make("Order #{$order->id} - {$order->delivery_date}")
                                                    ->schema([
                                                        Placeholder::make('order_id')
                                                            ->label('Order ID')
                                                            ->content($order->id),
    
                                                        Placeholder::make('notes')
                                                            ->label('Notes')
                                                            ->content($order->notes),
    
                                                        Placeholder::make('delivery_date')
                                                            ->label('Delivery Date')
                                                            ->content($order->delivery_date),
    
                                                        Placeholder::make('payment_date')
                                                            ->label('Payment Date')
                                                            ->content($order->payment_date),
    
                                                        Placeholder::make('status')
                                                            ->label('Order Status')
                                                            ->content(ucfirst($order->status)),
    
                                                        Placeholder::make('created_at')
                                                            ->label('Order Created')
                                                            ->content($order->created_at->format('Y-m-d H:i:s')),
    
                                                        Placeholder::make('updated_at')
                                                            ->label('Last Updated')
                                                            ->content($order->updated_at->format('Y-m-d H:i:s')),
    
                                                        // 🔹 Ordered Products Section
                                                        Fieldset::make('Ordered Items')
                                                            ->schema(
                                                                collect($order->orders)->flatMap(function ($orderItem) {
                                                                    return $orderItem->orderProducts->map(function ($item) {
                                                                        return Fieldset::make($item->product?->name ?? 'Unknown Product')
                                                                            ->schema([
                                                                                Placeholder::make('product_name')
                                                                                    ->label('Product')
                                                                                    ->content($item->product?->name ?? 'N/A'),

                                                                                Placeholder::make('quantity')
                                                                                    ->label('Quantity')
                                                                                    ->content($item->qty),

                                                                                Placeholder::make('price')
                                                                                    ->label('Price per Unit')
                                                                                    ->content(number_format($item->product?->price ?? 0, 2)),

                                                                                Placeholder::make('subtotal')
                                                                                    ->label('Subtotal')
                                                                                    ->content(number_format($item->qty * ($item->product?->price ?? 0), 2)),
                                                                            ]);
                                                                    });
                                                                })->toArray()
                                                            ),
                                                                                                            ])
                                            )->toArray()
                                    ),
                            ]),

                    ])
                 ])
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
        'index' => ListCustomers::route('/'),
        'create' => CreateCustomer::route('/create'),
        'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
