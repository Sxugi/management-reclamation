<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\BiayaReklamasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RencanaBiaya\CreateRencanaBiayaRequest;
use App\Http\Requests\RencanaBiaya\UpdateRencanaBiayaRequest;
use App\Services\RencanaBiayaService;
use Illuminate\Support\Facades\DB;

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

        $rencana_biaya = BiayaReklamasi::with('detailBiayaReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rencana')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        $tahun_aktif = request('tahun', $lahan->tahun_awal);
        $currency = $rencana_biaya[$tahun_aktif]->currency ?? 'IDR';

        return view('detail-lahan.rencana-biaya.index', compact('rencana_biaya', 'lahan', 'tahun_aktif', 'currency'));
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

        $rencana_biaya = BiayaReklamasi::with('detailBiayaReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rencana')
            ->orderBy('tahun')
            ->get();

        $tahun_aktif = request('tahun', $lahan->tahun_awal);
        $currency = request('currency', 'IDR');

        return view('detail-lahan.rencana-biaya.create', compact('lahan', 'rencana_biaya', 'tahun_aktif', 'currency'));
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

        try {
            DB::transaction(function () use ($validated, $lahan) {
                $rencanaBiaya = BiayaReklamasi::create([
                    'lahan_id'    => $lahan->lahan_id,
                    'tahun'       => $validated['tahun'],
                    'tipe'        => 'rencana',
                    'currency'    => $validated['currency'],
                    'subtotal_1'  => $validated['subtotal_1'],
                    'subtotal_2'  => $validated['subtotal_2'],
                ]);

                foreach ($validated['detail'] as $data) {
                    $rencanaBiaya->detailBiayaReklamasi()->create([
                        'kegiatan' => $data['kegiatan'],
                        'kategori' => $data['kategori'],
                        'biaya'    => $data['biaya'],
                    ]);
                }
            });

            return redirect()->route('lahan.rencana-biaya.index', $lahan->lahan_id)
                ->with('success', 'Rencana Biaya tahun ' . $validated['tahun'] . ' berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error creating rencana biaya', [
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

        $rencana_biaya_collection = BiayaReklamasi::with('detailBiayaReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rencana')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        $tahun_aktif = request('tahun', $rencana_biaya->tahun);
        $currency = request('currency', 'IDR');

        return view('detail-lahan.rencana-biaya.edit', [
            'rencana_biaya' => $rencana_biaya_collection,
            'lahan' => $lahan,
            'tahun_aktif' => $tahun_aktif,
            'rencana_biaya_item' => $rencana_biaya,
            'currency' => $currency,
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
        
        try {
            DB::transaction(function () use ($validated, $rencana_biaya) {
                $rencana_biaya->update([
                    'tahun'      => $validated['tahun'],
                    'currency'    => $validated['currency'],
                    'subtotal_1' => $validated['subtotal_1'],
                    'subtotal_2' => $validated['subtotal_2'],
                ]);

                $rencana_biaya->detailBiayaReklamasi()->delete();

                foreach ($validated['detail'] as $data) {
                    $rencana_biaya->detailBiayaReklamasi()->create([
                        'kegiatan' => $data['kegiatan'],
                        'kategori' => $data['kategori'],
                        'biaya'    => $data['biaya'],
                    ]);
                }
            });

            return redirect()->route('lahan.rencana-biaya.index', $lahan->lahan_id)
                ->with('success', 'Rencana Biaya tahun ' . $validated['tahun'] . ' berhasil diperbarui.');

        } catch (\Exception $e) {
            \Log::error('Error updating rencana biaya', [
                'user' => Auth::user()->username,
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }
    }

    public function generatePDF(Lahan $lahan, RencanaBiayaService $pdfService)
    {
        $data = BiayaReklamasi::with('detailBiayaReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rencana')
            ->orderBy('tahun')
            ->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'Rencana biaya reklamasi belum tersedia.');
        }

        return $pdfService->generate($lahan, $data, 'reports.rencana-biaya-pdf', 'Rencana Biaya');
    }
}
