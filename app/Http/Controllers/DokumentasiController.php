<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\Dokumentasi\CreateDokumentasiRequest;
use App\Http\Requests\Dokumentasi\UpdateDokumentasiRequest;

class DokumentasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $dokumentasi = Dokumentasi::where('lahan_id', $lahan->lahan_id);

        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $dokumentasi->where('created_at', '>=', $startDate);
        }

        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $dokumentasi->where('created_at', '<=', $endDate);
        }

        $hasFilter = $request->has(['start_date', 'end_date']);

        $dokumentasi = $dokumentasi->latest()->paginate(8);
        
        return view('detail-lahan.dokumentasi.index', [
            'dokumentasi' => $dokumentasi,
            'lahan' => $lahan,
            'hasFilter' => $hasFilter,
        ]);
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

        return view('detail-lahan.dokumentasi.create', [
            'lahan' => $lahan,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Lahan $lahan, CreateDokumentasiRequest $request)
    {
        $validated = $request->validated();

        $validated['lahan_id'] = $lahan->lahan_id;

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('dokumentasi', 'public');
        }

        $dokumentasi = Dokumentasi::create($validated);

        return redirect()->route('lahan.dokumentasi.index', $lahan->lahan_id)
                        ->with('success', 'Dokumentasi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lahan $lahan, Dokumentasi $dokumentasi)
    {
        if ($dokumentasi->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Dokumentasi not found for this lahan.');
        }

        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('detail-lahan.dokumentasi.show', [
            'dokumentasi' => $dokumentasi,
            'lahan' => $lahan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lahan $lahan, Dokumentasi $dokumentasi)
    {
        if ($dokumentasi->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Dokumentasi not found for this lahan.');
        }

        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('detail-lahan.dokumentasi.edit', [
            'dokumentasi' => $dokumentasi,
            'lahan' => $lahan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Lahan $lahan, Dokumentasi $dokumentasi, UpdateDokumentasiRequest $request)
    {
        $validated = $request->validated();

        $validated['lahan_id'] = $lahan->lahan_id;

        if ($request->hasFile('image')) {
            if ($dokumentasi->image_path) {
                Storage::disk('public')->delete($dokumentasi->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('dokumentasi', 'public');
        }

        $dokumentasi->update($validated);

        return redirect()->route('lahan.dokumentasi.index', $lahan->lahan_id)
                        ->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lahan $lahan, Dokumentasi $dokumentasi)
    {
        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($dokumentasi->image_path) {
            Storage::disk('public')->delete($dokumentasi->image_path);
        }
        $dokumentasi->delete();

        return redirect()->route('lahan.dokumentasi.index', $lahan->lahan_id)->with('success', 'Dokumentasi berhasil dihapus.');
    }
}
