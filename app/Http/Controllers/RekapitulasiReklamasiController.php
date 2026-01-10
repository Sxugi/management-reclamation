<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\DataReklamasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RekapitulasiReklamasi\StoreRekapitulasiReklamasiRequest;
use App\Http\Requests\RekapitulasiReklamasi\UpdateRekapitulasiReklamasiRequest;
use App\Services\RekapitulasiReklamasiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RekapitulasiReklamasiController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Lahan $lahan)
    {
        // Check if user owns this lahan
        $this->authorize('viewAny', [DataReklamasi::class, $lahan]);

        $rekapitulasi_reklamasi = DataReklamasi::with('detailDataReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rekapitulasi')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        return view('detail-lahan.rekapitulasi-reklamasi.index', compact('rekapitulasi_reklamasi', 'lahan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Lahan $lahan)
    {
        // Check if user owns this lahan
        $this->authorize('create', [DataReklamasi::class, $lahan]);

        $rekapitulasi_reklamasi = DataReklamasi::with('detailDataReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rekapitulasi')
            ->orderBy('tahun')
            ->get();

        return view('detail-lahan.rekapitulasi-reklamasi.create', compact('lahan', 'rekapitulasi_reklamasi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRekapitulasiReklamasiRequest $request, Lahan $lahan)
    {
        // Check if user owns this lahan
        $this->authorize('create', [DataReklamasi::class, $lahan]);

        // Validate the request
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $dataReklamasi = DataReklamasi::create([
                'lahan_id' => $lahan->lahan_id,
                'tahun' => $validated['tahun'],
                'tipe' => 'rekapitulasi',
            ]);

            foreach ($validated['detail'] as $data) {
                $dataReklamasi->detailDataReklamasi()->create([
                    'kegiatan' => $data['kegiatan'],
                    'kategori' => $data['kategori'],
                    'volume' => $data['volume'],
                    'satuan' => $data['satuan'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('lahan.rekapitulasi-reklamasi.index', $lahan->lahan_id)
                ->with('success', 'Rekapitulasi Reklamasi tahun ' . $validated['tahun'] . ' berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating rekapitulasi reklamasi', [
                'user' => Auth::user()->username,
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lahan $lahan, DataReklamasi $rekapitulasi_reklamasi)
    {
        // Verify this rekapitulasi_reklamasi belongs to this lahan
        if ($rekapitulasi_reklamasi->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Rekapitulasi reklamasi not found for this lahan.');
        }

        // Check if user owns this lahan
        $this->authorize('update', $rekapitulasi_reklamasi);

        $rekapitulasi_reklamasi_collection = DataReklamasi::with('detailDataReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rekapitulasi')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        $tahun_aktif = request('tahun', $rekapitulasi_reklamasi->tahun);

        return view('detail-lahan.rekapitulasi-reklamasi.edit', [
            'rekapitulasi_reklamasi' => $rekapitulasi_reklamasi_collection,
            'lahan' => $lahan,
            'tahun_aktif' => $tahun_aktif,
            'rekapitulasi_reklamasi_item' => $rekapitulasi_reklamasi,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRekapitulasiReklamasiRequest $request, Lahan $lahan, DataReklamasi $rekapitulasi_reklamasi)
    {
        // Verify this rekapitulasi_reklamasi belongs to this lahan
        if ($rekapitulasi_reklamasi->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Rekapitulasi reklamasi not found for this lahan.');
        }

        // Check if user owns this lahan
        $this->authorize('update', $rekapitulasi_reklamasi);

        // Validate the request
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $rekapitulasi_reklamasi->update([
                'tahun' => $validated['tahun'],
            ]);

            $rekapitulasi_reklamasi->detailDataReklamasi()->delete();

            foreach ($validated['detail'] as $data) {
                if (isset($data['volume']) && (float)$data['volume'] > 0) {
                    $rekapitulasi_reklamasi->detailDataReklamasi()->create([
                        'kegiatan' => $data['kegiatan'],
                        'kategori' => $data['kategori'],
                        'volume' => $data['volume'],
                        'satuan' => $data['satuan'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('lahan.rekapitulasi-reklamasi.index', $lahan->lahan_id)
                ->with('success', 'Rekapitulasi Reklamasi ' . $validated['tahun'] . ' berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating rekapitulasi reklamasi', [
                'user' => Auth::user()->username,
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }
    }

    public function generatePDF(Lahan $lahan, Request $request, RekapitulasiReklamasiService $pdfService)

    {
        $this->authorize('generatePDF', [DataReklamasi::class, $lahan]);
        
        $tahun = $request->input('tahun');

        // Check if theres data for the specified lahan and year
        $hasData = DataReklamasi::with('detailDataReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rekapitulasi')
            ->where('tahun', $tahun)
            ->exists();

        if (!$hasData) {
            return back()->with('error', "Tidak ada data rekapitulasi pelaksanaan reklamasi untuk tahun {$tahun}.");
        }

        try {
            return $pdfService->generate($lahan, $tahun);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }
}
