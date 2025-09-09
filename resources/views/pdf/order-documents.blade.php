<style>

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;

    }
     table, th, td {
        border: 1px solid #000; /* Show all borders */
    }
    td, th {
        padding: 5px;
        font-size: 14px;
        vertical-align: top;
    }
    .document {
        margin: 20px 0;
        page-break-inside: avoid;
    }
    .document img {
        width: 100%;
        max-height: 400px;
        object-fit: contain;
        border: 1px solid #ccc;
        margin-bottom: 10px;
    }
    .page-break {
    page-break-after: always;
}
</style>

@if($order->orderMaster->customer->status=='corporate')

@foreach($order->landcertificate_photo as $index => $photo)
<table>
    {{-- First Row: 3 columns --}}
    <tr>
        <td colspan="4"><strong>Order ID:</strong> {{ $order->id }}</td>
        <td colspan="4"><strong>Dokumen:</strong> Sertifikat Tanah #{{$index + 1}}</td>
        <td colspan="4"><strong>Created At:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</td>
    </tr>

    {{-- Second Row: 2 columns --}}
    <tr>
        <td colspan="6"><strong>Nama:</strong> {{ $order->orderMaster->customer->customer_name ?? '-' }}</td>
        <td colspan="6"><strong>Alamat:</strong> {{ $order->address ?? '-' }}</td>
    </tr>

    {{-- Third Row: 4 columns --}}
    <tr>
        <td colspan="3"><strong>Desa:</strong> {{ $order->village->name ?? '-' }}</td>
        <td colspan="3"><strong>Kecamatan:</strong> {{ $order->district->name ?? '-' }}</td>
        <td colspan="3"><strong>Kabupaten/Kota:</strong> {{ $order->regency->name ?? '-' }}</td>
        <td colspan="3"><strong>Provinsi:</strong> {{ $order->province->name ?? '-' }}</td>
    </tr>
      <tr>
        <td colspan ="12"> <strong>Produk:</strong> @foreach($order->orderProducts as $product) {{ $product->product->name }} ( {{ $product->qty }} ) @endforeach</td>
    </tr>

    {{-- Fourth Row: 1 full-width cell with images --}}
   <tr>
        <td colspan="12">
           
                @if ($photo)
                    <img src="{{ storage_path('app/private/' . $photo) }}"
                         alt="Land Certificate {{ $index + 1 }}"
                         style="max-width: 100%; max-height: 800px; object-fit: contain; margin: 5px;">
                @endif
           
        </td>
    </tr>

</table>
<div class="page-break"></div>
@endforeach

@endif

@if($order->orderMaster->customer->status=='private')
@foreach($order->landcertificate_photo as $index => $photo)
<table>
    {{-- First Row: 3 columns --}}
    <tr>
        <td colspan="4"><strong>Order ID:</strong> {{ $order->id }}</td>
        <td colspan="4"><strong>Dokumen:</strong> Sertifikat Tanah #{{$index + 1}}</td>
        <td colspan="4"><strong>Created At:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</td>
    </tr>

    {{-- Second Row: 2 columns --}}
    <tr>
        <td colspan="6"><strong>Nama:</strong> {{ $order->orderMaster->customer->customer_name ?? '-' }}</td>
        <td colspan="6"><strong>Alamat:</strong> {{ $order->address ?? '-' }}</td>
    </tr>

    {{-- Third Row: 4 columns --}}
    <tr>
        <td colspan="3"><strong>Desa:</strong> {{ $order->village->name ?? '-' }}</td>
        <td colspan="3"><strong>Kecamatan:</strong> {{ $order->district->name ?? '-' }}</td>
        <td colspan="3"><strong>Kabupaten/Kota:</strong> {{ $order->regency->name ?? '-' }}</td>
        <td colspan="3"><strong>Provinsi:</strong> {{ $order->province->name ?? '-' }}</td>
    </tr>
      <tr>
        <td colspan ="12"> <strong>Produk:</strong> @foreach($order->orderProducts as $product) {{ $product->product->name }} ( {{ $product->qty }} ) @endforeach</td>
    </tr>

    {{-- Fourth Row: 1 full-width cell with images --}}
@if (is_array($order->landcertificate_photo) && count($order->landcertificate_photo))
    <tr>
        <td colspan="12">
           
                @if ($photo)
                    <img src="{{ storage_path('app/private/' . $photo) }}"
                         alt="Land Certificate {{ $index + 1 }}"
                         style="max-width: 100%; max-height: 800px; object-fit: contain; margin: 5px;">
                @endif
           
        </td>
    </tr>
@else
    <tr>
        <td colspan="12">No land certificate photos.</td>
    </tr>
@endif

</table>
<div class="page-break"></div>
@endforeach
@foreach($order->KTP_photo as $index => $photo)
        <table>
    {{-- First Row: 3 columns --}}
    <tr>
        <td colspan="4"><strong>Order ID:</strong> {{ $order->id }}</td>
        <td colspan="4"><strong>Dokumen:</strong> KTP #{{ $index + 1 }}</td>
        <td colspan="4"><strong>Created At:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</td>
    </tr>

    {{-- Second Row: 2 columns --}}
    <tr>
        <td colspan="6"><strong>Nama:</strong> {{ $order->orderMaster->customer->customer_name ?? '-' }}</td>
        <td colspan="6"><strong>Alamat:</strong> {{ $order->address ?? '-' }}</td>
    </tr>

    {{-- Third Row: 4 columns --}}
    <tr>
        <td colspan="3"><strong>Desa:</strong> {{ $order->village->name ?? '-' }}</td>
        <td colspan="3"><strong>Kecamatan:</strong> {{ $order->district->name ?? '-' }}</td>
        <td colspan="3"><strong>Kabupaten/Kota:</strong> {{ $order->regency->name ?? '-' }}</td>
        <td colspan="3"><strong>Provinsi:</strong> {{ $order->province->name ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan ="12"> <strong>Produk:</strong> @foreach($order->orderProducts as $product) {{ $product->product->name }} ( {{ $product->qty }} ) @endforeach</td>
    </tr>

    {{-- Fourth Row: 1 full-width cell with images --}}
    <tr>
        <td colspan="12">
            @if(is_array($order->KTP_photo))
                 
                @if ($photo)
                    <img src="{{ storage_path('app/private/' . $photo) }}"
                        alt="KTP Photo {{ $index + 1 }}"
                        style="max-width: 100%; max-height: 800px; margin-right: 5px;">
                @endif
         
            @else
                <p>No KTP photos.</p>
            @endif
        </td>
    </tr>
</table>
<div class="page-break"></div>
@endforeach

@foreach($order->statementletter_photo as $index => $photo)
        <table>
    {{-- First Row: 3 columns --}}
    <tr>
        <td colspan="4"><strong>Order ID:</strong> {{ $order->id }}</td>
        <td colspan="4"><strong>Dokumen:</strong> Surat Pernyatan #{{ $index + 1 }}</td>
        <td colspan="4"><strong>Created At:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</td>
    </tr>

    {{-- Second Row: 2 columns --}}
    <tr>
        <td colspan="6"><strong>Nama:</strong> {{ $order->orderMaster->customer->customer_name ?? '-' }}</td>
        <td colspan="6"><strong>Alamat:</strong> {{ $order->address ?? '-' }}</td>
    </tr>

    {{-- Third Row: 4 columns --}}
    <tr>
        <td colspan="3"><strong>Desa:</strong> {{ $order->village->name ?? '-' }}</td>
        <td colspan="3"><strong>Kecamatan:</strong> {{ $order->district->name ?? '-' }}</td>
        <td colspan="3"><strong>Kabupaten/Kota:</strong> {{ $order->regency->name ?? '-' }}</td>
        <td colspan="3"><strong>Provinsi:</strong> {{ $order->province->name ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan ="12"> <strong>Produk:</strong> @foreach($order->orderProducts as $product) {{ $product->product->name }} ( {{ $product->qty }} ) @endforeach</td>
    </tr>

    {{-- Fourth Row: 1 full-width cell with images --}}
    <tr>
        <td colspan="12">
            @if(is_array($order->statementletter_photo))
                 
                @if ($photo)
                    <img src="{{ storage_path('app/private/' . $photo) }}"
                        alt="Surat Pernyataan {{ $index + 1 }}"
                        style="max-width: 100%; max-height: 800px; margin-right: 5px;">
                @endif
         
            @else
                <p>No Surat Pernyataan photos.</p>
            @endif
        </td>
    </tr>
</table>
<div class="page-break"></div>
@endforeach

@foreach($order->kartu_keluarga as $index => $photo)
        <table>
    {{-- First Row: 3 columns --}}
    <tr>
        <td colspan="4"><strong>Order ID:</strong> {{ $order->id }}</td>
        <td colspan="4"><strong>Dokumen:</strong> Kartu Keluarga #{{ $index + 1 }}</td>
        <td colspan="4"><strong>Created At:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</td>
    </tr>

    {{-- Second Row: 2 columns --}}
    <tr>
        <td colspan="6"><strong>Nama:</strong> {{ $order->orderMaster->customer->customer_name ?? '-' }}</td>
        <td colspan="6"><strong>Alamat:</strong> {{ $order->address ?? '-' }}</td>
    </tr>

    {{-- Third Row: 4 columns --}}
    <tr>
        <td colspan="3"><strong>Desa:</strong> {{ $order->village->name ?? '-' }}</td>
        <td colspan="3"><strong>Kecamatan:</strong> {{ $order->district->name ?? '-' }}</td>
        <td colspan="3"><strong>Kabupaten/Kota:</strong> {{ $order->regency->name ?? '-' }}</td>
        <td colspan="3"><strong>Provinsi:</strong> {{ $order->province->name ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan ="12"> <strong>Produk:</strong> @foreach($order->orderProducts as $product) {{ $product->product->name }} ( {{ $product->qty }} ) @endforeach</td>
    </tr>

    {{-- Fourth Row: 1 full-width cell with images --}}
    <tr>
        <td colspan="12">
            @if(is_array($order->kartu_keluarga))
                 
                @if ($photo)
                    <img src="{{ storage_path('app/private/' . $photo) }}"
                        alt="Kartu Keluarga {{ $index + 1 }}"
                        style="max-width: 100%; max-height: 800px ; margin-right: 5px;">
                @endif
         
            @else
                <p>No Kartu Keluarga photos.</p>
            @endif
        </td>
    </tr>
</table>
<div class="page-break"></div>
@endforeach
@endif