<?php

namespace App\Http\Controllers;

use App\Models\DataGudang;
use App\Models\Lahan;
use Illuminate\Http\Request;
use App\Http\Requests\Gudang\StoreDataGudangRequest;
use App\Http\Requests\Gudang\UpdateDataGudangRequest;
use App\Services\DataGudangService;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DataGudangController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Lahan $lahan)
    {
        $this->authorize('viewAny', [DataGudang::class, $lahan]);

        $gudang = DataGudangService::getFilteredData($request, $lahan);

        $rekapStok = DataGudangService::getStockSummary($lahan);

        $hasFilter = $request->filled('startDate') ||
                        $request->filled('endDate') ||
                        $request->filled('jenisBarang') ||
                        $request->filled('statusBarang');

        return view('detail-lahan.gudang.index', [
            'gudang' => $gudang,
            'lahan' => $lahan,
            'hasFilter' => $hasFilter,
            'rekapStok' => $rekapStok,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Lahan $lahan)
    {
        $this->authorize('create', [DataGudang::class, $lahan]);

        $existingItems = DataGudang::where('lahan_id', $lahan->lahan_id)
            ->select('jenis_barang', 'nama_barang', 'sku', 'satuan')
            ->orderBy('nama_barang')
            ->get()
            ->groupBy('jenis_barang')
            ->map(fn($items) => $items->unique('nama_barang')->map(fn($item) => [
                'nama_barang' => $item->nama_barang,
                'sku' => $item->sku ??  '',
                'satuan' => $item->satuan,
            ])->values()->toArray())
            ->toArray();

        return view('detail-lahan.gudang.create', compact('lahan', 'existingItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDataGudangRequest $request, Lahan $lahan)
    {
        $this->authorize('create', [DataGudang::class, $lahan]);

        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $validated['lahan_id'] = $lahan->lahan_id;
            
            DataGudang::create($validated);
            DB::commit();

            $action = $validated['jenis_transaksi'] === 'MASUK' ? 'diterima' : 'dikeluarkan';
            
            return redirect()->route('lahan.gudang.index', $lahan)
                            ->with('success', "Data Gudang berhasil disimpan. Barang {$action}.");
        } catch (\Exception $e) {
            DB::rollBack(); 
            \Log::error('Failed to create gudang', [
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data gudang: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lahan $lahan, DataGudang $gudang) // Sesuaikan dengan route parameter
    {
        // Check if gudang belongs to this lahan
        if ($gudang->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Data not found.');
        }

        $this->authorize('update', $gudang);

        $rawItems = DataGudang:: where('lahan_id', $lahan->lahan_id)
            ->select('jenis_barang', 'nama_barang', 'sku', 'satuan')
            ->orderBy('nama_barang')
            ->get();

        $existingItems = $rawItems->groupBy('jenis_barang')
            ->map(function($items) {
                return $items->unique('nama_barang')->map(function($item) {
                    return [
                        'nama_barang' => $item->nama_barang,
                        'sku' => $item->sku ?? '',
                        'satuan' => $item->satuan,
                    ];
                })->values()->toArray();
            })
            ->toArray();

        return view('detail-lahan.gudang.edit', compact('gudang', 'lahan', 'existingItems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDataGudangRequest $request, Lahan $lahan, DataGudang $gudang)
    {
        if ($gudang->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Data not found.');
        }

        $this->authorize('update', $gudang);

        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $gudang->update($validated);

            DB::commit();

            $action = ($validated['jenis_transaksi'] ?? $gudang->jenis_transaksi) === 'MASUK' ? 'diterima' : 'dikeluarkan';

            return redirect()->route('lahan.gudang.index', $lahan)
                        ->with('success', "Data Gudang berhasil diperbarui. Barang {$action}.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update gudang', [
                'gudang_id' => $gudang->data_gudang_id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data gudang: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lahan $lahan, DataGudang $gudang)
    {
        // Check if gudang belongs to this lahan
        if ($gudang->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Data not found.');
        }

        $this->authorize('delete', $gudang);

        DB::beginTransaction();
        try {
            // Prevent deletion if it would cause negative stock
            if ($gudang->jenis_transaksi === 'MASUK') {
                // Calculate stock if this MASUK transaction is deleted
                $stokAvailable = DataGudang::where('lahan_id', $lahan->lahan_id)
                    ->where('nama_barang', $gudang->nama_barang)
                    ->where('data_gudang_id', '!=', $gudang->data_gudang_id)
                    ->selectRaw("SUM(CASE WHEN jenis_transaksi = 'MASUK' THEN jumlah_barang ELSE -jumlah_barang END) as sisa")
                    ->value('sisa') ?? 0;

                // If stock becomes negative, reject deletion
                if ($stokAvailable < 0) {
                    DB::rollBack();
                    return redirect()
                        ->route('lahan.gudang.index', $lahan)
                        ->with('error', "Data Tidak Bisa Dihapus! Transaksi ini sudah digunakan.  Stok akan menjadi negatif ({$stokAvailable} {$gudang->satuan}) jika dihapus.");
                }
            }
            $gudang->delete();

            DB::commit();

            return redirect()->route('lahan.gudang.index', $lahan)
                            ->with('success', 'Data Gudang berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to delete gudang', [
                'gudang_id' => $gudang->data_gudang_id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('lahan.gudang.index', $lahan)
                            ->with('error', 'Gagal menghapus data gudang: ' . $e->getMessage());
        }
    }

    /**
     * Export Excel Data Gudang
     */
    public function export(Lahan $lahan)
    {
        $this->authorize('viewAny', [DataGudang::class, $lahan]);

        try {
            return DataGudangService::exportExcel($lahan);
        } catch (\Exception $e) {
            \Log::error('Failed to export data gudang', [
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }

    /**
     * Check available stock for a specific item
     */
    public function checkStock(Request $request, Lahan $lahan)
    {
        $namaBarang = $request->input('nama_barang');
        $excludeId = $request->input('exclude_id'); // For edit mode

        if (!$namaBarang) {
            return response()->json([
                'error' => 'Nama barang tidak boleh kosong',
                'stok' => 0
            ], 400);
        }

        $query = DataGudang::where('lahan_id', $lahan->lahan_id)
            ->where('nama_barang', $namaBarang);

        if ($excludeId) {
            $query->where('data_gudang_id', '!=', $excludeId);
        }

        $stok = $query->selectRaw("
            SUM(CASE 
                WHEN jenis_transaksi = 'MASUK' THEN jumlah_barang 
                ELSE -jumlah_barang 
            END) as sisa
        ")->value('sisa') ?? 0;

        return response()->json([
            'success' => true,
            'stok' => max(0, $stok), // Ensure non-negative
            'nama_barang' => $namaBarang,
            'satuan' => $query->first()?->satuan ?? 'unit'
        ]);
    }
}