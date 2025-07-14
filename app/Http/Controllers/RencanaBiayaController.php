<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\BiayaReklamasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\View;
use App\Services\RencanaReklamasiService;
use App\Http\Requests\RencanaBiaya\CreateRencanaBiayaRequest;
use App\Http\Requests\RencanaBiaya\UpdateRencanaBiayaRequest;
use Barryvdh\DomPDF\Facade\PDF;

class RencanaBiayaController extends Controller
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

        $rencana_biaya = BiayaReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rencana')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        return view('detail-lahan.rencana-biaya.index', compact('rencana_biaya', 'lahan'));
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

        $rencana_biaya = BiayaReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rencana')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        return view('detail-lahan.rencana-biaya.create', compact('lahan', 'rencana_biaya'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRencanaBiayaRequest $request, Lahan $lahan)
    {
        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validated();

        $validated['lahan_id'] = $lahan->lahan_id;

        $validated['type'] = 'rencana';

        // Create the Rencana Biaya
        $rencana_biaya = BiayaReklamasi::create($validated);

        return redirect()->route('lahan.rencana-biaya.index', $lahan->lahan_id)
                        ->with('success', 'Rencana Biaya ' . $validated['tahun'] . ' berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lahan $lahan, BiayaReklamasi $rencana_biaya)
    {
        // Verify this rencana_biaya belongs to this lahan
        if ($rencana_biaya->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Rencana biaya not found for this lahan.');
        }

        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $rencana_biaya_collection = BiayaReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rencana')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        $tahun_aktif = request('tahun', $rencana_biaya->tahun);

        return view('detail-lahan.rencana-biaya.edit', [
            'rencana_biaya' => $rencana_biaya_collection,
            'lahan' => $lahan,
            'tahun_aktif' => $tahun_aktif,
            'rencana_biaya_item' => $rencana_biaya,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRencanaBiayaRequest $request, Lahan $lahan, BiayaReklamasi $rencana_biaya)
    {
        // Verify this rencana_biaya belongs to this lahan
        if ($rencana_biaya->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Rencana biaya not found for this lahan.');
        }

        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validated();

        $validated['lahan_id'] = $lahan->lahan_id;

        $validated['type'] = 'rencana';

        $rencana_biaya->update($validated);

        return redirect()->route('lahan.rencana-biaya.index', $lahan->lahan_id)
                         ->with('success', 'Rencana Biaya ' . $validated['tahun'] . ' berhasil diperbarui.');
    }

    public function generatePDF(Lahan $lahan, RencanaReklamasiService $pdfService)
    {
        $data = BiayaReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rencana')
            ->orderBy('tahun')
            ->get();

        return $pdfService->generate($lahan, $data, 'reports.rencana-biaya-pdf', 'Rencana Biaya');
    }
}
