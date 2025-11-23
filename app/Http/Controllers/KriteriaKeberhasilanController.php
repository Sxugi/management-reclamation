<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\KriteriaKeberhasilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\View;
use App\Http\Requests\KriteriaKeberhasilan\UpdatePenatagunaanRequest;
use App\Http\Requests\KriteriaKeberhasilan\UpdateRevegetasiRequest;
use App\Http\Requests\KriteriaKeberhasilan\UpdatePenyelesaianRequest;
use App\Services\KriteriaKeberhasilanService;
use Illuminate\Support\Facades\DB;


class KriteriaKeberhasilanController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $kriteria = KriteriaKeberhasilan::firstOrCreate(
            ['lahan_id' => $lahan->lahan_id]
        );

        $kriteria->load('detailKriteriaKeberhasilan');

        $tab_aktif = request()->query('tab', 'penatagunaan');

        $details = $kriteria->detailKriteriaKeberhasilan
            ->where('kategori', $tab_aktif)
            ->keyBy('indikator')
            ->map(function ($item) {
                return [
                    'rencana' => $item->rencana,
                    'realisasi' => $item->realisasi,
                    'hasil_evaluasi' => $item->hasil_evaluasi,
                ];
            })->toArray();

        return view('detail-lahan.kriteria-keberhasilan.show', compact('lahan', 'kriteria', 'tab_aktif', 'details'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $kriteria = KriteriaKeberhasilan::firstOrCreate(
            ['lahan_id' => $lahan->lahan_id]
        );

        $kriteria->load('detailKriteriaKeberhasilan');

        $tab_aktif = request()->query('tab', 'penatagunaan');

        $details = $kriteria->detailKriteriaKeberhasilan
            ->where('kategori', $tab_aktif)
            ->keyBy('indikator')
            ->map(function ($item) {
                return [
                    'rencana' => $item->rencana,
                    'realisasi' => $item->realisasi,
                    'hasil_evaluasi' => $item->hasil_evaluasi,
                ];
            })->toArray();

        return view('detail-lahan.kriteria-keberhasilan.edit', compact('lahan', 'kriteria', 'tab_aktif', 'details'));
    }

    /**
     * Update the Penatagunaan criteria.
     */
    public function updatePenatagunaan(UpdatePenatagunaanRequest $request, Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $lahan) {
                $kriteria = KriteriaKeberhasilan::firstOrCreate([
                    'lahan_id' => $lahan->lahan_id
                ]);

                foreach ($validated['indikator'] as $key => $data) {
                    $kriteria->detailKriteriaKeberhasilan()->updateOrCreate(
                        [
                            'indikator' => $key,
                            'kategori' => 'penatagunaan',
                        ],
                        [
                            'rencana' => $data['rencana'] ?? null,
                            'realisasi' => $data['realisasi'] ?? null,
                            'hasil_evaluasi' => $data['hasil_evaluasi'] ?? null,
                        ]
                    );
                }
            });

            return redirect()->route('lahan.kriteria-keberhasilan.show', ['lahan' => $lahan])
                ->with('success', 'Kriteria Keberhasilan Penatagunaan berhasil disimpan.');
        } catch (\Exception $e) {
            \Log::error('Error updating Kriteria Penatagunaan', [
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
     * Update the specified resource in storage for Revegetasi.
     */
    public function updateRevegetasi(UpdateRevegetasiRequest $request, Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $lahan) {
                $kriteria = KriteriaKeberhasilan::firstOrCreate([
                    'lahan_id' => $lahan->lahan_id
                ]);

                foreach ($validated['indikator'] as $key => $data) {
                    $kriteria->detailKriteriaKeberhasilan()->updateOrCreate(
                        [
                            'indikator' => $key,
                            'kategori' => 'revegetasi',
                        ],
                        [
                            'rencana' => $data['rencana'] ?? null,
                            'realisasi' => $data['realisasi'] ?? null,
                            'hasil_evaluasi' => $data['hasil_evaluasi'] ?? null,
                        ]
                    );
                }
            });

            return redirect()->route('lahan.kriteria-keberhasilan.show', ['lahan' => $lahan])
                ->with('success', 'Kriteria Keberhasilan Revegetasi berhasil disimpan.');
        } catch (\Exception $e) {
            \Log::error('Error updating Kriteria Revegetasi', [
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
     * Update the Penyelesaian criteria.
     */
    public function updatePenyelesaian(UpdatePenyelesaianRequest $request, Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $lahan) {
                $kriteria = KriteriaKeberhasilan::firstOrCreate([
                    'lahan_id' => $lahan->lahan_id
                ]);

                foreach ($validated['indikator'] as $key => $data) {
                    $kriteria->detailKriteriaKeberhasilan()->updateOrCreate(
                        [
                            'indikator' => $key,
                            'kategori' => 'penyelesaian',
                        ],
                        [
                            'rencana' => $data['rencana'] ?? null,
                            'realisasi' => $data['realisasi'] ?? null,
                            'hasil_evaluasi' => $data['hasil_evaluasi'] ?? null,
                        ]
                    );
                }
            });

            return redirect()->route('lahan.kriteria-keberhasilan.show', ['lahan' => $lahan])
                ->with('success', 'Kriteria Keberhasilan Penyelesaian berhasil disimpan.');
        } catch (\Exception $e) {
            \Log::error('Error updating Kriteria Penyelesaian', [
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
     * Generate PDF for Kriteria Keberhasilan.
     */
    public function generatePDF(Lahan $lahan, Request $request, KriteriaKeberhasilanService $pdfService)
    {
        try {
            if ($lahan->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }
            $errors = $pdfService->validatePDFGeneration($lahan);

            if (!empty($errors)) {
                return redirect()
                    ->back()
                    ->withErrors($errors)
                    ->withInput();
            }

            return $pdfService->generate($lahan);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }
}
