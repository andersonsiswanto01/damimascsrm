<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use App\Models\OrderMaster;
use Nasution\Terbilang;
use Illuminate\Support\Facades\Storage;


class OrderPdfController extends Controller
{

    public $productDetails = [];
    protected function authentication($order_stage_id)
    {
        if ($order_stage_id == 1) {
            abort(403, 'Unauthorized action');
        }
    }

    public function generatepi(Order $order)
    {

        // dd($order);

        $this->authentication($order->order_stage_id);
        $order->load(['customer', 'orderProducts.product', 'orderMaster.customer']);
        $customerName = $order->orderMaster->customer->customer_name;
        $orderProducts = $order->orderProducts;


        $totalQty = $orderProducts->sum('qty');
        $totalPrice = $orderProducts->sum(function ($orderProducts) {
            return $orderProducts->qty * $orderProducts->product->price;
        });
        $totalPriceTerbilang = ucwords(Terbilang::convert($totalPrice));

        foreach ($order->orderProducts as $orderProduct) {

            $this->productDetails[] = [
                'name' => $orderProduct->product->name, // or 'name' depending on your column name
                'price' => $orderProduct->product_price,
                ];
            }


        $productPriceFormarted = number_format(
            $orderProducts->sum(function ($orderProduct) {
                return $orderProduct->product->price;
            }),
            2, ',', '.'
        );
        $totalPriceFormarted = number_format(
            $orderProducts->sum(function ($orderProduct) {
                return $orderProduct->qty * $orderProduct->product->price;
            }),
            2, ',', '.'
        );
        
        $companyName = $order->orderMaster->customer->company_name;

        $legalForm = $order->orderMaster->customer->legal_form;

        $formattedLegalForm = match ($legalForm) {
            'pt_tbk' => 'PT ' . $companyName . ' Tbk',
            'pt' => 'PT ' . $companyName,
            'koperasi' => 'Koperasi ' . $companyName,
            'cv' => 'CV ' . $companyName,
            'ud' => 'UD ' . $companyName,
             default => $legalForms[$legalForm] . ' ' . $companyName,
        };
        
        
        // Step 1: Define the path to your private image (e.g., signature image)
        $imagePath = storage_path('app/private/signature/musachandra.png');

        // Step 2: Read the image file from the given path and encode it to base64
        // This converts the image into a base64 encoded string, which can be embedded in HTML
        $imageData = base64_encode(file_get_contents($imagePath));

        // Step 3: Extract the file extension (like png, jpg, etc.) so we can use it in the data URI
        $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);

        // Step 4: Combine the file extension and the base64 encoded image data to create a data URI
        // This will embed the image directly into the HTML or PDF, without requiring a file URL
        $signatureBase64 = 'data:image/' . $imageType . ';base64,' . $imageData;

        // Step 5: Use this base64 string in your Blade view (or wherever you need to display the image)
        $data = [
            'orderNumber' => $order->id . '/DIR/SUS/' . $order->created_at->format('m') . '/' . $order->created_at->format('Y'),
            'customerName' => $customerName,
            'qty' => $totalQty,
            'totalPrice' => $totalPriceFormarted,
            'totalPriceTerbilang' => $totalPriceTerbilang,
            'date' => $order->created_at->translatedFormat('d F Y'),
            'productDetails' => $this->productDetails,
            'corporate' => $formattedLegalForm,
            'regency'=> $order->orderMaster->customer->regency->name,
            'province'=> $order->orderMaster->customer->province->name,
            'signature' => $signatureBase64,
        ];

        if ($order->orderMaster->customer->status === 'corporate') {
            $pdf = Pdf::loadView('pdf.picorporate', $data)
                ->setPaper('A4', 'portrait');
        } elseif (in_array($order->orderMaster->customer->status, ['private', 'breeder'])) {
            $pdf = Pdf::loadView('pdf.piprivate', $data) // <- note: you probably meant piprivate here
                ->setPaper('A4', 'portrait');
        } else {
            abort(404, 'Customer type not supported');
        }
      

        return $pdf->stream('PI_' . $order->orderMaster->customer->customer_name . '.pdf');
    }

    public function generatesd(OrderMaster $order)
    {
        $order->load('orders.orderProducts','customer');
        $this->authentication($order->order_stage_id);
        $customerName = $order->customer->customer_name;

        
        $productDetails = [];
        $orderDetails = [];
        $grandTotal = 0;
        $grandQty = 0.0;

        $order_source = $order->invoice_statement;

        foreach ($order->orders as $singleOrder) {
            $totalQty = 0;
            $totalPrice = 0;
        
            foreach ($singleOrder->orderProducts as $orderProduct) {
                $subtotal = $orderProduct->qty * $orderProduct->product_price;
                $totalQty += $orderProduct->qty;
                $totalPrice += $subtotal;
        
                // Optionally keep detailed product info per product
                $productDetails[] = [
                    'order_id' => $singleOrder->id,
                    'product_name' => $orderProduct->product->name ?? '-',
                    'qty' => $orderProduct->qty,
                    'price' => $orderProduct->product_price,
                    'subtotal' => $subtotal,
                    'proformainvoice_id' => sprintf(
                        '%04d/SUS/%d/%d',
                        $singleOrder->id,
                        $singleOrder->created_at->format('n'),
                        $singleOrder->created_at->format('Y')
                    ),
                    'delivery_date' => $singleOrder->delivery_date,
                   
                ];
            }
        
            // Summing up total across all orders
            $grandQty += $totalQty;
            $grandTotal += $totalPrice;
            $grandTotalFormatted =  number_format($grandTotal, 0, ',', '.');

            // dd($grandTotalFormatted);
        
            $orderDetails[] = [
                'order_id' => $singleOrder->id,
                'paymentdate' => $singleOrder->payment_date,
                'order_number' => $singleOrder->id . '/DIR/SUS/' . $singleOrder->created_at->format('m') . '/' . $singleOrder->created_at->format('Y'),
                'delivery_date' => $singleOrder->delivery_date,
                'grandTotalOrder' => $totalPrice,
                'grandTotalQtyOrder' => $totalQty,
                'payment_due' => $order->created_at->addDays(30),
            ];
        }

        $grouped = collect($productDetails)
            ->groupBy('product_name')
            ->map(function ($items, $productName) {
                $totalQty = $items->sum('qty');
                $totalPrice = $items->sum(function ($item) {
                    return $item['price'] * $item['qty'];
                });
                $totalSubtotal = $items->sum('subtotal');
                $averagePrice = $totalPrice / $totalQty;

                return [
                    'product_name' => $productName,
                    'total_qty' => $totalQty,
                    'average_price' => round($averagePrice, 2),
                    'subtotal' => $totalSubtotal,
                ];
            })->values()->all();


        $totalPriceTerbilang = ucwords(Terbilang::convert($grandTotal));

        $companyName = $order->customer->company_name;

        $legalForm = $order->customer->legal_form ?? '';

        $formattedLegalForm = match ($legalForm) {
            'pt_tbk' => 'PT ' . $companyName . ' Tbk',
            'pt' => 'PT ' . $companyName,
            'koperasi' => 'Koperasi ' . $companyName,
            'cv' => 'CV ' . $companyName,
            'ud' => 'UD ' . $companyName,
            default    => ucfirst($legalForm) . ' ' . $companyName, // fallback with capitalized legal form
        };
        
        
        // Step 1: Define the path to your private image (e.g., signature image)
        $imagePath = storage_path('app/private/signature/musachandra.png');

        // Step 2: Read the image file from the given path and encode it to base64
        // This converts the image into a base64 encoded string, which can be embedded in HTML
        $imageData = base64_encode(file_get_contents($imagePath));

        // Step 3: Extract the file extension (like png, jpg, etc.) so we can use it in the data URI
        $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);

        // Step 4: Combine the file extension and the base64 encoded image data to create a data URI
        // This will embed the image directly into the HTML or PDF, without requiring a file URL
        $signatureBase64 = 'data:image/' . $imageType . ';base64,' . $imageData;

          // Step 5: Use this base64 string in your Blade view (or wherever you need to display the image)
          $data = [
            'orderNumber' => $order->id . '/DIR/SUS/' . $order->created_at->format('m') . '/' . $order->created_at->format('Y'),
            'customerName' => $customerName,
            'order_source' => $order_source,
            'qty' => $grandQty,
            'grandTotal' => $grandTotalFormatted,
            'productDetails' => $productDetails,
            'orderDetails' => $orderDetails,
            'totalPriceTerbilang' => $totalPriceTerbilang,
            'date' => $order->created_at->translatedFormat('d F Y'),
            'corporate' => $formattedLegalForm,
            'regency'=> $order->customer->regency->name,
            'province'=> $order->customer->province->name,
            'signature' => $signatureBase64,
            'grouped' => $grouped,
        ];

        if ($order->customer->status === 'corporate') {
            $pdf = Pdf::loadView('pdf.sdcorporate', $data)
                ->setPaper('A4', 'portrait');
        } elseif (in_array($order->customer->status, ['private', 'breeder'])) {
            $pdf = Pdf::loadView('pdf.sdprivate', $data) // <- note: you probably meant piprivate here
                ->setPaper('A4', 'portrait');
        } else {
            abort(404, 'Customer type not supported');
        }
        return $pdf->stream('SD_' . $order->customer->customer_name . '.pdf');
    }

        

    }


    // public function generatesd(OrderMaster $order)
    // {
    //     $order->load('orders.orderProducts');

    //     $productDetails = [];
    //     foreach ($order->orders as $singleOrder) {



            
    //         foreach ($singleOrder->orderProducts as $orderProduct) {
    //             $productDetails[] = [
    //                 'name' => $orderProduct->product->name,
    //                 'product_id' =>$orderProduct->product_id, // or 'name' depending on your column name
    //                 'price' => $orderProduct->product_price,
    //             ];
    //         }
    //         dd($productDetails);

    //         $order->load(['customer', 'orderProducts.product']);
        
    //     $this->authentication($order->order_stage_id);
    //     $order->load(['customer', 'orderProducts.product', 'orderMaster.customer']);
    //     $customerName = $order->orderMaster->customer->customer_name;
    //     $orderProducts = $order->orderProducts;
    //     $totalQty = $orderProducts->sum('qty');
    //     $totalPrice = $orderProducts->sum(function ($orderProducts) {
    //         return $orderProducts->qty * $orderProducts->product->price;
    //     });
    //     $totalPriceTerbilang = ucwords(Terbilang::convert($totalPrice));

    //     foreach ($order->orderProducts as $orderProduct) {

    //         $this->productDetails[] = [
    //             'name' => $orderProduct->product->name, // or 'name' depending on your column name
    //             'price' => $orderProduct->product_price,
    //             ];
    //         }


    //     $productPriceFormarted = number_format(
    //         $orderProducts->sum(function ($orderProduct) {
    //             return $orderProduct->product->price;
    //         }),
    //         2, ',', '.'
    //     );
    //     $totalPriceFormarted = number_format(
    //         $orderProducts->sum(function ($orderProduct) {
    //             return $orderProduct->qty * $orderProduct->product->price;
    //         }),
    //         2, ',', '.'
    //     );
        
    //     $companyName = $order->orderMaster->customer->company_name;

    //     $legalForm = $order->orderMaster->customer->legal_form;

    //     $formattedLegalForm = match ($legalForm) {
    //         'pt_tbk' => 'PT ' . $companyName . ' Tbk',
    //         'pt' => 'PT ' . $companyName,
    //         'koperasi' => 'Koperasi ' . $companyName,
    //         'cv' => 'CV ' . $companyName,
    //         'ud' => 'UD ' . $companyName,
    //          default => $legalForms[$legalForm] . ' ' . $companyName,
    //     };
        
        
    //     // Step 1: Define the path to your private image (e.g., signature image)
    //     $imagePath = storage_path('app/private/signature/musachandra.png');

    //     // Step 2: Read the image file from the given path and encode it to base64
    //     // This converts the image into a base64 encoded string, which can be embedded in HTML
    //     $imageData = base64_encode(file_get_contents($imagePath));

    //     // Step 3: Extract the file extension (like png, jpg, etc.) so we can use it in the data URI
    //     $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);

    //     // Step 4: Combine the file extension and the base64 encoded image data to create a data URI
    //     // This will embed the image directly into the HTML or PDF, without requiring a file URL
    //     $signatureBase64 = 'data:image/' . $imageType . ';base64,' . $imageData;

    //       // Step 5: Use this base64 string in your Blade view (or wherever you need to display the image)
    //       $data = [
    //         'orderNumber' => $order->id . '/DIR/SUS/' . $order->created_at->format('m') . '/' . $order->created_at->format('Y'),
    //         'customerName' => $customerName,
    //         'poNumber' => '058/TBS-Mdn/III/2025',
    //         'qty' => $totalQty,
    //         'price' => '9500',
    //         'totalPrice' => $totalPriceFormarted,
    //         'totalPriceTerbilang' => $totalPriceTerbilang,
    //         'date' => $order->created_at->translatedFormat('d F Y'),
    //         'productDetails' => $this->productDetails,
    //         'corporate' => $formattedLegalForm,
    //         'regency'=> $order->orderMaster->customer->regency->name,
    //         'province'=> $order->orderMaster->customer->province->name,
    //         'signature' => $signatureBase64,
    //     ];

    //     if ($order->orderMaster->customer->status === 'corporate') {
    //         $pdf = Pdf::loadView('pdf.sdcorporate', $data)
    //             ->setPaper('A4', 'portrait');
    //     } elseif (in_array($order->orderMaster->customer->status, ['private', 'breeder'])) {
    //         $pdf = Pdf::loadView('pdf.sdprivate', $data) // <- note: you probably meant piprivate here
    //             ->setPaper('A4', 'portrait');
    //     } else {
    //         abort(404, 'Customer type not supported');
    //     }
    //     return $pdf->stream('SD_' . $order->orderMaster->customer->customer_name . '.pdf');
    // }

        

    // }


