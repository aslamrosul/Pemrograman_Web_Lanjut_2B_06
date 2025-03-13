<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 15; $i++) {
            DB::table('t_stok')->insert([
                'supplier_id' => rand(1, 3),
                'barang_id' => $i,
                'user_id' => 1,
                'stok_tanggal' => now(),
                'stok_jumlah' => rand(10, 50),
            ]);
        }
    }
}
