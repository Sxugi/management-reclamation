<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\DataReklamasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\View;
use App\Services\RencanaReklamasiService;
use App\Http\Requests\RencanaReklamasi\CreateRencanaReklamasi;
use App\Http\Requests\RencanaReklamasi\UpdateRencanaReklamasi;
use Barryvdh\DomPDF\Facade\PDF;

class RencanaReklamasiController extends Controller
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

        $rencana_reklamasi = DataReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rencana')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        return view('detail-lahan.rencana-reklamasi.index', compact('rencana_reklamasi', 'lahan'));
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

        $rencana_reklamasi = DataReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rencana')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        return view('detail-lahan.rencana-reklamasi.create', compact('lahan', 'rencana_reklamasi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRencanaReklamasi $request, Lahan $lahan)
    {
        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validated();

        $validated['lahan_id'] = $lahan->lahan_id;

        $validated['type'] = 'rencana';

        // Create the Rencana Reklamasi
        $rencana_reklamasi = DataReklamasi::create($validated);

        return redirect()->route('lahan.rencana-reklamasi.index', $lahan->lahan_id)
                        ->with('success', 'Rencana Reklamasi ' . $validated['tahun'] . ' berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lahan $lahan, DataReklamasi $rencana_reklamasi)
    {
        // Verify this rencana_reklamasi belongs to this lahan
        if ($rencana_reklamasi->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Rencana reklamasi not found for this lahan.');
        }

        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $rencana_reklamasi_collection = DataReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rencana')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        $tahun_aktif = request('tahun', $rencana_reklamasi->tahun);

        return view('detail-lahan.rencana-reklamasi.edit', [
            'rencana_reklamasi' => $rencana_reklamasi_collection,
            'lahan' => $lahan,
            'tahun_aktif' => $tahun_aktif,
            'rencana_reklamasi_item' => $rencana_reklamasi,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRencanaReklamasi $request, Lahan $lahan, DataReklamasi $rencana_reklamasi)
    {
        // Verify this rencana_reklamasi belongs to this lahan
        if ($rencana_reklamasi->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Rencana reklamasi not found for this lahan.');
        }

        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validated();

        $validated['lahan_id'] = $lahan->lahan_id;

        $validated['type'] = 'rencana';

        // Update the Rencana Reklamasi
        $rencana_reklamasi->update($validated);

        return redirect()->route('lahan.rencana-reklamasi.index', $lahan->lahan_id)
                        ->with('success', 'Rencana Reklamasi ' . $validated['tahun'] . ' berhasil diperbarui.');
    }

    public function generatePDF(Lahan $lahan, RencanaReklamasiService $pdfService)
    {
        $data = DataReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rencana')
            ->orderBy('tahun')
            ->get();

        return $pdfService->generate($lahan, $data, 'reports.rencana-reklamasi-pdf', 'Rencana Reklamasi');
    }
}
