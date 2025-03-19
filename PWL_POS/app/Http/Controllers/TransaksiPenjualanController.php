<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use App\Models\BarangModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TransaksiPenjualanController extends Controller
{
    // Menampilkan halaman daftar transaksi penjualan
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Transaksi Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        $page = (object) [
            'title' => 'Daftar transaksi penjualan dalam sistem'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Ambil data transaksi untuk DataTables
    public function list(Request $request)
    {
        $penjualan = PenjualanModel::with('user')
            ->select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal');
    
        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('kasir', function ($penjualan) {
                return $penjualan->user ? $penjualan->user->nama : '-';
            })
            ->addColumn('aksi', function ($penjualan) {
                // return '
                //     <a href="' . url('/penjualan/' . $penjualan->penjualan_id) . '" class="btn btn-info btn-sm">Detail</a>
                //     <a href="' . url('/penjualan/' . $penjualan->penjualan_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>
                //     <form action="' . url('/penjualan/' . $penjualan->penjualan_id) . '" method="POST" class="d-inline-block">
                //         ' . csrf_field() . method_field('DELETE') . '
                //         <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus transaksi ini?\');">Hapus</button>
                //     </form>
                // ';

                $btn = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    


    // Menampilkan halaman tambah transaksi
     public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Transaksi Penjualan',
            'list' => ['Home', 'Penjualan', 'Tambah']
        ];
    
        $page = (object) [
            'title' => 'Tambah transaksi penjualan'
        ];
    
        $kasir = UserModel::all(); // Ambil data kasir (user)
        $barang = BarangModel::all(); // Ambil data barang
        $activeMenu = 'penjualan';
    
        return view('penjualan.create', compact('breadcrumb', 'page', 'kasir', 'barang', 'activeMenu'));
    }
    

    // Menyimpan transaksi penjualan
    public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:m_user,user_id',
        'pembeli' => 'required|string|max:100',
        'penjualan_kode' => 'required|string|unique:t_penjualan,penjualan_kode',
        'penjualan_tanggal' => 'required|date',
        'barang_id' => 'required|array',
        'barang_id.*' => 'exists:m_barang,barang_id',
        'jumlah' => 'required|array',
        'jumlah.*' => 'integer|min:1'
    ]);

    // Simpan data transaksi penjualan
    $penjualan = PenjualanModel::create([
        'user_id' => $request->user_id,
        'pembeli' => $request->pembeli,
        'penjualan_kode' => $request->penjualan_kode,
        'penjualan_tanggal' => $request->penjualan_tanggal
    ]);

    // Simpan detail barang yang dibeli
    foreach ($request->barang_id as $index => $barang_id) {
        $barang = BarangModel::find($barang_id); // Ambil data barang berdasarkan ID
    
    if (!$barang) {
        return redirect()->back()->withErrors(['barang_id' => 'Barang dengan ID ' . $barang_id . ' tidak ditemukan']);
    }
   
        PenjualanDetailModel::create([
            'penjualan_id' => $penjualan->penjualan_id,
            'barang_id' => $barang_id,
            'jumlah' => $request->jumlah[$index],
            'harga' => $barang->harga_jual ?? 0
        ]);
    }

    return redirect('/penjualan')->with('success', 'Transaksi berhasil ditambahkan');
}


    // Menampilkan halaman edit transaksi
    public function edit(string $id)
    {
        $penjualan = PenjualanModel::with('details')->findOrFail($id);
        $user = UserModel::all();
        $barang = BarangModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Transaksi Penjualan',
            'list' => ['Home', 'Penjualan', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit transaksi penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.edit', compact('breadcrumb', 'page', 'penjualan', 'user', 'barang', 'activeMenu'));
    }

    // Menyimpan perubahan transaksi
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:m_user,user_id',
            'pembeli' => 'required|string|max:100',
            'penjualan_kode' => 'required|string|unique:t_penjualan,penjualan_kode,' . $id . ',penjualan_id',
            'penjualan_tanggal' => 'required|date',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:m_barang,barang_id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
            'harga' => 'required|array',
            'harga.*' => 'integer|min:0',
        ]);

        $penjualan = PenjualanModel::findOrFail($id);
        $penjualan->update($request->only(['user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal']));

        // Hapus detail lama, lalu simpan detail baru
        PenjualanDetailModel::where('penjualan_id', $id)->delete();
        foreach ($request->barang_id as $index => $barang_id) {
            PenjualanDetailModel::create([
                'penjualan_id' => $penjualan->penjualan_id,
                'barang_id' => $barang_id,
                'jumlah' => $request->jumlah[$index],
                'harga' => $request->harga[$index]
            ]);
        }

        return redirect('/penjualan')->with('success', 'Transaksi berhasil diperbarui');
    }

        // Menampilkan detail transaksi penjualan
        public function show(string $id)
        {
            $penjualan = PenjualanModel::with(['user', 'details.barang'])->findOrFail($id);
    
            $breadcrumb = (object) [
                'title' => 'Detail Transaksi Penjualan',
                'list' => ['Home', 'Penjualan', 'Detail']
            ];
    
            $page = (object) [
                'title' => 'Detail transaksi penjualan'
            ];
    
            $activeMenu = 'penjualan';
    
            return view('penjualan.show', compact('breadcrumb', 'page', 'penjualan', 'activeMenu'));
        }
    

    // Menghapus transaksi penjualan
    public function destroy(string $id)
    {
        $penjualan = PenjualanModel::find($id);

        if (!$penjualan) {
            return redirect('/penjualan')->with('error', 'Data transaksi tidak ditemukan');
        }

        try {
            PenjualanDetailModel::where('penjualan_id', $id)->delete();
            $penjualan->delete();

            return redirect('/penjualan')->with('success', 'Transaksi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/penjualan')->with('error', 'Terjadi kesalahan saat menghapus transaksi');
        }
    }
    public function create_ajax()
    {
        $kasir = UserModel::select('user_id', 'nama')->get();
        $barang = BarangModel::select('barang_id', 'barang_nama', 'harga_jual')->get();

        return view('penjualan.create_ajax', compact('kasir', 'barang'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id' => 'required|exists:m_user,user_id',
                'pembeli' => 'required|string|max:100',
                'penjualan_kode' => 'required|string|unique:t_penjualan,penjualan_kode',
                'penjualan_tanggal' => 'required|date',
                'barang_id' => 'required|array',
                'barang_id.*' => 'exists:m_barang,barang_id',
                'jumlah' => 'required|array',
                'jumlah.*' => 'integer|min:1'
            ];

            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $penjualan = PenjualanModel::create($request->only(['user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal']));

            foreach ($request->barang_id as $index => $barang_id) {
                $barang = BarangModel::find($barang_id);
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $barang_id,
                    'jumlah' => $request->jumlah[$index],
                    'harga' => $barang->harga_jual ?? 0
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil disimpan'
            ]);
        }
    }

    public function edit_ajax(string $id)
    {
        $penjualan = PenjualanModel::with('details')->find($id);
        $kasir = UserModel::select('user_id', 'nama')->get();
        $barang = BarangModel::select('barang_id', 'barang_nama', 'harga_jual')->get();

        return view('penjualan.edit_ajax', compact('penjualan', 'kasir', 'barang'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id' => 'required|exists:m_user,user_id',
                'pembeli' => 'required|string|max:100',
                'penjualan_kode' => 'required|string|unique:t_penjualan,penjualan_kode,' . $id . ',penjualan_id',
                'penjualan_tanggal' => 'required|date',
                'barang_id' => 'required|array',
                'barang_id.*' => 'exists:m_barang,barang_id',
                'jumlah' => 'required|array',
                'jumlah.*' => 'integer|min:1',
                'harga' => 'required|array',
                'harga.*' => 'integer|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $penjualan = PenjualanModel::findOrFail($id);
            $penjualan->update($request->only(['user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal']));

            PenjualanDetailModel::where('penjualan_id', $id)->delete();
            foreach ($request->barang_id as $index => $barang_id) {
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $barang_id,
                    'jumlah' => $request->jumlah[$index],
                    'harga' => $request->harga[$index]
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil diperbarui'
            ]);
        }
    }

    public function confirm_ajax(string $id)
    {
        $penjualan = PenjualanModel::find($id);

        return view('penjualan.confirm_ajax', ['penjualan' => $penjualan]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $penjualan = PenjualanModel::find($id);
            if ($penjualan) {
                PenjualanDetailModel::where('penjualan_id', $id)->delete();
                $penjualan->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Transaksi berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
    }

    public function show_ajax($id)
    {
        $penjualan = PenjualanModel::with(['user', 'details.barang'])->find($id);
        return view('penjualan.show_ajax', compact('penjualan'));
    }
}
