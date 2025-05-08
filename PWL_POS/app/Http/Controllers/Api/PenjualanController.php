<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        return PenjualanModel::with(['user', 'penjualanDetail.barang'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'pembeli' => 'required|string|max:100',
            'penjualan_kode' => 'required|string|max:20|unique:t_penjualan',
            'details' => 'required|array|min:1',
            'details.*.barang_id' => 'required|integer|exists:m_barang,barang_id',
            'details.*.jumlah' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $penjualan = PenjualanModel::create([
                'user_id' => $request->user_id,
                'pembeli' => $request->pembeli,
                'penjualan_kode' => $request->penjualan_kode,
                'penjualan_tanggal' => now()
            ]);

            foreach ($request->details as $detail) {
                $barang = BarangModel::find($detail['barang_id']);

                // Gunakan accessor untuk cek stok
                if ($barang->barang_stok < $detail['jumlah']) {
                    throw new \Exception("Stok barang {$barang->barang_nama} tidak mencukupi");
                }

                // Simpan detail dengan harga dari database
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $detail['barang_id'],
                    'jumlah' => $detail['jumlah'],
                    'harga' => $barang->harga_jual // Ambil harga dari database
                ]);

                // Jika menggunakan stok fisik:
                // $barang->decrement('barang_stok', $detail['jumlah']);
            }

            DB::commit();
            return response()->json(['success' => true, 'data' => $penjualan]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan penjualan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $penjualan = PenjualanModel::with(['user', 'penjualanDetail.barang'])->find($id);

        if (!$penjualan) {
            return response()->json([
                'success' => false,
                'message' => 'Data penjualan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'penjualan' => $penjualan
        ]);
    }

    public function update(Request $request, $id)
{
    // Validasi input dengan rule 'sometimes' untuk field yang opsional
    $validator = Validator::make($request->all(), [
        'user_id' => 'sometimes|required|integer|exists:m_user,user_id',
        'pembeli' => 'sometimes|required|string|max:100',
        'penjualan_kode' => 'sometimes|required|string|max:20|unique:t_penjualan,penjualan_kode,' . $id . ',penjualan_id',
        'details' => 'sometimes|required|array|min:1',
        'details.*.barang_id' => 'sometimes|required|integer|exists:m_barang,barang_id',
        'details.*.jumlah' => 'sometimes|required|integer|min:1'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    DB::beginTransaction();
    try {
        $penjualan = PenjualanModel::with('penjualanDetail')->findOrFail($id);

        // Update header penjualan hanya untuk field yang ada di request
        $updateData = [];
        if ($request->has('user_id')) {
            $updateData['user_id'] = $request->user_id;
        }
        if ($request->has('pembeli')) {
            $updateData['pembeli'] = $request->pembeli;
        }
        if ($request->has('penjualan_kode')) {
            $updateData['penjualan_kode'] = $request->penjualan_kode;
        }

        if (!empty($updateData)) {
            $penjualan->update($updateData);
        }

        // Update detail penjualan hanya jika ada di request
        if ($request->has('details')) {
            // Hapus detail lama
            $penjualan->penjualanDetail()->delete();

            // Tambahkan detail baru
            foreach ($request->details as $detail) {
                $barang = BarangModel::findOrFail($detail['barang_id']);
                
                // Validasi stok menggunakan accessor
                if ($barang->barang_stok < $detail['jumlah']) {
                    throw new \Exception("Stok {$barang->barang_nama} tidak mencukupi");
                }

                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $barang->barang_id,
                    'jumlah' => $detail['jumlah'],
                    'harga' => $barang->harga_jual
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'data' => $penjualan->fresh(['user', 'penjualanDetail.barang'])
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Gagal update penjualan: ' . $e->getMessage()
        ], 500);
    }
}
    public function destroy($id)
    {
        $penjualan = PenjualanModel::find($id);

        if (!$penjualan) {
            return response()->json([
                'success' => false,
                'message' => 'Data penjualan tidak ditemukan'
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Restore stock
            foreach ($penjualan->penjualanDetail as $detail) {
                $barang = BarangModel::find($detail->barang_id);
                $barang->barang_stok += $detail->jumlah;
                $barang->save();
            }

            // Delete details
            $penjualan->penjualanDetail()->delete();

            // Delete penjualan
            $penjualan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data penjualan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
