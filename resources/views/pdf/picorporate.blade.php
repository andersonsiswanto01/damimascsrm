<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Proforma Invoice {{$customerName}}</title>
    <style>
        @page {
            size: A4;
            margin: 0cm 2cm 1cm 2cm; /* top, right, bottom, left */
        }
       
        .fixed-header {
            position: fixed;
            top: 1cm; /* Adjust the top margin */
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
            margin-top: 1cm; /* Make sure content is pushed below the fixed header */
            margin-left: 0.5cm; /* Adjust left margin to align with header */
            margin-right: 0.5cm; /* Adjust right margin to align with header */
            text-align: justify; /* Justify the text to spread it evenly */
        }

        h1 {
            text-align: center;
            font-size: 12pt;
            margin-top: 3.2cm;
            margin-bottom: 20px;
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
            margin-top: px;
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

        .footer-fixed {
  position: fixed;
  bottom: 0cm;
  left: 0;
  right: 0;
  text-align: center;
  font-size: 10pt;
  color: #555;
  border-top: 1px solid #ccc;
  padding-top: 5px;
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

    <h1 >PROFORMA INVOICE</h1>

<div class="content">

    <div class="header">
        <div class="left">
            
        </div>
        <div class="right">
            <div class="section">
                <table>
                  <tr>
                    <td class="bold">Nomor</td>
                    <td>:</td>
                    <td>{{$orderNumber}}</td>
                  </tr>
                  <tr>
                    <td class="bold">Referensi</td>
                    <td>:</td>
                    <td>{{$orderNumber}}</td>
                  </tr>
                  <tr>
                    <td class="bold">Tanggal</td>
                    <td>:</td>
                    <td><p> {{ $date }}</p></td>
                  </tr>
                </table>
              </div>

        </div>
    </div>

    <p>Kepada Yth.:</p>
    <p><strong>{{ $corporate }}</strong></p>
    <p>Attn. : {{ $customerName }}</p>
    <br>
    <p>Proforma Invoice untuk pesanan melalui PO nomor, 100% untuk pembayaran <span class="bold">{{$qty}} butir Kecambah
        @foreach ($productDetails as $key => $product)
            @if ($key > 0)
                {{ $key == count($productDetails) - 1 ? ' dan ' : ', ' }}
            @endif
            <span>{{ $product['name'] }} ( Rp {{ $product['price'] }} )</span>
        @endforeach
        <span class="bold">= Rp {{$totalPrice}}-</span>
    </p>

    <br>

    
    <table style="width: 100%; border-collapse: collapse; font-size: 11pt; font-weight: bold">
        <tr>
            <td>Jumlah Pembayaran</td>
            <td>:</td>
            <td>Rp{{$totalPrice}}</td>
        </tr>
        <tr>
            <td>Terbilang</td>
            <td>:</td>
            <td>{{ $totalPriceTerbilang }} Rupiah (FULL AMOUNT)</td>
        </tr>
    </table>

    <br>    
    <p>Mohon Pembayaran ditransfer ke rekening sebagai berikut:</p>    
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
            
        </p>
        <br>
        <p><strong>Untuk biaya administrasi ditanggung oleh pengirim.</strong></p>
        <br>
        <p>Untuk pengambilan kecambah pada tanggal yang sudah ditentukan, mohon agar proses pembayaran dilakukan sebelum tanggal <strong>22 April 2025</strong>.</p>
        <br>
        <p>Atas perhatian dan kerja samanya, kami ucapkan terima kasih.</p>

        <div class="signature">
            <p>Hormat kami,</p>
            <p><img src="{{ $signature }}" alt="Corporate Signature" style="height: 80px;"></p>
            <p><strong>Musa Chandra</strong><br>Direktur Utama</p>
        </div>
</div>



<div class="footer-fixed">
    PT Sawit Unggul Sakti – Menara Thamrin Lt.18, Jakarta Pusat 10250 | Telp: 021-39830007 | Email: sales@sawit-unggul.com
  </div>
</body>
</html>
