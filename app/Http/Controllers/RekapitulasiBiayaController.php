<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\BiayaReklamasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RekapitulasiBiaya\StoreRekapitulasiBiayaRequest;
use App\Http\Requests\RekapitulasiBiaya\UpdateRekapitulasiBiayaRequest;
use App\Services\RekapitulasiBiayaService;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RekapitulasiBiayaController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Lahan $lahan)
    {
        // Check if user owns this lahan
        $this->authorize('viewAny', [BiayaReklamasi::class, $lahan]);

        $rekapitulasi_biaya = BiayaReklamasi::with('detailBiayaReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rekapitulasi')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        $tahun_aktif = request('tahun', $lahan->tahun_awal);
        $currency = $rekapitulasi_biaya[$tahun_aktif]->currency ?? 'IDR';

        return view('detail-lahan.rekapitulasi-biaya.index', compact('rekapitulasi_biaya', 'lahan', 'tahun_aktif', 'currency'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Lahan $lahan)
    {
        // Check if user owns this lahan
        $this->authorize('create', [BiayaReklamasi::class, $lahan]);

        $rekapitulasi_biaya = BiayaReklamasi::with('detailBiayaReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rekapitulasi')
            ->orderBy('tahun')
            ->get();

        $tahun_aktif = request('tahun', $lahan->tahun_awal);
        $currency = request('currency', 'IDR');

        return view('detail-lahan.rekapitulasi-biaya.create', compact('lahan', 'rekapitulasi_biaya', 'tahun_aktif', 'currency'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRekapitulasiBiayaRequest $request, Lahan $lahan)
    {
        // Check if user owns this lahan
        $this->authorize('create', [BiayaReklamasi::class, $lahan]);

        // Validate the request
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $rekapitulasiBiaya = BiayaReklamasi::create([
                'lahan_id'    => $lahan->lahan_id,
                'tahun'       => $validated['tahun'],
                'tipe'        => 'rekapitulasi',
                'currency'    => $validated['currency'],
                'subtotal_1'  => $validated['subtotal_1'],
                'subtotal_2'  => $validated['subtotal_2'],
            ]);

            foreach ($validated['detail'] as $data) {
                $rekapitulasiBiaya->detailBiayaReklamasi()->create([
                    'kegiatan' => $data['kegiatan'],
                    'kategori' => $data['kategori'],
                    'biaya'    => $data['biaya'],
                ]);
            }

            DB::commit();

            return redirect()->route('lahan.rekapitulasi-biaya.index', $lahan->lahan_id)
                ->with('success', 'Rekapitulasi Biaya tahun ' . $validated['tahun'] . ' berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating rekapitulasi biaya', [
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
    public function edit(Lahan $lahan, BiayaReklamasi $rekapitulasi_biaya)
    {
        // Verify this rekapitulasi_biaya belongs to this lahan
        if ($rekapitulasi_biaya->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Rekapitulasi biaya not found for this lahan.');
        }

        // Check if user owns this lahan
        $this->authorize('update', $rekapitulasi_biaya);

        $rekapitulasi_biaya_collection = BiayaReklamasi::with('detailBiayaReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rekapitulasi')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        $tahun_aktif = request('tahun', $rekapitulasi_biaya->tahun);
        $currency = request('currency', $rekapitulasi_biaya->currency ?? 'IDR');

        return view('detail-lahan.rekapitulasi-biaya.edit', [
            'rekapitulasi_biaya' => $rekapitulasi_biaya_collection,
            'lahan' => $lahan,
            'tahun_aktif' => $tahun_aktif,
            'currency' => $currency,
            'rekapitulasi_biaya_item' => $rekapitulasi_biaya,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRekapitulasiBiayaRequest $request, Lahan $lahan, BiayaReklamasi $rekapitulasi_biaya)
    {
        // Verify this rekapitulasi_biaya belongs to this lahan
        if ($rekapitulasi_biaya->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Rekapitulasi biaya not found for this lahan.');
        }

        // Check if user owns this lahan
        $this->authorize('update', $rekapitulasi_biaya);

        // Validate the request
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $rekapitulasi_biaya->update([
                'tahun'      => $validated['tahun'],
                'currency'    => $validated['currency'],
                'subtotal_1' => $validated['subtotal_1'],
                'subtotal_2' => $validated['subtotal_2'],
            ]);

            $rekapitulasi_biaya->detailBiayaReklamasi()->delete();

            foreach ($validated['detail'] as $data) {
                $rekapitulasi_biaya->detailBiayaReklamasi()->create([
                    'kegiatan' => $data['kegiatan'],
                    'kategori' => $data['kategori'],
                    'biaya'    => $data['biaya'],
                ]);
            }

            DB::commit();

            return redirect()->route('lahan.rekapitulasi-biaya.index', $lahan->lahan_id)
                ->with('success', 'Rekapitulasi Biaya tahun ' . $validated['tahun'] . ' berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating rekapitulasi biaya', [
                'user' => Auth::user()->username,
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }
    }

    public function generatePDF(Lahan $lahan, Request $request, RekapitulasiBiayaService $pdfService)
    {   
        $this->authorize('generatePDF', [BiayaReklamasi::class, $lahan]);
        
        $tahun = $request->input('tahun');

        // Check if theres data for the specified lahan and year
        $hasData = BiayaReklamasi::with('detailBiayaReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rekapitulasi')
            ->where('tahun', $tahun)
            ->exists();
            
        if (!$hasData) {
            return back()->with('error', "Tidak ada data rekapitulasi biaya untuk tahun {$tahun}.");
        }
        
        try {
            return $pdfService->generate($lahan, $tahun);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }
}