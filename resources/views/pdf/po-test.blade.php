<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Purchase Order</title>
  <style>
    @page {
      size: A4;
      margin: 20mm;
    }
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      line-height: 1.4;
      color: #000;
    }
    .header {
      text-align: center;
      margin-bottom: 20px;
    }
    .header h2 {
      margin: 0;
      text-transform: uppercase;
    }
    .company-info {
      margin-bottom: 10px;
    }
    .company-info p {
      margin: 2px 0;
    }
    .two-col {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .two-col div {
      width: 48%;
      border: 1px solid #000;
      padding: 8px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    table, th, td {
      border: 1px solid #000;
    }
    th, td {
      padding: 6px;
      text-align: left;
    }
    .right {
      text-align: right;
    }
    .footer {
      margin-top: 40px;
    }
    .signature {
      margin-top: 60px;
      text-align: right;
    }
  </style>
</head>
<body>

  <div class="header">
    <h2>ORDER PEMBELIAN</h2>
  </div>

  <div class="company-info">
    <p><strong>PT Sawit Unggul Sakti</strong></p>
    <p>Menara Thamrin, Lt. 18 Jakarta</p>
    <p>Jl. MH Thamrin, Kav. 3, Kampung Bali</p>
    <p>Tanah Abang, Jakarta Pusat - 10250</p>
    <p>Telp: 021-39830007</p>
    <p><strong>No: 011/SUS/II/2025</strong></p>
    <p><strong>Tanggal: 26 Februari 2025</strong></p>
  </div>

  <div class="two-col">
    <div>
      <strong>BARANG DIKIRIM KE:</strong><br>
      PT Sawit Unggul Sakti<br>
      Sinarmas Land Plaza 2 no 23<br>
      Menteng, Jakarta Pusat
    </div>
    <div>
      <strong>KEPADA:</strong><br>
      PT Dami Mas Sejahtera<br>
      Desa Beringin Lestari, Tapung Hilir<br>
      Kampar, Pekanbaru
    </div>
  </div>

  <p><strong>Syarat Pembayaran:</strong> 30 HARI SETELAH INVOICE DITERIMA</p>
  <div>
    <p>Harap dikirimkan barang ke alamat seperti tersebut di atas dan cantumkan nomor PO ini pada surat jalan Anda. 1 copy surat jalan harus diberikan kepada penerima barang.
        Untuk penagihan, kirimkan kembali kepada kami: OP asli, Surat Jalan Asli yang telah ditandatangani dan dicap oleh penerima barang, Faktur Pajak dan atau kwitansi bermeterai cukup.</p>
  </div>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Kuantitas</th>
        <th>Nama Barang</th>
        <th>Harga Satuan</th>
        <th>Disc</th>
        <th>Jumlah</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td class="right">630 butir</td>
        <td>DxP Dami Mas IGR</td>
        <td class="right">11.000,00 Rp</td>
        <td class="right">330.000,00 Rp</td>
        <td class="right">6.600.000,00 Rp</td>
      </tr>
      <tr>
        <td>2</td>
        <td class="right">49.980 butir</td>
        <td>DxP Dami Mas Reguler</td>
        <td class="right">6.250,00 Rp</td>
        <td class="right">14.875.000,00 Rp</td>
        <td class="right">297.500.000,00 Rp</td>
      </tr>
      <tr>
        <td colspan="5" class="right"><strong>Total</strong></td>
        <td class="right"><strong>304.100.000,00 Rp</strong></td>
      </tr>
    </tbody>
  </table>

  <p><strong>Note:</strong> LOCO Gudang Dami Mas</p>
  <p><strong>FULL AMOUNT</strong></p>
  <p><strong>Terbilang:</strong> #NAME?</p>



  <div class="signature">
    <p><strong>PT SAWIT UNGGUL SAKTI</strong></p>
    <p style="margin-top:60px;">Musa Chandra</p>
  </div>

</body>
</html>
