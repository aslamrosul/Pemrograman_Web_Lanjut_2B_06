@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/penjualan') }}" class="form-horizontal">
            @csrf
            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Kasir</label>
                <div class="col-10">
                    <select class="form-control" name="user_id" required>
                        <option value="">Pilih Kasir</option>
                        @foreach($kasir as $item)
                        <option value="{{ $item->user_id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Pembeli</label>
                <div class="col-10">
                    <input type="text" class="form-control" name="pembeli" required>
                    @error('pembeli')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Kode Transaksi</label>
                <div class="col-10">
                    <input type="text" class="form-control" name="penjualan_kode" required>
                    @error('penjualan_kode')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Tanggal</label>
                <div class="col-10">
                    <input type="date" class="form-control" name="penjualan_tanggal" required>
                    @error('penjualan_tanggal')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <h5>Detail Barang</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-items">
                    <tr>
                        <td>
                            <select class="form-control" name="barang_id[]">
                                <option value="">Pilih Barang</option>
                                @foreach($barang as $item)
                                <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="jumlah[]" min="1" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-success btn-sm" id="add-item">Tambah Barang</button>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                <a class="btn btn-default btn-sm" href="{{ url('/penjualan') }}">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        $('#add-item').click(function () {
            let row = `
                <tr>
                    <td>
                        <select class="form-control" name="barang_id[]">
                            <option value="">Pilih Barang</option>
                            @foreach($barang as $item)
                            <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control" name="jumlah[]" min="1" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button>
                    </td>
                </tr>`;
            $('#table-items').append(row);
        });

        $(document).on('click', '.remove-item', function () {
            $(this).closest('tr').remove();
        });
    });
</script>
@endpush
