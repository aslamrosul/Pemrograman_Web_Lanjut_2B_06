<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

// app/Models/BarangModel.php

use App\Models\StokModel;

class BarangModel extends Model
{
    use HasFactory;

    protected $table = 'm_barang';
    protected $primaryKey = 'barang_id';

    protected $fillable = [
        'kategori_id',
        'barang_kode',
        'barang_nama',
        'harga_beli',
        'harga_jual',
        'image',
    ];

    public $timestamps = false;

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('/storage/posts/' . $image),
        );
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }

    // Tambahkan accessor untuk menghitung stok real-time
    // App\Models\BarangModel.php
    public function getBarangStokAttribute(): int
    {
        // Total stok masuk (hanya yang positif)
        $stokMasuk = StokModel::where('barang_id', $this->barang_id)
            ->where('stok_jumlah', '>', 0)
            ->sum('stok_jumlah');

        // Total stok keluar (jumlah penjualan)
        $stokKeluar = PenjualanDetailModel::where('barang_id', $this->barang_id)
            ->sum('jumlah');

        return $stokMasuk - $stokKeluar;
    }
}
