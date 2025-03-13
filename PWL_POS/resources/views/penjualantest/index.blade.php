@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Penjualan</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('penjualan/create') }}">Tambah</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Penjualan</th>
                        <th>Pembeli</th>
                        <th>Petugas</th> <!-- Tambahkan kolom petugas -->
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualan as $item)
                        <tr>
                            <td>{{ $item->penjualan_id }}</td>
                            <td>{{ $item->penjualan_kode }}</td>
                            <td>{{ $item->pembeli }}</td>
                            <td>{{ $item->user->nama }}</td> <!-- Tampilkan nama petugas -->
                            <td>{{ $item->penjualan_tanggal }}</td>
                            <td>
                                <a href="{{ url('penjualan/' . $item->penjualan_id) }}" class="btn btn-sm btn-info">Lihat</a>
                                <a href="{{ url('penjualan/' . $item->penjualan_id . '/edit') }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ url('penjualan/' . $item->penjualan_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
