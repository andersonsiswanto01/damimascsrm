<?php

namespace App\Livewire;

use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PublicSalesForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public Order $order;

    public $orderHistories;

    public function mount(Order $order): void
    {
        $this->order = $order;

        $order->load('customer', 'orderProducts'); // ✅ Load relationships

         // Get order history using the show() function
        $this->orderHistories = $this->show($order->id);  // Show the order history

        
        $this->form->fill([
            'customer_id' => $order->customer->customer_name, // ✅ Correct
            'delivery_date' => $order->delivery_date,
            'payment_date' => $order->payment_date,
            // 'orderProducts' => $order->orderProducts->map(fn ($product) => [
            //     'product_id' => $product->product_id,
            //     'qty' => $product->qty,
            // ])->toArray(),
            'payment_photo' => $order->payment_photo,
            'landcertificate_photo' => $order->landcertificate_photo,
            'statementletter_photo' => $order->statementletter_photo,
        ]);

       
    }

    public function show($orderId)
    {
        $order = Order::find($orderId); // Find the order by ID
        $orderHistories = $order->order_stage_histories()->with('stage')->orderBy('created_at', 'asc')->get(); // Get the order stage histories
    
        return $orderHistories; // Return the histories (or any other data you want to return)
    }

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make('customer_id')
                    ->label('Customer')
                    ->readOnly()
                    ->extraAttributes(['class' => 'rounded-lg']),


                DatePicker::make('delivery_date')
                    ->label('Delivery Date')
                    ->readOnly()
                    ->extraAttributes(['class' => 'rounded-lg']),

                DatePicker::make('payment_date')
                    ->label('Payment Date')
                    ->readOnly()
                     ->extraAttributes(['class' => 'rounded-lg']),

                // Repeater::make('orderProducts')
                //     ->label('Order Products')
                //     ->schema([
                //         Select::make('product_id')
                //             ->label('Product')
                //             ->relationship('product', 'name')
                //             ->disabled(),

                //         TextInput::make('qty')
                //             ->numeric()
                //             ->disabled(),
                //     ])
                //     ->columns(2)
                //     ->disabled(),

                FileUpload::make('payment_photo')
                    ->image()
                    ->label('Payment Proof')
                    ->disk('local')
                    ->visibility('private')
                    ->directory('private/payment')
                    ->nullable()
                    ->default($this->order->payment_photo),

                FileUpload::make('landcertificate_photo')
                    ->image()
                    ->label('Land Certificate')
                    ->disk('local')
                    ->visibility('private')
                    ->nullable()
                    ->directory('private/documents')
                    ->multiple()
                    ->default($this->order->landcertificate_photo),

                FileUpload::make('statementletter_photo')
                    ->image()
                    ->label('Statement Letter')
                    ->directory('private/documents')
                    ->disk('local')
                    ->visibility('private')
                    ->nullable()
                    ->default($this->order->statementletter_photo),
            ])
            ->statePath('data');
    }

    public function render(): View
    {
        return view('livewire.public-sales-form', [
            'order' => $this->order->load('orderProducts.product'),
            'orderHistories' => $this->orderHistories, // Eager load orderProducts and their associated product
        ]);
    }
}
