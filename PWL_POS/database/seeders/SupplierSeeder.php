<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder {
    public function run() {
        $suppliers = ['Supplier A', 'Supplier B', 'Supplier C'];
        foreach ($suppliers as $key => $supplier) {
            DB::table('m_supplier')->insert([
                'supplier_kode' => 'SUP' . ($key + 1),
                'supplier_nama' => $supplier,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}