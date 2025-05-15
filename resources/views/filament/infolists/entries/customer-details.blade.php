<div>
<div x-data="{ show: false, imageUrl: '' }">

    <p>TEST</p>
    <!-- Customer Details -->
    <div class="p-4 bg-white shadow rounded-lg">
        <h3 class="text-lg font-bold">Customer Details</h3>
        <p><strong>Name:</strong> {{ $record->customer_name }}</p>
        <p><strong>Company:</strong> {{ $record->company_name }}</p>
        <p><strong>Status:</strong> {{ $record->status }}</p>
    </div>

    <!-- Fullscreen Image Modal -->
    <div x-show="show" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50">
        <div class="relative">
            <img :src="imageUrl" class="max-w-full max-h-screen">
            <button @click="show = false" class="absolute top-4 right-4 text-white text-2xl">&times;</button>
        </div>
    </div>
</div>

<!-- Alpine.js Modal Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        window.openImageModal = function (url) {
            let modalData = document.querySelector('[x-data]').__x.$data;
            modalData.show = true;
            modalData.imageUrl = url;
        }
    });
</script>
</div>