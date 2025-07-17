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


class KriteriaKeberhasilanController extends Controller
{
    public function show(Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $kriteria = KriteriaKeberhasilan::firstOrCreate(
            ['lahan_id' => $lahan->lahan_id]
        );

        $tab_aktif = request()->query('tab', 'penatagunaan');

        return view('detail-lahan.kriteria-keberhasilan.show', compact('lahan', 'kriteria', 'tab_aktif'));
    }

    public function edit(Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $kriteria = KriteriaKeberhasilan::firstOrCreate(
            ['lahan_id' => $lahan->lahan_id]
        );

        $tab_aktif = request()->query('tab', 'penatagunaan');

        return view('detail-lahan.kriteria-keberhasilan.edit', compact('lahan', 'kriteria', 'tab_aktif'));
    }

    public function updatePenatagunaan(UpdatePenatagunaanRequest $request, Lahan $lahan)
    {
        $validated = $request->validated();

        $kriteria = KriteriaKeberhasilan::firstOrCreate(
            ['lahan_id' => $lahan->lahan_id]
        );

        $kriteria->update($validated);

        return redirect()->route('lahan.kriteria-keberhasilan.show', ['lahan' => $lahan])
            ->with('success', 'Kriteria Keberhasilan Penatagunaan berhasil diperbarui.');
    }

    public function updateRevegetasi(UpdateRevegetasiRequest $request, Lahan $lahan)
    {
        $validated = $request->validated();

        $kriteria = KriteriaKeberhasilan::firstOrCreate(
            ['lahan_id' => $lahan->lahan_id]
        );

        $kriteria->update($validated);

        return redirect()->route('lahan.kriteria-keberhasilan.show', ['lahan' => $lahan])
            ->with('success', 'Kriteria Keberhasilan Revegetasi berhasil diperbarui.');
    }

    public function updatePenyelesaian(UpdatePenyelesaianRequest $request, Lahan $lahan)
    {
        $validated = $request->validated();

        $kriteria = KriteriaKeberhasilan::firstOrCreate(
            ['lahan_id' => $lahan->lahan_id]
        );

        $kriteria->update($validated);

        return redirect()->route('lahan.kriteria-keberhasilan.show', ['lahan' => $lahan])
            ->with('success', 'Kriteria Keberhasilan Penyelesaian berhasil diperbarui.');
    }

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

            return $pdfService->downloadPDF($lahan);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }
}
