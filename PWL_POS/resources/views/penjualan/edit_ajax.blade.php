@empty($penjualan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data penjualan tidak ditemukan.
                </div>
                <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/penjualan/' . $penjualan->penjualan_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')

        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Penjualan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Penjualan</label>
                        <input type="text" class="form-control" value="{{ $penjualan->penjualan_kode }}" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Nama Pembeli</label>
                        <input type="text" name="pembeli" class="form-control" value="{{ $penjualan->pembeli }}" required>
                        <small id="error-pembeli" class="error-text form-text text-danger"></small>
                    </div>
                    
                    <div class="form-group">
                        <label>Tanggal Penjualan</label>
                        <input type="text" class="form-control" 
                               value="{{ \Carbon\Carbon::parse($penjualan->penjualan_tanggal)->translatedFormat('d F Y H:i:s') }}" readonly>
                    </div>
                    
                    <hr>
                    <h5>Detail Barang</h5>
                    <div id="detail-container">
                        @foreach($penjualan->penjualanDetail as $index => $detail)
                        <div class="detail-item row mb-3">
                            <div class="col-md-5">
                                <label>Barang</label>
                                <select class="form-control barang-select" name="details[{{ $index }}][barang_id]" required>
                                    <option value="">Pilih Barang</option>
                                    @foreach($barang as $item)
                                        <option value="{{ $item->barang_id }}" 
                                            {{ $detail->barang_id == $item->barang_id ? 'selected' : '' }}
                                            data-harga="{{ $item->harga_jual }}">
                                            {{ $item->barang_nama }} (Stok: {{ $item->barang_stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Harga Satuan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control harga-satuan" 
                                           name="details[{{ $index }}][harga]" 
                                           value="{{ number_format($detail->harga, 0, ',', '.') }}" readonly required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>Jumlah</label>
                                <input type="number" class="form-control jumlah" 
                                       name="details[{{ $index }}][jumlah]" 
                                       value="{{ $detail->jumlah }}" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <label>SubTotal</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control subtotal" 
                                           value="{{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-block hapus-detail">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <button type="button" id="tambah-detail" class="btn btn-secondary">
                        <i class="fa fa-plus"></i> Tambah Barang
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">
                        <i class="fa fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            // Initialize detail counter
            let detailCounter = {{ count($penjualan->penjualanDetail) }};
            
            // Function to format number as Rupiah
            function formatRupiah(angka) {
                var number_string = angka.toString(),
                    split = number_string.split('.'),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);
                    
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                
                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah;
            }
            
            // Function to calculate price
            function calculatePrice(element) {
                const row = $(element).closest('.detail-item');
                const pricePerItem = parseFloat(row.find('.barang-select option:selected').data('harga')) || 0;
                const quantity = parseInt(row.find('.jumlah').val()) || 0;
                const totalPrice = pricePerItem * quantity;
                
                row.find('.harga-satuan').val(formatRupiah(pricePerItem));
                row.find('.subtotal').val(formatRupiah(totalPrice));
            }
        
            // Add detail item
            $('#tambah-detail').click(function() {
                const index = detailCounter++;
                const newDetail = `
                    <div class="detail-item row mb-3">
                        <div class="col-md-5">
                            <label>Barang</label>
                            <select class="form-control barang-select" name="details[${index}][barang_id]" required>
                                <option value="">Pilih Barang</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->barang_id }}" data-harga="{{ $item->harga_jual }}">
                                        {{ $item->barang_nama }} (Stok: {{ $item->barang_stok }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Harga Satuan</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control harga-satuan" name="details[${index}][harga]" readonly required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label>Jumlah</label>
                            <input type="number" class="form-control jumlah" name="details[${index}][jumlah]" min="1" value="1" required>
                        </div>
                        <div class="col-md-2">
                            <label>SubTotal</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control subtotal" readonly>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-block hapus-detail">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                `;
                $('#detail-container').append(newDetail);
            });
        
            // Remove detail item
            $(document).on('click', '.hapus-detail', function() {
                if ($('.detail-item').length > 1) {
                    $(this).closest('.detail-item').remove();
                } else {
                    alert('Minimal harus ada satu detail barang');
                }
            });
        
            // Update price when item or quantity changes
            $(document).on('change', '.barang-select', function() {
                const row = $(this).closest('.detail-item');
                const pricePerItem = parseFloat($(this).find('option:selected').data('harga')) || 0;
                row.find('.harga-satuan').val(formatRupiah(pricePerItem));
                calculatePrice(this);
            });
        
            $(document).on('input', '.jumlah', function() {
                calculatePrice(this);
            });
        
            // Calculate initial prices for existing items
            $('.detail-item').each(function() {
                calculatePrice(this);
            });
        
            // Form validation
            $("#form-edit").validate({
                rules: {
                    pembeli: {
                        required: true,
                        maxlength: 100
                    }
                },
                submitHandler: function(form) {
                    // Convert Rupiah format back to number before submit
                    $('.harga-satuan').each(function() {
                        let value = $(this).val().replace(/\./g, '');
                        $(this).val(value);
                    });
                    
                    $.ajax({
                        url: $(form).attr('action'),
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#modal-master').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(key, value) {
                                    $('#error-' + key).text(value[0]);
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan sistem'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endempty