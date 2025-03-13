<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        DB::table('m_supplier')->insert([
            ['supplier_kode' => 'SUP01', 'supplier_nama' => 'PT Maju Jaya', 'supplier_alamat' => 'Jakarta'],
            ['supplier_kode' => 'SUP02', 'supplier_nama' => 'CV Sukses Selalu', 'supplier_alamat' => 'Surabaya'],
            ['supplier_kode' => 'SUP03', 'supplier_nama' => 'UD Makmur Sentosa', 'supplier_alamat' => 'Bandung'],
        ]);
    }
}
