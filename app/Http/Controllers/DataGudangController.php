<?php

namespace App\Http\Controllers;

use App\Models\DataGudang;
use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Gudang\CreateDataGudangRequest;
use App\Http\Requests\Gudang\UpdateDataGudangRequest;
use App\Services\DataGudangService;

class DataGudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $gudang = DataGudangService::getFilteredData($request, $lahan);

        $hasFilter = $request->filled('startDate') ||
                        $request->filled('endDate') ||
                        $request->filled('jenisBarang') ||
                        $request->filled('statusBarang');

        return view('detail-lahan.gudang.index', [
            'gudang' => $gudang,
            'lahan' => $lahan,
            'hasFilter' => $hasFilter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('detail-lahan.gudang.create', [
            'lahan' => $lahan,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDataGudangRequest $request, Lahan $lahan)
    {
        $validated = $request->validated();
        $validated['lahan_id'] = $lahan->lahan_id;
        $gudang = DataGudang::create($validated);

        return redirect()->route('lahan.gudang.index', $lahan)
                        ->with('success', 'Data Gudang berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lahan $lahan, DataGudang $gudang) // Sesuaikan dengan route parameter
    {
        // Check if user owns the lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if gudang belongs to this lahan
        if ($gudang->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Data not found.');
        }

        return view('detail-lahan.gudang.edit', [
            'gudang' => $gudang, 
            'lahan' => $lahan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDataGudangRequest $request, Lahan $lahan, DataGudang $gudang)
    {
        $validated = $request->validated();
        $validated['lahan_id'] = $lahan->lahan_id;
        $gudang->update($validated);

        return redirect()->route('lahan.gudang.index', $lahan)
                        ->with('success', 'Data Gudang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lahan $lahan, DataGudang $gudang)
    {
        // Check if user owns the lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if gudang belongs to this lahan
        if ($gudang->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Data not found.');
        }

        $gudang->delete();

        return redirect()->route('lahan.gudang.index', $lahan)
                        ->with('success', 'Data Gudang berhasil dihapus');
    }
}