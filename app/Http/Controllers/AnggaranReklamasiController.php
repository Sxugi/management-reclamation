<?php

namespace App\Http\Controllers;

use App\Models\AnggaranReklamasi;
use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\AnggaranReklamasi\StoreAnggaranReklamasiRequest;
use App\Http\Requests\AnggaranReklamasi\UpdateAnggaranReklamasiRequest;
use App\Services\AnggaranReklamasiService;

class AnggaranReklamasiController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(AnggaranReklamasiService $service, Request $request, Lahan $lahan)
    {
        $this->authorize('viewAny', [AnggaranReklamasi::class, $lahan]);

        $tabAktif = request()->query('tab', 'actual');
        $jenisArr = ['actual', 'projection', 'forecast'];

        $hasFilter = $request->filled('startYear') ||
                    $request->filled('endYear') ||
                    $request->filled('startMonth') ||
                    $request->filled('endMonth') ||
                    $request->filled('minNominal') ||
                    $request->filled('maxNominal') ||
                    $request->filled('minTotal') ||
                    $request->filled('kategori_anggaran') ||
                    $request->filled('startQuarter') ||
                    $request->filled('endQuarter') ||
                    $request->filled('tableSortColumn') || 
                    $request->filled('tableSortDirection');

        foreach ($jenisArr as $jenis) {
            if ($tabAktif === $jenis) {
                if ($hasFilter) {
                    $data[$jenis] = $service->getFilteredData($request, $lahan, $jenis);
                } else {
                    $data[$jenis] = $service->getByJenis($lahan->lahan_id, $jenis, 12);
                }
                $sortCol = $request->get('tableSortColumn');
                $rowspanMap[$jenis] = $service->generateRowspanMap($data[$jenis], $sortCol);
                $quarterTotals[$jenis] = $service->getQuarterTotals($request, $lahan, $jenis);
            }
        }

        $kategoriAnggaranList = $service->getKategoriAnggaranList($lahan->lahan_id);

        return view('detail-lahan.anggaran-reklamasi.index', [
            'lahan' => $lahan,
            'actual' => $data['actual'] ?? null,
            'projection' => $data['projection'] ?? null,
            'forecast' => $data['forecast'] ?? null,
            'rowspanMap' => $rowspanMap,
            'quarterTotals' => $quarterTotals,
            'tab_aktif' => $tabAktif,
            'kategoriAnggaranList' => $kategoriAnggaranList,
            'hasFilter' => $hasFilter
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(AnggaranReklamasiService $service, Lahan $lahan)
    {
        $this->authorize('create', [AnggaranReklamasi::class, $lahan]);

        $kategoriAnggaranList = $service->getKategoriAnggaranList($lahan->lahan_id);

        return view('detail-lahan.anggaran-reklamasi.create', [
            'lahan' => $lahan,
            'kategoriAnggaranList' => $kategoriAnggaranList
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AnggaranReklamasiService $service, StoreAnggaranReklamasiRequest $request, Lahan $lahan)
    {
        $this->authorize('create', [AnggaranReklamasi::class, $lahan]);

        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $validated['lahan_id'] = $lahan->lahan_id;
            $validated['quarter_label'] = $service->generateQuarterLabel(
                $validated['quarter'],
                $validated['tahun'],
                $validated['bulan'],
                $lahan->lahan_id,
                $validated['jenis_anggaran'],
                $validated['kategori_anggaran_id']
            );

            $anggaran = AnggaranReklamasi::create($validated);

            DB::commit();

            return redirect()->route('lahan.anggaran.index', $lahan)
                ->with('success', 'Anggaran Reklamasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create anggaran reklamasi', [
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan anggaran:  ' . $e->getMessage());
        }
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lahan $lahan, AnggaranReklamasi $anggaran, AnggaranReklamasiService $service)
    {
        // Validate anggaran belongs to lahan
        if ($anggaran->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Anggaran tidak ditemukan untuk lahan ini.');
        }

        $this->authorize('update', $anggaran);

        $kategoriAnggaranList = $service->getKategoriAnggaranList($lahan->lahan_id);

        return view('detail-lahan.anggaran-reklamasi.edit', [
            'lahan' => $lahan,
            'anggaran' => $anggaran,
            'kategoriAnggaranList' => $kategoriAnggaranList
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AnggaranReklamasiService $service, UpdateAnggaranReklamasiRequest $request, Lahan $lahan, AnggaranReklamasi $anggaran)
    {
        // Validate anggaran belongs to lahan
        if ($anggaran->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Anggaran tidak ditemukan untuk lahan ini.');
        }

        $this->authorize('update', $anggaran);

        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $validated['quarter_label'] = $service->generateQuarterLabel(
                $validated['quarter'],
                $validated['tahun'],
                $validated['bulan'],
                $lahan->lahan_id,
                $validated['jenis_anggaran'],
                $validated['kategori_anggaran_id'],
                $anggaran->anggaran_reklamasi_id
            );

            $anggaran->update($validated);

            DB::commit();

            return redirect()->route('lahan.anggaran.index', $lahan)
                ->with('success', 'Anggaran Reklamasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update anggaran reklamasi', [
                'anggaran_reklamasi_id' => $anggaran->anggaran_reklamasi_id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui anggaran: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AnggaranReklamasiService $service, Lahan $lahan, AnggaranReklamasi $anggaran)
    {
        // Validate anggaran belongs to lahan
        if ($anggaran->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Anggaran tidak ditemukan untuk lahan ini.');
        }

        $this->authorize('delete', $anggaran);

        DB::beginTransaction();
        try {
            $kategoriNama = $anggaran->kategori->nama_kategori ?? 'Unknown';
            $quarter = $anggaran->quarter;
            $tahun = $anggaran->tahun;
            $bulan = $anggaran->bulan;
            $jenisAnggaran = $anggaran->jenis_anggaran;
            $oldLabel = $anggaran->quarter_label;

            $anggaran->delete();

            // Regenerate quarter labels for remaining items
            $service->regenerateQuarterLabelAfterDeletion(
                $quarter,
                $lahan->lahan_id,
                $jenisAnggaran,
                $oldLabel
            );

            DB::commit();
            
            return redirect()->route('lahan.anggaran.index', $lahan)
                ->with('success', "Anggaran dengan kategori $kategoriNama untuk $quarter $tahun-$bulan berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to delete anggaran reklamasi', [
                'anggaran_id' => $anggaran->anggaran_reklamasi_id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('lahan.anggaran. index', $lahan)
                ->with('error', 'Gagal menghapus anggaran: ' . $e->getMessage());
        }
    }

    /**
     * Export Anggaran ke Excel
     */
    public function export(AnggaranReklamasiService $service, Lahan $lahan)
    {
        // Authorize user
        $this->authorize('viewAny', [AnggaranReklamasi::class, $lahan]);

        try {
            // Call service to export
            return $service->exportExcel($lahan);
            
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Gagal export anggaran reklamasi', [
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return back with error message
            return back()->with('error', 'Terjadi kesalahan saat mengexport data: ' . $e->getMessage());
        }
    }
}
