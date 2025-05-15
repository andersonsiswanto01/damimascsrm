<ul class="space-y-2">
   <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-4 py-2 border-b">Customer ID</th>
                <th class="px-4 py-2 border-b">Company Name</th>
                <th class="px-4 py-2 border-b">Quantity </th>
            </tr>
        </thead>
        <tbody>

            @foreach ($this->topBuyers as $buyer)
                <tr>
                    <td class="px-4 py-2 border-b">{{ $buyer['customer_id'] ?? 'Unknown ID' }}</td>
                        <td class="px-4 py-2 border-b">{{ $buyer['company_name'] ?? 'Unknown Commpany' }}</td>
                    <td class="px-4 py-2 border-b">{{ number_format($buyer['quantity']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
    
</ul>