<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder {
    public function run() {
        $kategori = ['Elektronik', 'Pakaian', 'Makanan', 'Minuman', 'Aksesoris'];
        foreach ($kategori as $k) {
            DB::table('m_kategori')->insert([
                'kategori_kode' => strtoupper(substr($k, 0, 3)) . rand(100, 999),
                'kategori_nama' => $k,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}