<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder {
    public function run() {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('t_penjualan')->insert([
                'user_id' => 1, // Sesuaikan dengan user yang tersedia
                'pembeli' => 'Pembeli ' . $i,
                'penjualan_kode' => 'PJ' . $i . rand(100, 999),
                'penjualan_tanggal' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}