<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder {
    public function run() {
        for ($i = 1; $i <= 15; $i++) {
            DB::table('m_barang')->insert([
                'kategori_id' => rand(1, 5),
                'barang_kode' => 'BRG' . $i,
                'barang_nama' => 'Barang ' . $i,
                'harga_beli' => rand(5000, 50000),
                'harga_jual' => rand(6000, 60000),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}