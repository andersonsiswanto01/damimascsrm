<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CompileOrderDocuments implements ShouldQueue
{
      use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $orders = Order::with(['orderMaster.user'])->where('order_stage_id', 5)->get();

        $allHtml = '';

        foreach ($orders as $order) {
            $allHtml .= view('pdf.order-documents', [
                'orders' => $order,
            ])->render();
        }

        $pdf = Pdf::loadHTML($allHtml)->setPaper('a4');

        // Store the PDF
        $filename = 'compiled-orders-' . now()->format('Ymd_His') . '.pdf';
        Storage::put("public/compiled/{$filename}", $pdf->output());

        // Optional: email, notification, or link for download
    }
}
