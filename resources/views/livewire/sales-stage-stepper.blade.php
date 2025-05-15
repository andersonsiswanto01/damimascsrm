<div>
<ol class="flex flex-wrap items-center justify-between w-full p-4 space-x-2 text-sm font-medium text-gray-500 bg-white border border-gray-200 rounded-lg shadow-sm dark:text-gray-400 sm:text-base dark:bg-gray-800 dark:border-gray-700 sm:p-4 sm:space-x-4 rtl:space-x-reverse">
@foreach ($salesStages as $index => $stage)
    <li class="flex items-center {{ $stage->id == $currentStageId ? 'text-blue-600 dark:text-blue-500' : 'text-gray-500 dark:text-gray-400' }}">
        <button wire:click="updateStage({{ $stage->id }})"
            class="flex items-center justify-center w-6 h-6 me-2 text-xs font-bold border {{ $stage->id == $currentStageId ? 'border-blue-600 bg-blue-100 dark:border-blue-500 dark:bg-blue-900' : 'border-gray-500 bg-gray-100 dark:border-gray-400 dark:bg-gray-700' }} rounded-full shrink-0">
            {{ $index + 1 }}
        </button>
        <span class="whitespace-nowrap">
            {{ $stage->name }}
        </span>

        @if (!$loop->last)
            <svg class="w-4 h-4 ms-2 sm:ms-4 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 9 4-4-4-4M1 9l4-4-4-4"/>
            </svg>
        @endif
    </li>
@endforeach
</ol>

</div>
