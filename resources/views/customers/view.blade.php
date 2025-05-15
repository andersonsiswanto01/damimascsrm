<x-filament::page>
    <div class="p-6">
        <h2 class="text-xl font-bold">Customer Details</h2>
        <p><strong>Name:</strong> {{ $customer->customer_name }}</p>
        <p><strong>Company:</strong> {{ $customer->company_name }}</p>
        <p><strong>Status:</strong> {{ $customer->status }}</p>
        <p><strong>Telephone:</strong> {{ $customer->telephone_number }}</p>
        <p><strong>Registered:</strong> {{ $customer->registration_date }}</p>
        <p><strong>Province:</strong> {{ $customer->province->name ?? 'N/A' }}</p>
    </div>
</x-filament::page>