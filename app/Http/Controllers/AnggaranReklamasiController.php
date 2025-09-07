<?php

namespace App\Http\Controllers;

use App\Models\AnggaranReklamasi;
use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AnggaranReklamasi\CreateAnggaranReklamasiRequest;
use App\Http\Requests\AnggaranReklamasi\UpdateAnggaranReklamasiRequest;
use App\Services\AnggaranReklamasiService;

class AnggaranReklamasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AnggaranReklamasiService $service, Request $request, Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403);
        }

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
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $kategoriAnggaranList = $service->getKategoriAnggaranList($lahan->lahan_id);

        return view('detail-lahan.anggaran-reklamasi.create', [
            'lahan' => $lahan,
            'kategoriAnggaranList' => $kategoriAnggaranList
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AnggaranReklamasiService $service, CreateAnggaranReklamasiRequest $request, Lahan $lahan)
    {
        $validated = $request->validated();
        $validated['lahan_id'] = $lahan->lahan_id;
        $validated['quarter_label'] = $service->generateQuarterLabel(
            $validated['quarter'],
            $validated['tahun'],
            $validated['bulan'],
            $lahan->lahan_id,
            $validated['jenis_anggaran'],
            $validated['kategori_anggaran']
        );

        try {
            $anggaran_reklamasi = AnggaranReklamasi::create($validated);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['general' => $e->getMessage()])->withInput();
        }

        return redirect()->route('lahan.anggaran.index', $lahan)
            ->with('success', 'Anggaran Reklamasi created successfully.');
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lahan $lahan, AnggaranReklamasi $anggaran, AnggaranReklamasiService $service)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($anggaran->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Data not found.');
        }

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
        $validated = $request->validated();

        $validated['lahan_id'] = $lahan->lahan_id;
        $validated['quarter_label'] = $service->generateQuarterLabel(
            $validated['quarter'],
            $validated['tahun'],
            $validated['bulan'],
            $lahan->lahan_id,
            $validated['jenis_anggaran'],
            $validated['kategori_anggaran'],
            $anggaran->anggaran_reklamasi_id
        );

        try {
            $anggaran->update($validated);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['general' => $e->getMessage()])->withInput();
        }

        return redirect()->route('lahan.anggaran.index', $lahan)
            ->with('success', 'Anggaran Reklamasi updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AnggaranReklamasiService $service, Lahan $lahan, AnggaranReklamasi $anggaran)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($anggaran->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Data not found.');
        }

        try {
            $kategori = $anggaran->kategori_anggaran;
            $quarter = $anggaran->quarter;
            $tahun = $anggaran->tahun;
            $bulan = $anggaran->bulan;

            $anggaran->delete();
            
            return redirect()->route('lahan.anggaran.index', $lahan)
                ->with('success', "Anggaran dengan kategori $kategori untuk $quarter $tahun-$bulan berhasil dihapus.");
        } catch (\Exception $e) {
            \Log::error('Anggaran deletion failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['general' => 'Terjadi kesalahan saat menghapus data anggaran.'])
                ->withInput();
        }
    }
}
