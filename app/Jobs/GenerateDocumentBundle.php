<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use App\Notifications\NewOrderNotification;
use App\Models\User;
use ZipArchive;


class GenerateDocumentBundle implements ShouldQueue
{
     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $orderIds;

    protected $user;

    /**
     * Create a new job instance.
     */
      public function __construct(Collection $records, User $user)
    {
        $this->orderIds = $records->pluck('id')->toArray();
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
     public function handle(): void
    {
        $orders = Order::with([
            'orderMaster.customer.corporateSp2bks',
            'district',
            'village',
            'regency',
            'province',
            'orderProducts.product',
        ])->whereIn('id', $this->orderIds)->get();

        dd($orders);

        $pdf = Pdf::loadView('pdf.order-documents', [
            'orders' => $orders,
        ]);

        $fileName = 'order_documents_' . now()->format('Ymd_His') . '.pdf';
        $pdfPath = "public/{$fileName}";

        Storage::disk('public')->put($fileName, $pdf->output());

        // Optional: You could notify a user or broadcast here.


        // Step 2: Check if any order has corporate/breeder customer
        
        //checking if they have corporate or breeder status
        $hasCorporateOrBreeder = $orders->contains(function ($order) {
            $status = $order->orderMaster?->customer?->status;
            return in_array($status, ['corporate', 'breeder']);
        });

        //Creating the zip for created file previously
        if ($hasCorporateOrBreeder) {
        $zipFileName = 'List_DO_' . now()->format('Ymd_His') . '.zip';
        $zipFilePath = storage_path("app/public/{$zipFileName}");

        $zip = new ZipArchive;
            
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
        $zip->addFile($pdfFullPath, $fileName); // 2nd param is the name inside the zip
        
        
        //Check for each orders
        foreach ($orders as $order) {
        
            $customer = $order->orderMaster?->customer;

        if (!$customer) {
            continue;
        }

        if (in_array($customer->status, ['corporate', 'breeder'])) {
            $sp2bksPath = $customer->corporateSp2bks->sp2bks_file;

            if ($sp2bksPath && str_ends_with(strtolower($sp2bksPath), '.pdf')) {
                $fullPath = storage_path('app/private/' . $sp2bksPath);

                if (file_exists($fullPath)) {
                    // Add to zip, optionally prefix with customer name or ID
                    $zip->addFile($fullPath, 'sp2bks/' . basename($sp2bksPath));
                }
            }
        }
        }
        
        $zip->close();
        }

        // Optional: Clean up individual PDFs
        foreach ($pdfFiles as $pdfPath) {
            unlink($pdfPath); // Remove individual PDF
        }

        $recipient = $this->user;

            Notification::make()
            ->title('Saved successfully')
            ->body('The document bundle has been generated and saved successfully. You can download ONCE it using the link below.')
            ->actions([
                Action::make('Download PDF')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->button()
                    ->markAsRead()
                    ->url(url("/download/{$fileName}"), shouldOpenInNewTab: true),
            ])
    ->sendToDatabase($recipient);
    }
}
}