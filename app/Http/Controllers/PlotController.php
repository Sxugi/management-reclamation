<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\View;
// use App\Http\Requests\Plot\CreatePlotRequest;
// use App\Http\Requests\Plot\UpdatePlotRequest;

class PlotController extends Controller
{
    /**
     * Display a listing of the resource.
     * Uses nested route: /lahan/{lahan}/plot
     */
    public function index(Lahan $lahan)
    {
        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $plot = Plot::where('lahan_id', $lahan->lahan_id)->get();
        
        return view('detail-lahan.plot.index', compact('plot', 'lahan'));
    }

    /**
     * Show the form for creating a new resource.
     * Uses nested route: /lahan/{lahan}/plot/create
     */
    public function create(Lahan $lahan)
    {
        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('detail-lahan.plot.create', compact('lahan'));
    }

    /**
     * Store a newly created resource in storage.
     * Uses nested route: /lahan/{lahan}/plot (POST)
     */
    public function store(Request $request, Lahan $lahan)
    {
        $validated = $request->validate([
            'nama_plot' => 'string|max:255',
            'coordinates' => 'required|json',
            'area' => 'required|numeric',
        ]);

        $validated['area'] = (float) $validated['area'];

        $validated['coordinates'] = json_decode($validated['coordinates'], true);
        
        $validated['lahan_id'] = $lahan->lahan_id;
        
        $plot = Plot::create($validated);
        
        return redirect()->route('lahan.plot.index', $lahan->lahan_id)
                        ->with('success', 'Plot berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     * Uses shallow route: /plot/{plot}
     */
    public function show(Plot $plot)
    {
        // Get the associated lahan
        $lahan = Lahan::findOrFail($plot->lahan_id);
        
        // Check if user owns the lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('detail-lahan.plot.show', compact('plot', 'lahan'));
    }

    /**
     * Show the form for editing the specified resource.
     * Uses shallow route: /plot/{plot}/edit
     */
    public function edit(Plot $plot)
    {
        // Get the associated lahan
        $lahan = Lahan::findOrFail($plot->lahan_id);
        
        // Check if user owns the lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('detail-lahan.plot.edit', compact('plot', 'lahan'));
    }

    /**
     * Update the specified resource in storage.
     * Uses shallow route: /plot/{plot} (PUT/PATCH)
     */
    public function update(Request $request, Plot $plot)
    {
        // Get the associated lahan
        $lahan = Lahan::findOrFail($plot->lahan_id);
        
        // Check if user owns the lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'nama_plot' => 'required|string|max:255',
            'coordinates' => 'required|json',
            'area' => 'required|numeric',
        ]);
        
        $validated['area'] = (float) $validated['area'];

        $validated['coordinates'] = json_decode($validated['coordinates'], true);
        
        $validated['lahan_id'] = $lahan->lahan_id;

        $plot->update($validated);
        
        return redirect()->route('lahan.plot.index', $lahan->lahan_id)
                        ->with('success', 'Plot berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     * Uses shallow route: /plot/{plot} (DELETE)
     */
    public function destroy(Plot $plot)
    {
        // Get the associated lahan and its ID before deleting
        $lahan = Lahan::findOrFail($plot->lahan_id);
        $lahan_id = $lahan->lahan_id;
        
        // Check if user owns the lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $plot->delete();
        
        return redirect()->route('lahan.plot.index', $lahan_id)
                        ->with('success', 'Plot berhasil dihapus');
    }
}