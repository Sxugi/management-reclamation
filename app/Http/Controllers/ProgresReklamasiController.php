<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgresReklamasi\StoreProgresReklamasiRequest;
use App\Http\Requests\ProgresReklamasi\UpdateProgresReklamasiRequest;
use App\Models\Plot;
use App\Models\ProgresReklamasi;
use App\Models\JenisAktivitas;
use App\Models\KategoriAktivitas;
use App\Services\ProgresReklamasiService;
use Illuminate\Http\Request;

class ProgresReklamasiController extends Controller
{
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
        try {
            $this->service->create($plot, $request->validated());
            
            return redirect()->route('plot.show', $plot)
                            ->with('success', 'Progres berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan progres: ' . $e->getMessage()]);
        }
    }

    /**
     * Show edit progres form
     */
    public function edit(Plot $plot, ProgresReklamasi $progres)
    {
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
        $this->service->update($progres, $request->validated());

        return redirect()->route('plot.show', $progres->plot)
                         ->with('success', 'Progres berhasil diperbarui.');
    }

    /**
     * Delete progres
     */
    public function destroy(Plot $plot, ProgresReklamasi $progres)
    {
        try {
            $this->service->delete($progres);

            return redirect()->route('plot.show', $plot)
                             ->with('success', 'Progres berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus progres: ' . $e->getMessage()]);
        }
    }
}