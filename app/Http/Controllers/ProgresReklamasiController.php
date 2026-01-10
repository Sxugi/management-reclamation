<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgresReklamasi\StoreProgresReklamasiRequest;
use App\Http\Requests\ProgresReklamasi\UpdateProgresReklamasiRequest;
use App\Models\Plot;
use App\Models\ProgresReklamasi;
use App\Models\JenisAktivitas;
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