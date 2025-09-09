<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderMasterResource\Pages;
use App\Filament\Resources\OrderMasterResource\RelationManagers;
use App\Models\OrderMaster;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Filament\Forms\Components\Section;
use App\Models\Customer;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use App\Models\Order;
use Filament\Tables\Actions;
use Filament\Tables\Actions\ActionGroup;


class OrderMasterResource extends Resource
{
    protected static ?string $model = OrderMaster::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Sales';
    

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            DatePicker::make('order_date')->required(),
            Textarea::make('notes'),
            Section::make('Master Customer')
            ->schema([
                Select::make('customer_id')
                ->relationship(
                    'customer',
                    'customer_name',
                    fn ($query) => $query->select(
                        'id',
                        'customer_name',
                        'telephone_number',
                        'province_id',
                        'regency_id',
                        'district_id',
                        'village_id'
                    )
                )

                ->createOptionForm([
                    Forms\Components\Tabs::make('Customer Details')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Personal Info')
                            ->schema([
                                Forms\Components\Grid::make(2) // 💡 Two-column layout
                                    ->schema([
                                        Forms\Components\TextInput::make('customer_name')
                                            ->label('Full Name')
                                            ->required()
                                            ->placeholder('e.g., John Doe')
                                            ->columnSpan(2), // 🔹 Half width
                                            Forms\Components\Fieldset::make('Phone Number')
                                            ->schema([
                                                Forms\Components\Grid::make(3) // Adjusts layout for better alignment
                                                    ->schema([
                                                        Forms\Components\Select::make('phone_code')
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
                                        
        
                                        Forms\Components\Select::make('status')
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
    
                                            Forms\Components\Select::make('legal_form')
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
        
                                Forms\Components\Grid::make(2) // 💡 Another two-column layout for address fields
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
        
                        Forms\Components\Tabs\Tab::make('Documents')
                            ->schema([
    
                                // Forms\Components\TextInput::make('nik_ktp')
                                //     ->label('NIK KTP')
                                //     ->columnSpanFull(),
           
                                //     Forms\Components\FileUpload::make('KTP_photo')
                                //     ->image()
                                //     ->imageEditor()
                                //     ->downloadable()
                                //     ->fetchFileInformation(false)
                                //     ->label('KTP Photo')
                                //     ->directory('private/documents') // Store in storage/app/private/documents
                                //     ->visibility('private') // Make it private
                                //     ->disk('local') // Use the local disk
                                //     ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                                //     ->maxSize(2048)
                                //     ->columnSpanFull()
                                //     ->openable(),
                                
                                Forms\Components\TextInput::make('npwp')
                                ->label('NPWP')
                                ->columnSpanFull(),
    
                                Forms\Components\FileUpload::make('NPWP_photo')
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
        
                            Forms\Components\Tabs\Tab::make('SP2BKS Info')
                            ->schema([
                                Repeater::make('corporateSp2bks')
                                ->relationship('corporateSp2bks') // name of the relationship method
                                ->label('SP2BKS Files')
                                ->schema([
                                    TextInput::make('id')
                                        ->label('SP2BKS ID'),
    
                                    FileUpload::make('sp2bks_file')
                                        ->label('SP2BKS File(s)')
                                        ->multiple()
                                        ->downloadable()
                                        ->disk('local')
                                        ->openable()
                                        ->directory('private/documents')
                                        ->visibility('private')
                                        ->fetchFileInformation(false)
                                        ->directory('corporate_sp2bks')
                                        ->preserveFilenames()
                                        ->nullable(),
    
                                    DatePicker::make('expiry_date')
                                        ->label('Expiry Date'),
    
                                    Repeater::make('sp2bksProducts')
                                        ->label('Products in SP2BKS')
                                        ->relationship('sp2bksProducts') // nested relationship
                                        ->schema([
                                            Select::make('produsenbenihproduct_id')
                                                ->label('Product')
                                                ->relationship('produsenBenihProduct', 'product_name')
                                                ->searchable()
                                                ->preload()
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
                            ])
                            ->hidden(fn ($get) => !in_array($get('status'), ['corporate', 'breeder','distributor'])), // ✅ Hide if private
                    ]),
                ])
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->customer_name} ({$record->telephone_number})")
                    ->searchable(['customer_name', 'telephone_number'])
                    ->label('Customer')
                    ->required(),

                DatePicker::make('payment_date')
                    ->label('Tanggal Pembayaran')
                    ->nullable(),

                Select::make('order_source')
                    ->label('Order Source')
                    ->options([
                        'whatsapp' => 'WhatsApp',
                        'meeting' => 'Meeting',
                        'other' => 'Other',
                        'po' => 'Purchase Order',
                    ])
                    ->nullable()  // Makes this field optional
                    ->placeholder('Select order source')
                    ->required()
                    ->live(),  // Set to required if needed

                // PO Number: Text input
                    TextInput::make('po_number')
                    ->label('PO Number')
                    ->nullable()  // Makes this field optional
                    ->placeholder('Enter PO number')
                    ->visible(fn (callable $get) => $get('order_source') === 'po'), // Visible only if order_source is 'po'

                FileUpload::make('payment_picture')
                    ->image()
                    ->imageEditor()
                    ->downloadable()
                    ->multiple()
                    ->fetchFileInformation(false)
                    ->label('Payment Picture')
                    ->directory('private/documents') // Store in storage/app/private/documents
                    ->visibility('private') // Make it private
                    ->disk('local') // Use the local disk
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                    ->maxSize(2048)
                    ->columnSpanFull()
                    ->openable(),

            
                    // Grid::make(2)->schema([
                    //     Select::make('province_id')
                    //         ->relationship('province', 'name')
                    //         ->label('Province')
                    //         ->searchable()
                    //         ->live(),
                    
                    //     Select::make('regency_id')
                    //         ->relationship('regency', 'name')
                    //         ->label('Regency')
                    //         ->searchable()
                    //         ->live()
                    //         ->rules([
                    //             fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                    //                 $exists = \App\Models\Regency::query()
                    //                     ->where('id', $value)
                    //                     ->where('province_id', $get('province_id'))
                    //                     ->exists();
                    
                    //                 if (! $exists) {
                    //                     $fail('The selected regency does not belong to the selected province.');
                    //                 }
                    //             }
                    //         ]),
                    
                    //     Select::make('district_id')
                    //         ->relationship('district', 'name')
                    //         ->label('District')
                    //         ->searchable()
                    //         ->live()
                    //         ->rules([
                    //             fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                    //                 $exists = \App\Models\District::query()
                    //                     ->where('id', $value)
                    //                     ->where('regency_id', $get('regency_id'))
                    //                     ->exists();
                    
                    //                 if (! $exists) {
                    //                     $fail('The selected district does not belong to the selected regency.');
                    //                 }
                    //             }
                    //         ]),
                    
                    //     Select::make('village_id')
                    //         ->relationship('village', 'name')
                    //         ->label('Village')
                    //         ->searchable()
                    //         ->live()
                    //         ->rules([
                    //             fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                    //                 $exists = \App\Models\Village::query()
                    //                     ->where('id', $value)
                    //                     ->where('district_id', $get('district_id'))
                    //                     ->exists();
                    
                    //                 if (! $exists) {
                    //                     $fail('The selected village does not belong to the selected district.');
                    //                 }
                    //             }
                    //         ]),
                    // ]),
            ])
            ->columnSpan(2) ,
            Repeater::make('MasterOrder')
                ->label('List of Orders')
                ->columnSpan(2) // 👈 This makes it span 2 columns in a grid layout
                ->relationship('orders') // assuming hasMany('orders')
                ->schema([
                
                Section::make('Customer Details')
                    ->schema([
                        TextInput::make('customer_name')
                            ->label('Nama Customer')
                            ->required()
                            ->maxLength(255),


                            Grid::make(2)->schema([
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
                                ->afterStateUpdated(fn ($set) => $set('address', null)) // ✅ Reset Address ]),
                                ]),

                        Textarea::make('address')
                            ->label('Alamat Customer')
                            ->rows(3)
                            ->required(),
                    ]),

                    Forms\Components\Section::make('Product and Delivery Details')
                    ->schema([

                        Forms\Components\Grid::make(2) // 💡 Two-column layout inside the section
                        ->schema([

                            Repeater::make('orderProducts')
                            ->label('Order Products')
                            ->relationship('orderProducts') // ✅ Correct way to handle the relationship
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name') // ✅ Ensure it references the correct Product relationship
                                    ->required()
                                     ->live(onBlur: true),
                        
                                TextInput::make('qty')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required()
                                        ->live(onBlur: true)
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
                                        ->live(onBlur: true)
                                    ->debounce(500) // Adds a 500ms delay before triggering the calculation
                                    ->minValue(0)
                                    ->prefix('Rp')
                                    ->afterStateUpdated(function (?string $state, callable $set) {
                                        $clean = preg_replace('/[^0-9]/', '', $state);
                                        $set('product_price', $clean);
                                    }),
        
                            ])
                            ->reactive()
                                ->live(onBlur: true)
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
                                    ->label('Kartu Keluarga')
                                    ->disk('local')
                                    ->directory('private/documents')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->openable()
                                    ->fetchFileInformation(false)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf']) // accepts JPG, PNG, PDF
                                    ->maxSize(2048) // in kilobytes (2MB)
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
            
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([ 
            TextColumn::make('id')->label('Order ID')->sortable(),
            TextColumn::make('customer.customer_name')
            ->label('Customer Name'),
            TextColumn::make('order_date')->date(),
             TextColumn::make('customer.telephone_number')
                ->label('Customer Phone')
                ->url(fn ($record) => 
                    'https://wa.me/' . preg_replace(
                        '/[^0-9]/', 
                        '', 
                        $record->customer->phone_code . $record->customer->telephone_number
                    )
                )
                ->openUrlInNewTab()
                ->formatStateUsing(fn ($state) => $state),
            TextColumn::make('orders_count')
                ->label('Customer Count')
                ->counts('orders'),
            TextColumn::make('payment_date')
                ->label('Date of Payment'),
            
            TextColumn::make('payment_verified')
                ->label('Payment Status')
                ->badge()
                ->formatStateUsing(fn (bool $state): string => $state ? 'verified' : 'not_verified')
                ->color(fn (bool $state): string => $state ? 'success' : 'gray'),

                TextColumn::make('total_purchase')
                ->label('Total Purchase')
                ->money('IDR') // or use formatStateUsing() for custom formatting
                ->sortable(),
            
        ])
        ->defaultSort('order_date', 'desc')
        
        ->actions([
            ActionGroup::make([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
                Actions\Action::make('Generate PI PDF')
                    ->url(fn (OrderMaster $record): string => route('order.pi.pdf', $record->id))
                    ->icon('heroicon-o-pencil')
                    ->color('success')
                    ->requiresConfirmation()
                    ->label('PI')
                    ->visible(fn (OrderMaster $record): bool => $record->all_orders_have_stage_greater_than_two),
        
                Actions\Action::make('Generate SD PDF')
                    ->url(fn (OrderMaster $record): string => route('order.sd.pdf', $record->id))
                    ->icon('heroicon-o-pencil')
                    ->color('success')
                    ->requiresConfirmation()
                    ->label('SD')
                    ->visible(fn (OrderMaster $record): bool => $record->all_orders_have_stage_greater_than_two),

            ])->iconButton(), // Make sure the iconButton is placed correctly here
        ])
        ->bulkActions([
            Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOrderMasters::route('/'),
            'create' => Pages\CreateOrderMaster::route('/create'),
            'edit' => Pages\EditOrderMaster::route('/{record}/edit'),
        ];
    }
}
