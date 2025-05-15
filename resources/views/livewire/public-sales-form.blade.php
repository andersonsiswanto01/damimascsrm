<div class="">
    <div x-init="initFlowbite();"></div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
  
    <div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row w-full max-w-7xl gap-8">
        
            <!-- Left: Customer Form (100% on mobile, 50% on desktop) -->
            <form wire:submit="create" class="w-full md:w-1/2 p-8 bg-white border border-gray-300 shadow-lg rounded-lg">
                <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Fill Form</h2>
        
                <div class="space-y-5">
                    {{ $this->form }}
                </div>
        
                <div class="mt-6 flex justify-center">
                    <button type="submit"
                        class="w-full px-6 py-2 text-white font-semibold rounded-lg shadow-md transition duration-300"
                        style="background-color: rgb(237, 28, 36);">
                        Submit
                    </button>
                </div>
            </form>
        
            <!-- Right: Order Process Timeline and Product Table (100% on mobile, 50% on desktop) -->
            <div class="w-full md:w-1/2 space-y-8">
                
                <!-- Top: Order Process Timeline -->
                <div class="p-8 bg-white border border-gray-300 shadow-lg rounded-lg">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Order Process Timeline</h2>
        
                    <ol class="relative border-s border-gray-200 dark:border-gray-700">
                        @foreach($orderHistories as $history)
                            <li class="mb-10 ms-4">
                                <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                
                                <!-- Time (created_at or any other relevant time) -->
                                <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">
                                    {{ $history->created_at->format('F d, Y h:i:s A') }} <!-- Full date and time --> <!-- Format date as per your needs -->
                                </time>
                    
                                <!-- Title of the stage -->
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $history->stage->name }} <!-- Assuming you have a relationship to get the stage name -->
                                </h3>
                    
                                <!-- Notes (This will be the reason/description of the status) -->
                                <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                                    {{ $history->note }}
                                </p>
                            </li>
                        @endforeach
                    </ol>
                    
                </div>
        
                <!-- Bottom: Product Table -->
                <div class="p-8 bg-white border border-gray-300 shadow-lg rounded-lg">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Product List</h2>
        
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3">Number</th>
                                    <th scope="col" class="px-6 py-3">Product Name</th>
                                    <th scope="col" class="px-6 py-3">Quantity</th>
                                    <th scope="col" class="px-6 py-3">Price</th>
                                    <th scope="col" class="px-6 py-3">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $grandTotal = 0; @endphp
                            
                                @foreach ($order->orderProducts as $orderProduct)
                                    @php 
                                        $total = $orderProduct->qty * $orderProduct->product_price;
                                        $grandTotal += $total;
                                    @endphp
                            
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $orderProduct->product->name }}</td>
                                        <td class="px-6 py-4">{{ $orderProduct->qty }}</td>
                                        <td class="px-6 py-4">{{ number_format($orderProduct->product_price, 2) }}</td>
                                        <td class="px-6 py-4">{{ number_format($total, 2) }}</td>
                                    </tr>
                                @endforeach
                            
                                <!-- Total Row -->
                                <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                                    <td colspan="4" class="px-6 py-4 text-end">Grand Total</td>
                                    <td class="px-6 py-4">{{ number_format($grandTotal, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    
    
    

    <x-filament-actions::modals />
</div>
