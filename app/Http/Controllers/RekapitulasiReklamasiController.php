<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\DataReklamasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\View;
use App\Services\RekapitulasiReklamasiService;
use App\Http\Requests\RekapitulasiReklamasi\CreateRekapitulasiReklamasiRequest;
use App\Http\Requests\RekapitulasiReklamasi\UpdateRekapitulasiReklamasiRequest;
use Barryvdh\DomPDF\Facade\PDF;

class RekapitulasiReklamasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Lahan $lahan)
    {
        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $rekapitulasi_reklamasi = DataReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rekapitulasi')
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
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $rekapitulasi_reklamasi = DataReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rekapitulasi')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        return view('detail-lahan.rekapitulasi-reklamasi.create', compact('lahan', 'rekapitulasi_reklamasi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRekapitulasiReklamasiRequest $request, Lahan $lahan)
    {
        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validated();

        $validated['lahan_id'] = $lahan->lahan_id;

        $validated['type'] = 'rekapitulasi';

        // Create the Rekapitulasi Reklamasi
        $rekapitulasi_reklamasi = DataReklamasi::create($validated);

        return redirect()->route('lahan.rekapitulasi-reklamasi.index', $lahan->lahan_id)
                        ->with('success', 'Rekapitulasi Pelaksanaan Reklamasi ' . $validated['tahun'] . ' berhasil ditambahkan.');
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
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $rekapitulasi_reklamasi_collection = DataReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rekapitulasi')
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
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validated();

        $validated['lahan_id'] = $lahan->lahan_id;

        $validated['type'] = 'rekapitulasi';

        // Update the Rekapitulasi Reklamasi
        $rekapitulasi_reklamasi->update($validated);

        return redirect()->route('lahan.rekapitulasi-reklamasi.index', $lahan->lahan_id)
                        ->with('success', 'Rekapitulasi Pelaksanaan Reklamasi ' . $validated['tahun'] . ' berhasil diperbarui.');
    }

    public function generatePDF(Lahan $lahan, Request $request, RekapitulasiReklamasiService $pdfService)

    {
        $tahun = $request->input('tahun');

        // Check if theres data for the specified lahan and year
        $hasData = DataReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rekapitulasi')
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
