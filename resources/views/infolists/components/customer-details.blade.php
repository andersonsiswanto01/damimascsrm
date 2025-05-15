<!-- Image Grid -->
<div class="grid grid-cols-3 gap-4">
    @foreach (['NPWP_photo', 'KTP_photo', 'SP2BKS_photo'] as $photo)
    @if (!empty($getRecord()->$photo)) 
        @php
            $filename = basename($getRecord()->$photo); // Ensure we only get the filename
            $photoUrl = route('customer.photo', ['filename' => $filename]);
        @endphp
        <a href="{{ $photoUrl }}" target="_blank">
            <img 
                src="{{ $photoUrl }}" 
                alt="Customer Photo"
                class="w-full h-auto rounded-lg shadow-md cursor-pointer transition-transform hover:scale-105">
        </a>   
    @endif
@endforeach

</div>
