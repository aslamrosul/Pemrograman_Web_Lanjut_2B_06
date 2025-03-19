@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td>{{ $penjualan->penjualan_id }}</td>
            </tr>
            <tr>
                <th>Kode Transaksi</th>
                <td>{{ $penjualan->penjualan_kode }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ $penjualan->penjualan_tanggal }}</td>
            </tr>
            <tr>
                <th>Pembeli</th>
                <td>{{ $penjualan->pembeli }}</td>
            </tr>
            <tr>
                <th>Kasir</th>
                <td>{{ $penjualan->user->nama }}</td>
            </tr>
        </table>
        <h4>Detail Barang</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan->details as $detail)
                <tr>
                    <td>{{ $detail->barang->barang_nama }}</td>
                    <td>Rp {{ number_format($detail->barang->harga, 0, ',', '.') }}</td>
                    <td>{{ $detail->jumlah }}</td>
                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <h4>Total Transaksi: Rp {{ number_format($penjualan->details->sum('subtotal'), 0, ',', '.') }}</h4>
        <a class="btn btn-default" href="{{ url('penjualan') }}">Kembali</a>
    </div>
</div>
@endsection

@push('css')
@endpush
@push('js')
@endpush