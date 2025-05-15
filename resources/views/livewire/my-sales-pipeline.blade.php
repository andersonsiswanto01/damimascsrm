<div class="p-4 bg-white rounded shadow">
    <h2 class="text-xl font-bold">{{ $record->lead->name ?? $record->customer->customer_name ?? 'No Name' }}</h2>
    <p>{{ $record->lead->phone ?? $record->customer->telephone_number?? 'No Phone' }}</p>
    <p>Email: {{ $record->lead->email ?? 'No Email' }}</p>
</div>
