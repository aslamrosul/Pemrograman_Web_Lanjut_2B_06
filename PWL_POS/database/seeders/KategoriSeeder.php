<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        DB::table('m_kategori')->insert([
            ['kategori_kode' => 'ELEC', 'kategori_nama' => 'Elektronik'],
            ['kategori_kode' => 'FURN', 'kategori_nama' => 'Furniture'],
            ['kategori_kode' => 'FOOD', 'kategori_nama' => 'Makanan'],
            ['kategori_kode' => 'CLOT', 'kategori_nama' => 'Pakaian'],
            ['kategori_kode' => 'BEV', 'kategori_nama' => 'Minuman'],
        ]);
    }
}
