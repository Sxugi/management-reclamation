<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\DataReklamasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RencanaReklamasi\StoreRencanaReklamasi;
use App\Http\Requests\RencanaReklamasi\UpdateRencanaReklamasi;
use App\Services\RencanaReklamasiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RencanaReklamasiController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Lahan $lahan)
    {
        $this->authorize('viewAny', [DataReklamasi::class, $lahan]);

        $rencana_reklamasi = DataReklamasi::with('detailDataReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rencana')
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
        $this->authorize('create', [DataReklamasi::class, $lahan]);
        
        $rencana_reklamasi = DataReklamasi::with('detailDataReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rencana')
            ->orderBy('tahun')
            ->get();

        return view('detail-lahan.rencana-reklamasi.create', compact('lahan', 'rencana_reklamasi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRencanaReklamasi $request, Lahan $lahan)
    {
        $this->authorize('create', [DataReklamasi::class, $lahan]);

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $dataReklamasi = DataReklamasi::create([
                'lahan_id' => $lahan->lahan_id,
                'tahun' => $validated['tahun'],
                'tipe' => 'rencana',
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

            return redirect()->route('lahan.rencana-reklamasi.index', $lahan->lahan_id)
                ->with('success', 'Rencana Reklamasi tahun ' . $validated['tahun'] . ' berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating rencana reklamasi', [
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
    public function edit(Lahan $lahan, DataReklamasi $rencana_reklamasi)
    {
        if ($rencana_reklamasi->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Rencana reklamasi not found for this lahan.');;
        }

        $this->authorize('update', $rencana_reklamasi);

        $rencana_reklamasi_collection = DataReklamasi::with('detailDataReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rencana')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        $tahun_aktif = request('tahun', $rencana_reklamasi->tahun);

        return view('detail-lahan.rencana-reklamasi.edit', [
            'lahan' => $lahan,
            'rencana_reklamasi' => $rencana_reklamasi_collection,
            'tahun_aktif' => $tahun_aktif,
            'rencana_reklamasi_item' => $rencana_reklamasi,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRencanaReklamasi $request, Lahan $lahan, DataReklamasi $rencana_reklamasi)
    {
        if ($rencana_reklamasi->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Rencana reklamasi not found for this lahan.');
        }

        $this->authorize('update', $rencana_reklamasi);

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $rencana_reklamasi->update([
                'tahun' => $validated['tahun'],
            ]);

            $rencana_reklamasi->detailDataReklamasi()->delete();

            foreach ($validated['detail'] as $data) {
                if (isset($data['volume']) && (float)$data['volume'] > 0) {
                    $rencana_reklamasi->detailDataReklamasi()->create([
                        'kegiatan' => $data['kegiatan'],
                        'kategori' => $data['kategori'],
                        'volume' => $data['volume'],
                        'satuan' => $data['satuan'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('lahan.rencana-reklamasi.index', $lahan->lahan_id)
                ->with('success', 'Rencana Reklamasi ' . $validated['tahun'] . ' berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating rencana reklamasi', [
                'user' => Auth::user()->username,
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }
    }

    /**
     * Generate PDF for the specified resource.
     */
    public function generatePDF(Lahan $lahan, RencanaReklamasiService $pdfService)
    {
        $this->authorize('generatePDF', [DataReklamasi::class, $lahan]);

        $pdfResponse = $pdfService->generate($lahan);

        if (!$pdfResponse) {
            return redirect()->back()->with('error', 'Rencana reklamasi belum tersedia.');
        }

        return $pdfResponse;
    }
}

