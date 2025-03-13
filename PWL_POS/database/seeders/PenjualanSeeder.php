<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('t_penjualan')->insert([
                'user_id' => 1,
                'pembeli' => 'Pembeli ' . $i,
                'penjualan_kode' => 'PJ00' . $i,
                'penjualan_tanggal' => now(),
            ]);
        }
    }
}
