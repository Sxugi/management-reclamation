<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\RencanaBiaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\View;
use Illuminate\Validation\Rule;
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

        $rencana_biaya = RencanaBiaya::byLahanPerTahun($lahan->lahan_id);

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

        $rencana_biaya = RencanaBiaya::byLahanPerTahun($lahan->lahan_id);

        return view('detail-lahan.rencana-biaya.create', compact('lahan', 'rencana_biaya'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Lahan $lahan)
    {
        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validate([
            'tahun' => [
                'required',
                'integer',
                Rule::unique('rencana_biayas')->where(fn ($q) => $q->where('lahan_id', $lahan->lahan_id)),
            ],
            'biaya_langsung' => 'required|array',
            'biaya_tidak_langsung' => 'required|array',
        ]);

        $validated['lahan_id'] = $lahan->lahan_id;

        // Create the Rencana Biaya
        $rencana_biaya = RencanaBiaya::create($validated);

        return redirect()->route('lahan.rencana-biaya.index', $lahan->lahan_id)
                        ->with('success', 'Rencana Biaya ' . $validated['tahun'] . ' berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lahan $lahan, RencanaBiaya $rencana_biaya)
    {
        // Verify this rencana_biaya belongs to this lahan
        if ($rencana_biaya->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Rencana biaya not found for this lahan.');
        }

        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Save the rencana_biaya item to a new variable 
        $rencana_biaya_item = $rencana_biaya; 
        
        // Put the rencana_biaya collection in a variable
        $rencana_biaya_collection = RencanaBiaya::byLahanPerTahun($lahan->lahan_id);

        // Get the active year from the request or use the year from the rencana_biaya item
        $tahun_aktif = request('tahun', $rencana_biaya_item->tahun);

        return view('detail-lahan.rencana-biaya.edit', [
            'rencana_biaya' => $rencana_biaya_collection,
            'lahan' => $lahan,
            'tahun_aktif' => $tahun_aktif
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lahan $lahan, RencanaBiaya $rencana_biaya)
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
        $validated = $request->validate([
            'tahun' => [
                'required',
                'integer',
                Rule::unique('rencana_biayas')
                ->where(fn ($q) => $q->where('lahan_id', $lahan->lahan_id))
                ->ignore($rencana_biaya->rencana_biaya_id, 'rencana_biaya_id'),
            ],
            'biaya_langsung' => 'required|array',
            'biaya_tidak_langsung' => 'required|array',
        ]);

        $validated['lahan_id'] = $lahan->lahan_id;

        // Update the Rencana Biaya
        $rencana_biaya->update($validated);

        return redirect()->route('lahan.rencana-biaya.index', $lahan->lahan_id)
                         ->with('success', 'Rencana Biaya ' . $validated['tahun'] . ' berhasil diperbarui.');
    }

    public function generatePDF(Lahan $lahan)
    {
        // Get the Rencana Biaya data for the specified lahan
        $data = RencanaBiaya::where('lahan_id', $lahan->lahan_id)
            ->orderBy('tahun')
            ->get();

        // Template data preparation
        $tahunRange = [];
        $biayaPerTahun = [];
        
        if ($data->count() > 0) {
            $tahunAwal = $data->min('tahun');
            $tahunAkhir = $data->max('tahun');
            $tahunRange = "$tahunAwal s.d $tahunAkhir";
            
            // Prepare biaya per tahun
            foreach ($data as $rencana) {
                $biayaPerTahun[$rencana->tahun] = [
                    'biaya_langsung' => $rencana->biaya_langsung,
                    'biaya_tidak_langsung' => $rencana->biaya_tidak_langsung,
                ];
            }
        }
        
        // Load template
        $html = view('reports.rencana-biaya-pdf', [
            'lahan' => $lahan,
            'tahunRange' => $tahunRange,
            'biayaPerTahun' => $biayaPerTahun,
            'tahuns' => array_keys($biayaPerTahun),
        ])->render();

        // Load the HTML into PDF
        $pdf = PDF::loadHTML($html);
        
        // Set options for the PDF
        $dompdf = $pdf->getDomPDF();
        $options = $dompdf->getOptions();
        $options->set('defaultFont', 'Times-Roman');
        $dompdf->setOptions($options);
        
        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Rencana Biaya Reklamasi - ' . $lahan->nama_lahan . '.pdf');
    }
}
