<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;


use Google\Cloud\DocumentAI\V1\Client\DocumentProcessorServiceClient;
use Google\Cloud\DocumentAI\V1\RawDocument;
use Google\Cloud\DocumentAI\V1\ProcessRequest;
use App\Models\DocumentResponse;
use App\Models\Order;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;


class ProcessDocumentBatchJob implements ShouldQueue
{
    use Queueable;

   protected $records;

    public function __construct(Collection $records)
    {
        $this->records = $records;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $projectId = 'sixth-env-463906-h7';

        $location = 'us';

        foreach($this->records as $record){

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
                        'extracted_confidence' => $confidence,
                        'extracted_normalized_value' => $normalizedValue,
                    ], $extractedFields)
                );

            }

        }

    }

    public function ktp (){
        //Check KTP name matching order_->customer->customer_name


    }

    public function skt (){
        //Check SKT land title

        //Check SKT name matching KTP name

        //Check SKT land address matching order->address

        //Check SKT land size matching order->orderProducts->qty

    }

    public function sp (){
        //Check SP name matching order->customer->customer_name

        //Check SP land address matching order->address

        //Check SP quantity matching order->orderProducts->qty

        //Check SP signature matching KTP name

        //Check SP land area matching skt->land aread
    }
}