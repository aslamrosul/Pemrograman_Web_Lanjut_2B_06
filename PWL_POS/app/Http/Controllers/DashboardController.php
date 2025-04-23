<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\PenjualanDetailModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list' => ['Home', 'Dashboard']
        ];

        $activeMenu = 'dashboard';

        // Hitung data statistik
        $totalUsers = UserModel::count();
        $totalProducts = BarangModel::count();
        $totalCategories = KategoriModel::count();
        
        // Hitung total pendapatan dari semua penjualan
        $totalRevenue = PenjualanDetailModel::with('barang')
            ->get()
            ->sum(function($detail) {
                return $detail->jumlah * $detail->harga;
            });

        return view('dashboard', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'totalCategories' => $totalCategories,
            'totalRevenue' => $totalRevenue
        ]);
    }
}