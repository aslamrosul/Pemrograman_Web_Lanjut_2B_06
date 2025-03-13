@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Detail Penjualan</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr><th>ID</th><td>{{ $penjualan->penjualan_id }}</td></tr>
                <tr><th>Kode</th><td>{{ $penjualan->penjualan_kode }}</td></tr>
                <tr><th>Pembeli</th><td>{{ $penjualan->pembeli }}</td></tr>
                <tr><th>Tanggal</th><td>{{ $penjualan->penjualan_tanggal }}</td></tr>
            </table>
            <h5>Detail Barang</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualan->detail as $detail)
                        <tr>
                            <td>{{ $detail->barang->barang_nama }}</td>
                            <td>{{ $detail->harga }}</td>
                            <td>{{ $detail->jumlah }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ url('penjualan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection
