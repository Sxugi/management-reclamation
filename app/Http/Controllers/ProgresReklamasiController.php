<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgresReklamasi\StoreProgresReklamasiRequest;
use App\Http\Requests\ProgresReklamasi\UpdateProgresReklamasiRequest;
use App\Models\Plot;
use App\Models\ProgresReklamasi;
use App\Models\JenisAktivitas;
use App\Models\JenisPohon;
use App\Services\ProgresReklamasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProgresReklamasiController extends Controller
{
    use AuthorizesRequests;

    protected ProgresReklamasiService $service;

    public function __construct(ProgresReklamasiService $service)
    {
        $this->service = $service;
    }

    /**
     * Show create progres form
     */
    public function create(Plot $plot, Request $request)
    {
        $plot->load('lahan');

        if (!$plot->lahan) {
            abort(404, 'Lahan not found for this plot');
        }

        // Check authorization
        $this->authorize('create', [ProgresReklamasi::class, $plot]);

        $masterPohon = JenisPohon::orderBy('nama_pohon')->get()->groupBy('kategori');

        // Get kategori & aktivitas from query params if available
        $kategori = $request->query('kategori');
        $aktivitas = $request->query('aktivitas');
        $jenisAktivitas = null;

        if ($kategori && $aktivitas) {
            $jenisAktivitas = JenisAktivitas::whereHas('kategoriAktivitas', function($q) use ($kategori) {
                $q->where('field', $kategori);
            })->where('field', $aktivitas)->first();
        }

        return view('detail-lahan.plot.progres.create', [
            'plot' => $plot,
            'lahan' => $plot->lahan,
            'kategori' => $kategori,
            'aktivitas' => $aktivitas,
            'jenisAktivitas' => $jenisAktivitas,
            'data' => [],
            'isEdit' => false,
            'masterPohon' => $masterPohon,
        ]);
    }

    /**
     * Store progres
     */
    public function store(StoreProgresReklamasiRequest $request, Plot $plot)
    {
        // Load lahan relation
        $plot->load('lahan');
        
        // Check authorization
        $this->authorize('create', [ProgresReklamasi::class, $plot]);

        DB::beginTransaction();
        try {
            $progres = $this->service->create($plot, $request->validated());

            DB::commit();
            
            return redirect()->route('plot.show', $plot)
                            ->with('success', 'Progres berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create progres reklamasi', [
                'plot_id' => $plot->plot_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan progres:  ' . $e->getMessage());
        }
    }

    /**
     * Show edit progres form
     */
    public function edit(Plot $plot, ProgresReklamasi $progres)
    {
        // Validate progres belongs to plot
        if ($progres->plot_id !== $plot->plot_id) {
            abort(404, 'Progres tidak ditemukan untuk plot ini.');
        }

        // Check authorization
        $this->authorize('update', $progres);

        // Load relations
        $progres->load(['jenisAktivitas.kategoriAktivitas', 'fieldValues.fieldDefinition', 'dokumentasi', 'plot.lahan']);

        // Load relations
        $jenisAktivitas = $progres->jenisAktivitas;
        $kategori = $jenisAktivitas?->kategoriAktivitas?->field;
        $aktivitas = $jenisAktivitas?->field;
        $masterPohon = JenisPohon::orderBy('nama_pohon')->get()->groupBy('kategori');

        // Prefill existing field values
        $data = [];
        foreach ($progres->fieldValues as $fv) {
            $data[$fv->fieldDefinition->field_key] = $fv->field_value;
        }
        // Also prefill tanggal and catatan
        $data['tanggal'] = $progres->tanggal?->format('Y-m-d');
        $data['catatan'] = $progres->catatan;

        $existingFiles = [];
        foreach ($progres->dokumentasi as $dok) {
            $existingFiles['dokumentasi'][] = [
                'id' => $dok->progres_dokumentasi_id,
                'url' => $dok->image_path,
                'path' => $dok->image_path,
            ];
        }

        return view('detail-lahan.plot.progres.edit', [
            'progres' => $progres,
            'plot' => $progres->plot,
            'kategori' => $kategori,
            'aktivitas' => $aktivitas,
            'jenisAktivitas' => $jenisAktivitas,
            'data' => $data,
            'isEdit' => true,
            'existingFiles' => $existingFiles,
            'masterPohon' => $masterPohon,
        ]);
    }

    /**
     * Update progres
     */
    public function update(UpdateProgresReklamasiRequest $request, Plot $plot, ProgresReklamasi $progres)
    {
        // Validate progres belongs to plot
        if ($progres->plot_id !== $plot->plot_id) {
            abort(404, 'Progres tidak ditemukan untuk plot ini.');
        }

        // Check authorization
        $this->authorize('update', $progres);

        DB::beginTransaction();
        try {
            $this->service->update($progres, $request->validated());

            DB::commit();

            return redirect()->route('plot.show', $progres->plot)
                            ->with('success', 'Progres berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update progres reklamasi', [
                'progres_id' => $progres->progres_reklamasi_id,
                'plot_id' => $plot->plot_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui progres: ' . $e->getMessage());
        }
    }

    /**
     * Delete progres
     */
    public function destroy(Plot $plot, ProgresReklamasi $progres)
    {
        // Validate progres belongs to plot
        if ($progres->plot_id !== $plot->plot_id) {
            abort(404, 'Progres tidak ditemukan untuk plot ini.');
        }

        // Check authorization
        $this->authorize('delete', $progres);

        DB::beginTransaction();
        try {
            // Store info for success message
            $tanggal = $progres->tanggal?->format('d/m/Y');
            $aktivitas = $progres->jenisAktivitas?->field ??  'Unknown';

            $this->service->delete($progres);

            DB::commit();

            return redirect()->route('plot.show', $plot)
                             ->with('success', 'Progres berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to delete progres reklamasi', [
                'progres_id' => $progres->progres_reklamasi_id,
                'plot_id' => $plot->plot_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('plot.show', $plot)
                ->with('error', 'Gagal menghapus progres: ' . $e->getMessage());
        }
    }

    /**
     * Get total planted trees for monitoring validation
     * 
     * @param Plot $plot
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlantedTrees(Plot $plot, Request $request)
    {
        // Check authorization
        $this->authorize('view', $plot);

        $jenisPohonId = $request->input('jenis_pohon_id');
        
        if (!$jenisPohonId) {
            return response()->json([
                'success' => false,
                'total' => 0,
                'message' => 'Jenis pohon ID is required'
            ], 400);
        }

        try {
            $total = DB::table('data_pohon_realisasi as dpr')
                ->join('pohon as p', 'dpr.pohon_id', '=', 'p.pohon_id')
                ->where('dpr.plot_id', $plot->plot_id)
                ->where('p.jenis_pohon_id', $jenisPohonId)
                ->sum('dpr.jumlah_batang');

            return response()->json([
                'success' => true,
                'total' => (int)($total ?? 0),
                'plot_id' => $plot->plot_id,
                'plot_name' => $plot->nama_plot,
                'jenis_pohon_id' => $jenisPohonId,
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting planted trees for monitoring', [
                'plot_id' => $plot->plot_id,
                'jenis_pohon_id' => $jenisPohonId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'total' => 0,
                'message' => 'Failed to fetch planted trees data'
            ], 500);
        }
    }

    /**
     * Export progres to Excel
     */
    public function export(Plot $plot)
    {
        // Check authorization
        $this->authorize('view', $plot); 

        try {
            return $this->service->exportExcel($plot);
        } catch (\Exception $e) {
            \Log::error('Failed to export progres', [
                'plot_id' => $plot->plot_id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }
}