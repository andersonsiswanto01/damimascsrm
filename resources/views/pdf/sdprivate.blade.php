<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pemesanan Kecambah</title>
    <style>
        @page {
            size: A4;
            margin: 1cm 2.5cm 2.5cm 2.5cm; /* top, right, bottom, left */
        }
       
        .fixed-header {
            position: fixed;
            top: 0cm; /* Adjust the top margin */
            left: 0.5cm; /* Adjust the left margin */
            width: 100%;
            height: auto;
            font-family: 'Times New Roman', Times, serif;
            font-size: 20pt;
            font-weight: bold;
            display: flex;
            align-items: center; /* Center the content vertically */
            padding: 5px 0;
        }

        .page-break {
            page-break-before: always; /* Forces a new page */
            margin-top: 2cm; /* Add a margin to ensure content starts below header on new page */
        }

        p {
    margin: 5px 0;
}

        .logo {
            margin-right: 10px; /* Space between the logo and the text */
        }

        .content {
            margin-top: 2.5cm; /* Make sure content is pushed below the fixed header */
            margin-left: 0.5cm; /* Adjust left margin to align with header */
            margin-right: 0.5cm; /* Adjust right margin to align with header */
            text-align: justify; /* Justify the text to spread it evenly */
        }

        body {
            font-family:  'Arial', Helvetica, sans-serif;
            font-size: 11pt;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            padding: 2.5cm;
        }

        .header, .content, .footer {
            margin-bottom: 20px;
        }

        .left, .right {
            display: inline-block;
            vertical-align: top;
        }

        .left { width: 60%; }
        .right { width: 38%; text-align: right; }

        .center { text-align: center; }

        .title {
            font-weight: bold;
            text-decoration: underline;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th, .table td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        .signature {
            margin-top: 50px;
            text-align: left;
        }

        .indent {
            text-indent: 35px;
        }

        p {
            margin: 5px 0;
        }

        ol {
            margin-left: 5px;
        }

        ol li {
            margin-bottom: 10px;
        }

        @media print {
            body {
                margin: 0;
            }

            .container {
                padding: 2.5cm;
            }
        }
    </style>
</head>
<body>
    <div class="fixed-header">
        {{-- Company name text as logo --}}
        <div>
            <p><strong>PT. SAWIT UNGGUL SAKTI</strong></p>
        </div>
    </div>



<div class="content">

    <div class="header">
        <div class="left">
            <p>No : {{$orderNumber}}</p>
        </div>
        <div class="right">
            <p>Jakarta, {{ $date }}</p>
        </div>
    </div>

    <p>Kepada Yth.:</p>
    <p><strong>{{ $corporate }}</strong></p>
    <p>Attn. : {{ $customerName }}</p>
    <br>
    <p><strong>Perihal:</strong> Pemesanan Kecambah Kelapa Sawit @foreach ($grouped as $key => $product)
    @if ($key > 0)
        {{ $key == count($grouped) - 1 ? ' dan ' : ', ' }}
    @endif
    {{ $product['product_name'] }} {{ number_format($product['total_qty']) }} butir
@endforeach </p>
    <br>
    <p>Dengan hormat,</p>

    <p class="indent">Menanggapi permintaan dari {{ $corporate }} melalui {{ $order_source }} total Qty {{ $qty }} butir yang kami terima pada
    tanggal {{ $date }}, maka pada prinsipnya permintaan dapat dipenuhi dengan
    ketentuan sebagai berikut:</p>

    <ol>
        <li> Melampirkan identitas / KTP dan KK (Kartu Keluarga), photocopy Sertifikat 
            Tanah/Keterangan Kepemilikan Lahan dari Kepala Desa setempat, dan Surat 
            Pernyataan bermaterai penggunaan benih untuk kebun sendiri. Apabila nama 
            berbeda dengan identitas, maka harus dilengkapi Surat Kepemilikan Lahan dari 
            Kepala Desa setempat.</li>
        <li>Harga kecambah   
          <strong>
            
     @foreach ($grouped as $key => $product)
    @if ($key > 0)
        {{ $key == count($grouped) - 1 ? ' dan ' : ', ' }}
    @endif
    {{ $product['product_name'] }} {{ number_format($product['average_price']) }}/butir
@endforeach
          </strong> loco
        kebun Dami Mas Estate, Desa Beringin Lestari, Kec. Tapung, Kab. Kampar – Riau. Ekstra kecambah (<em>free allowance </em>) 5%.</li>
        <li style="font-weight: bold">Dana pembelian kecambah sebesar Rp. {{ $grandTotal }} ({{ $totalPriceTerbilang }} Rupiah Full Amount) sudah diterima sebelum jadwal yang ditetapkan di rekening sebagai berikut:
            <br><br>
            <table style="width: 70%; border-collapse: collapse; font-size: 11pt; margin-left: 15%; font-weight: bold">
                <tr>
                    <td style="padding: 0px; vertical-align: top;"><strong>Nama PT</strong></td>
                    <td>:</td>
                    <td style="padding: 0px;">PT Sawit Unggul Sakti</td>
                </tr>
                <tr>
                    <td style="padding: 0px; vertical-align: top;"><strong>No Rek</strong></td>
                    <td>:</td>
                    <td style="padding: 0px;">004 – 5483 – 077</td>
                </tr>
                <tr>
                    <td style="padding: 0px; vertical-align: top;"><strong>Jenis Rek</strong></td>
                    <td>:</td>
                    <td style="padding: 0px;">IDR</td>
                </tr>
                <tr>
                    <td style="padding: 0px; vertical-align: top;"><strong>Bank</strong></td>
                    <td>:</td>
                    <td style="padding: 0px;">Sinarmas</td>
                </tr>
                <tr>
                    <td style="padding: 0px; vertical-align: top;"><strong>Cabang</strong></td>
                    <td>:</td>
                    <td style="padding: 0px;">MSIG, Jakarta</td>
                </tr>
            </table>
            
        </li>

        <p>Apabila transfer dana telah dilakukan, mohon bukti transfer dapat dikirim via email
            ke sales@sawit-unggul.com. Apabila pembelian kecambah dari PT Sawit Unggul
            Sakti dibatalkan, maka uang pembelian kecambah tidak akan dikembalikan.
            </p>
        <li>Pelunasan pembayaran pembelian kecambah dilakukan sesuai tanggal
            pengambilan kecambah. Jadwal pembayaran dan pengambilan kecambah PT Sawit
            Unggul Sakti adalah sebagai berikut:</li>
    </ol>
    <div class="page-break">
    </div>
    <table class="table" style="font-size: 10px">
        <thead>
            <tr>
                <th>No PI</th>
                <th>Tanggal Jatuh Tempo</th>
                <th>Tanggal Pengambilan</th>
                <th>Qty</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderDetails as $orderDetail)
            <tr>
                <td>{{ $orderDetail['order_id'] }}</td>
                <td>{{$orderDetail['payment_due']}}</td>
                <td>{{ $orderDetail['delivery_date']}}</td>
                <td>{{ number_format($orderDetail['grandTotalQtyOrder'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($orderDetail['grandTotalOrder'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr style="background-color: lightgray;">
            <td colspan="3"><strong>Total</strong></td>
            <td><strong>{{ number_format($qty, 0, ',', '.') }}</strong></td>
            <td><strong>Rp {{ $grandTotal}}</strong></td>
        </tr>
        
    </table>
    <br>
    <ol start="5">
        <li>Apabila sampai tanggal yang ditetapkan di atas dana belum diterima di rekening PT Sawit Unggul Sakti, maka kami menganggap pembelian kecambah dibatalkan.</li>
        <li>Pada saat pengambilan kecambah dokumen DO dan surat kuasa harus dibawa.</li>
        <li>Pengurusan dokumen dan pemeriksaan ulang kecambah yang dilakukan di UPT Provinsi adalah tanggung jawab pembeli. Bila diperlukan, kami dapat memberikan pendampingan dalam proses tersebut.</li>
    </ol>

    <p class="indent">Terima kasih atas kepercayaan terhadap kecambah Sawit Unggul Sakti dan kami tunggu konfirmasi lebih lanjut dari pihak {{ $corporate }}.</p>
</div>

<div class="signature">
    <p>Hormat kami,</p>
     <p><img src="{{ $signature }}" alt="Corporate Signature" style="height: 80px;"></p>
    <p><strong>Musa Chandra</strong><br>Direktur Utama</p>
</div>

</body>
</html>
