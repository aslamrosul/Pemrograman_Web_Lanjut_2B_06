<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder {
    public function run() {
        for ($i = 1; $i <= 15; $i++) {
            DB::table('t_stok')->insert([
                'barang_id' => $i,
                'user_id' => 1, // Sesuaikan dengan user yang tersedia
                'stok_tanggal' => now(),
                'stok_jumlah' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}