<?php

namespace App\Jobs;

use App\Models\User;
use Filament\Actions\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

class CompileOrderDocuments implements ShouldQueue
{
      use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(    public $records,public int $userId)
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        $user = User::find($this->userId);

        $orders = Order::with(['orderMaster.user'])->where('order_stage_id', 5)->get();
    
        $allHtml = '';

        foreach ($orders as $order) {
            $allHtml .= view('pdf.order-documents', [
                'order' => $order,
            ])->render();
        }

        $pdf = Pdf::loadHTML($allHtml)->setPaper('a4');

        // Store the PDF
        $filename = 'compiled-orders-' . now()->format('Ymd_His') . '.pdf';
        Storage::disk('public')->put("{$filename}", $pdf->output());


        Notification::make()
            ->title('Saved successfully')
            ->body('The document bundle has been generated and saved successfully. You can download ONCE it using the link below.')
            ->actions([
                Action::make('Download PDF')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->button()
                    ->markAsRead()
                    ->url(url("/download/{$filename}"), shouldOpenInNewTab: true),
            ])
    ->sendToDatabase($user);

    }
}
