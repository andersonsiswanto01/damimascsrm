<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderDocumentVerificationResource\Pages;
use App\Filament\Resources\OrderDocumentVerificationResource\RelationManagers;
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
use Google\Cloud\DocumentAI\V1\Client\DocumentProcessorServiceClient;
use Google\Cloud\DocumentAI\V1\RawDocument;
use Google\Cloud\DocumentAI\V1\ProcessRequest;
use Filament\Tables\Actions\BulkAction;
use App\Models\DocumentResponse;
use App\Jobs\ProcessDocumentBatchJob;

class OrderDocumentVerificationResource extends Resource
{
    
    

    protected static ?string $navigationIcon = 'heroicon-s-document-check';
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
          Action::make('AI')
            ->label('AI Assistance')
            ->icon('heroicon-o-light-bulb')
            ->color('primary')
            ->requiresConfirmation()
            ->action(function ($record) {

        ////
# TODO(developer): Update the following lines before running the sample.
# Your Google Cloud Platform project ID.
$projectId = 'sixth-env-463906-h7';

# Your Processor Location.
$location = 'us';


# Path for the file to read.
$documents = [
    [
        'path' => storage_path('app/private/' . $record->KTP_photo[0]),
        'processorId' => '509449d8efd58984',
        'data_type' => 'ktp',
    ],
    [
        'path' => storage_path('app/private/' . $record->landcertificate_photo[0]),
        'processorId' => 'a939ffebd3660f3',
        'data_type' => 'skt',
    ],
    [
        'path' => storage_path('app/private/' . $record->statementletter_photo[0]),
        'processorId' => '1b981851cd250e88',
        'data_type' => 'sp',
    ],
];


// Loop through each document
foreach ($documents as $type => $config) {
    $path = $config['path'];
    if (!file_exists($path)) {
        logger()->warning("File not found for $type: $path");
        continue;
    }

    $mime = mime_content_type($path); // Auto-detect mime

    $contents = file_get_contents($path);

    $rawDocument = (new RawDocument())
        ->setContent($contents)
        ->setMimeType($mime);

    $processorId = $config['processorId'];

    
  $options = [
    'credentials' => storage_path('app/service-account.json'),
];

    $client = new DocumentProcessorServiceClient([
          'transport' => 'rest',
          'credentials' => $options['credentials'],
    ]);

    $fullProcessorName = $client->processorName($projectId, $location, $processorId);

  
    $request = (new ProcessRequest())
        ->setName($fullProcessorName)
        ->setRawDocument($rawDocument);

    $response = $client->processDocument($request);

    $document = $response->getDocument(); 
    $entities = $document->getEntities();  

    $entitiesData = [];

    foreach ($entities as $entity) {
    $type = $entity->getType(); // e.g., "name", "nik", "alamat"
    $value = $entity->getMentionText(); // The actual extracted value
    $confidence = $entity->getConfidence(); // (float) 0.0 - 1.0
    $normalizedValue = $entity->hasNormalizedValue()
        ? $entity->getNormalizedValue()->serializeToJsonString()
        : null;

    $entitiesData[$type] = [
        'value' => $value,
        'confidence' => $confidence,
        'normalized_value' => $normalizedValue,
    ];
    }
    $client->close();
    
    $extractedFields = [];

    if ($config['data_type'] == 'ktp') {

        $extractedFields = [
            'extracted_religion' => $entitiesData['agama']['value'] ?? null,
            'extracted_address' => $entitiesData['alamat']['value'] ?? null,
            'extracted_gender' => $entitiesData['jenis_kelamin']['value'] ?? null,
            'extracted_name' => $entitiesData['nama']['value'] ?? null,
            'extracted_nik' => $entitiesData['NIK']['value'] ?? null,
            'extracted_job' => $entitiesData['pekerjaan']['value'] ?? null,
            'extracted_marriage_status' => $entitiesData['status_perkawinan']['value'] ?? null,
            'extracted_birth' => $entitiesData['tanggal_lahir']['value'] ?? null,
            'extracted_birth_place' =>$entitiesData['tempat_lahir']['value'] ?? null,
        ];
    
    } elseif ($config['data_type'] == 'skt') {
    
        $extractedFields = [
            'extracted_religion' => $entitiesData['agama']['value'] ?? null,
            'extracted_address' => $entitiesData['alamat']['value'] ?? null,
            'extracted_origin_skt' => $entitiesData['desa_SKT']['value'] ?? null,
            'extracted_land_address' => $entitiesData['di_desa']['value'] ?? null,
            'extracted_title' => $entitiesData['judul']['value'] ?? null,
            'extracted_land_size' => $entitiesData['luas_tanah']['value'] ?? null,
            'extracted_name' => $entitiesData['nama']['value'] ?? null,
            'extracted_nik' => $entitiesData['NIK']['value'] ?? null,
            'extracted_birth' => $entitiesData['tempat_tanggal_lahir']['value'] ?? null,
            'extracted_date' => isset($entitiesData['date']['value']) ? \Carbon\Carbon::parse($entitiesData['date']['value']) : null,
        ];

    } elseif ($config['data_type'] == 'sp') {

         $extractedFields = [
            'extracted_address' => $entitiesData['alamat_pemohon']['value'] ?? null,
            'extracted_land_address' => $entitiesData['di_desa']['value'] ?? null,
            'extracted_qty' => $entitiesData['qty']['value'] ?? null,
            'extracted_name' => $entitiesData['nama']['value'] ?? null,
            'extracted_nik' => $entitiesData['NIK']['value'] ?? null,
            'extracted_signature' => $entitiesData['tanda_tangan']['value'] ?? null,
            'extracted_date' => isset($entitiesData['date']['value']) ? \Carbon\Carbon::parse($entitiesData['date']['value']) : null,
        ];
    }


   DocumentResponse::updateOrCreate(
    [   
        'order_id' => $record->id,
        'data_type' => $config['data_type'],
    ],
    array_merge([
       
    ], $extractedFields)
    );

}

    }),
            Action::make('accept_document')
                ->label('Verify Document')
                ->color('success')
                ->icon('heroicon-m-check-circle')
                ->requiresConfirmation()
                ->action(function ($record) {

                    // First increment: Confirm document verification
                    $record->incrementOrderStage('confirmed! Verifying Payment');
                    // Check related orderMaster payment_status
                    if ($record->orderMaster->payment_verified == 1) {
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
                    
                    FileUpload::make('kartu_keluarga')
                        ->image()
                        ->label('Kartu Keluarga')
                        ->disk('local')
                        ->directory('private/documents')
                        ->visibility('private')
                        ->downloadable()
                        ->openable()
                        ->fetchFileInformation(false)
                        ->multiple()
                        ->default($record->kartu_keluarga ?? []), // Ensure it's an array if multiple
        
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
        ])
        ->bulkActions([
                    BulkAction::make('AI Analysis')
                        ->label('AI Analysis')
                        ->icon('heroicon-o-light-bulb')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                                // Dispatch all grouped documents with metadata
                                ProcessDocumentBatchJob::dispatch($records);
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
