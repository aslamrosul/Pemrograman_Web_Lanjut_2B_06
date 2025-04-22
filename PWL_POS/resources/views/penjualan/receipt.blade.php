<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk Penjualan - {{ $penjualan->penjualan_kode }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .title {
            font-weight: bold;
            font-size: 14px;
        }
        .info {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table th, table td {
            padding: 3px;
            border-bottom: 1px dashed #ddd;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 10px;
        }
        hr {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">TOKO SUMBER JAYA</div>
        <div>Jl. Mayjend No. 123, Kota Malang</div>
        <div>Telp: 08123456789</div>
    </div>
    
    <hr>
    
    <div class="info">
        <div>No. Transaksi: {{ $penjualan->penjualan_kode }}</div>
        <div>Tanggal: {{ date('d/m/Y H:i', strtotime($penjualan->penjualan_tanggal)) }}</div>
        <div>Kasir: {{ $penjualan->user->username }}</div>
        <div>Pembeli: {{ $penjualan->pembeli }}</div>
    </div>
    
    <hr>
    
    <table>
        <thead>
            <tr>
                <th>Barang</th>
                <th class="text-right">Harga</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan->penjualanDetail as $detail)
            <tr>
                <td>{{ $detail->barang->barang_nama }}</td>
                <td class="text-right">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                <td class="text-center">{{ $detail->jumlah }}</td>
                <td class="text-right">{{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <hr>
    
    <div style="text-align: right; font-weight: bold;">
        Total: Rp {{ number_format($penjualan->penjualanDetail->sum(function($item) { 
            return $item->harga * $item->jumlah; 
        }), 0, ',', '.') }}
    </div>
    
    <div class="footer">
        Terima kasih telah berbelanja<br>
        Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan
    </div>
</body>
</html>