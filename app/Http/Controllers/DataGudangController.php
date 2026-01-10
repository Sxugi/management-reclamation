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

        $hasFilter = $request->filled('startDate') ||
                        $request->filled('endDate') ||
                        $request->filled('jenisBarang') ||
                        $request->filled('statusBarang');

        return view('detail-lahan.gudang.index', [
            'gudang' => $gudang,
            'lahan' => $lahan,
            'hasFilter' => $hasFilter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Lahan $lahan)
    {
        $this->authorize('create', [DataGudang::class, $lahan]);

        return view('detail-lahan.gudang.create', [
            'lahan' => $lahan,
        ]);
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

            return redirect()->route('lahan.gudang.index', $lahan)
                            ->with('success', 'Data Gudang berhasil ditambahkan');
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

        return view('detail-lahan.gudang.edit', [
            'gudang' => $gudang, 
            'lahan' => $lahan,
        ]);
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

            return redirect()->route('lahan.gudang.index', $lahan)
                        ->with('success', 'Data Gudang berhasil diperbarui');
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
}