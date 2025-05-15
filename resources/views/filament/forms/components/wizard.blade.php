<div class="p-4 bg-white rounded-lg shadow-md w-full">
    <div class="flex flex-col space-y-2">
   
    {{ dd($record) }}
    {{-- Customer Name --}}
        <div class="text-lg font-semibold text-gray-900">
            {{ $record->customer_name }}
        </div>

        {{-- Telephone Number --}}
        <div class="text-sm text-gray-600">
            📞 {{ $record->telephone }}
        </div>

        {{-- Status Badge --}}
        <div>
            <span class="px-3 py-1 text-xs font-semibold text-white rounded-full"
                  style="background-color: {{ match($record->status) {
                      'active' => '#4CAF50', /* Green */
                      'inactive' => '#F44336', /* Red */
                      'pending' => '#FF9800', /* Orange */
                      default => '#9E9E9E', /* Gray */
                  } }}">
                {{ ucfirst($record->status) }}
            </span>
        </div>
    </div>
</div>
