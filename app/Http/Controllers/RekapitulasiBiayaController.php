<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\BiayaReklamasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\View;
use App\Services\RekapitulasiBiayaService;
use App\Http\Requests\RekapitulasiBiaya\CreateRekapitulasiBiayaRequest;
use App\Http\Requests\RekapitulasiBiaya\UpdateRekapitulasiBiayaRequest;
use Barryvdh\DomPDF\Facade\PDF;

class RekapitulasiBiayaController extends Controller
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

        $rekapitulasi_biaya = BiayaReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rekapitulasi')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        return view('detail-lahan.rekapitulasi-biaya.index', compact('rekapitulasi_biaya', 'lahan'));
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

        $rekapitulasi_biaya = BiayaReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rekapitulasi')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        return view('detail-lahan.rekapitulasi-biaya.create', compact('lahan', 'rekapitulasi_biaya'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRekapitulasiBiayaRequest $request, Lahan $lahan)
    {
        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validated();

        $validated['lahan_id'] = $lahan->lahan_id;

        $validated['type'] = 'rekapitulasi';

        // Create the Rekapitulasi Biaya
        $rekapitulasi_biaya = BiayaReklamasi::create($validated);

        return redirect()->route('lahan.rekapitulasi-biaya.index', $lahan->lahan_id)
                        ->with('success', 'Rekapitulasi Biaya ' . $validated['tahun'] . ' berhasil ditambahkan.');
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
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $rekapitulasi_biaya_collection = BiayaReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rekapitulasi')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        $tahun_aktif = request('tahun', $rekapitulasi_biaya->tahun);

        return view('detail-lahan.rekapitulasi-biaya.edit', [
            'rekapitulasi_biaya' => $rekapitulasi_biaya_collection,
            'lahan' => $lahan,
            'tahun_aktif' => $tahun_aktif,
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
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validated();

        $validated['lahan_id'] = $lahan->lahan_id;

        $validated['type'] = 'rekapitulasi';

        $rekapitulasi_biaya->update($validated);

        return redirect()->route('lahan.rekapitulasi-biaya.index', $lahan->lahan_id)
                         ->with('success', 'Rekapitulasi Biaya ' . $validated['tahun'] . ' berhasil diperbarui.');
    }

    public function generatePDF(Lahan $lahan, Request $request, RekapitulasiBiayaService $pdfService)
    {   
        $tahun = $request->input('tahun');

        // Check if theres data for the specified lahan and year
        $hasData = BiayaReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rekapitulasi')
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