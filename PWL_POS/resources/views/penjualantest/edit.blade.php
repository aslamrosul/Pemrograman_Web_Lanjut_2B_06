@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Penjualan</h3>
        </div>
        <div class="card-body">
            <form action="{{ url('penjualan/' . $penjualan->penjualan_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="pembeli" class="form-label">Nama Pembeli</label>
                    <input type="text" name="pembeli" id="pembeli" class="form-control" value="{{ $penjualan->pembeli }}" required>
                </div>
                <div class="mb-3">
                    <label for="penjualan_tanggal" class="form-label">Tanggal</label>
                    <input type="datetime-local" name="penjualan_tanggal" id="penjualan_tanggal" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($penjualan->penjualan_tanggal)) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Barang</label>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="barang-list">
                            @foreach($penjualan->detail as $detail)
                                <tr>
                                    <td>
                                        <select name="barang_id[]" class="form-control barang-select" required>
                                            @foreach($barang as $b)
                                                <option value="{{ $b->barang_id }}" data-harga="{{ $b->harga_jual }}" {{ $b->barang_id == $detail->barang_id ? 'selected' : '' }}>
                                                    {{ $b->barang_nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="harga[]" class="form-control harga" value="{{ $detail->harga }}" readonly></td>
                                    <td><input type="number" name="jumlah[]" class="form-control jumlah" value="{{ $detail->jumlah }}" required></td>
                                    <td><button type="button" class="btn btn-sm btn-danger remove-barang">Hapus</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" id="add-barang" class="btn btn-sm btn-success">Tambah Barang</button>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ url('penjualan') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            // Tambah baris barang baru
            $('#add-barang').on('click', function () {
                let row = $('#barang-list tr:first').clone();
                row.find('input').val('');
                row.find('select').val('');
                $('#barang-list').append(row);
            });

            // Update harga saat barang dipilih
            $(document).on('change', '.barang-select', function () {
                let harga = $(this).find(':selected').data('harga');
                $(this).closest('tr').find('.harga').val(harga);
            });

            // Hapus baris barang
            $(document).on('click', '.remove-barang', function () {
                if ($('#barang-list tr').length > 1) {
                    $(this).closest('tr').remove();
                }
            });
        });
    </script>
@endpush
