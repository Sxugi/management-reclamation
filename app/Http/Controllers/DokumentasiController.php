<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\Dokumentasi\StoreDokumentasiRequest;
use App\Http\Requests\Dokumentasi\UpdateDokumentasiRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DokumentasiController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Lahan $lahan)
    {
        $this->authorize('viewAny', [Dokumentasi::class, $lahan]);

        $query = Dokumentasi::where('lahan_id', $lahan->lahan_id);

        if ($request->filled('startDate')) {
            $startDate = Carbon::parse($request->startDate)->startOfDay();
            $query->where('created_at', '>=', $startDate);
        }

        if ($request->filled('endDate')) {
            $endDate = Carbon::parse($request->endDate)->endOfDay();
            $query->where('created_at', '<=', $endDate);
        }

        $hasFilter = $request->has(['startDate', 'endDate']);

        $dokumentasi = $query->latest()->paginate(8);
        
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
        $this->authorize('create', [Dokumentasi::class, $lahan]);

        return view('detail-lahan.dokumentasi.create', [
            'lahan' => $lahan,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Lahan $lahan, StoreDokumentasiRequest $request)
    {
        $this->authorize('create', [Dokumentasi::class, $lahan]);

        $validated = $request->validated();
        $validated['lahan_id'] = $lahan->lahan_id;

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $validated['image_path'] = $request->file('image')->store('dokumentasi', 'public');
            }

            Dokumentasi::create($validated);

            DB::commit();

            return redirect()->route('lahan.dokumentasi.index', $lahan->lahan_id)
                            ->with('success', 'Dokumentasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating dokumentasi', [
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
    public function edit(Lahan $lahan, Dokumentasi $dokumentasi)
    {
        if ($dokumentasi->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Dokumentasi not found for this lahan.');
        }

        // Check if user owns this lahan
        $this->authorize('update', $dokumentasi);

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
        $this->authorize('update', $dokumentasi);

        $validated = $request->validated();
        $validated['lahan_id'] = $lahan->lahan_id;

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                if ($dokumentasi->image_path) {
                    Storage::disk('public')->delete($dokumentasi->image_path);
                }
                $validated['image_path'] = $request->file('image')->store('dokumentasi', 'public');
            }

            $dokumentasi->update($validated);

            DB::commit();

            return redirect()->route('lahan.dokumentasi.index', $lahan->lahan_id)
                            ->with('success', 'Dokumentasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating dokumentasi', [
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
     * Remove the specified resource from storage.
     */
    public function destroy(Lahan $lahan, Dokumentasi $dokumentasi)
    {
        if ($dokumentasi->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Dokumentasi not found for this lahan.');
        }

        // Check if user owns this lahan
        $this->authorize('delete', $dokumentasi);
        
        DB::beginTransaction();
        try {
            if ($dokumentasi->image_path) {
                Storage::disk('public')->delete($dokumentasi->image_path);
            }
            $dokumentasi->delete();

            DB::commit();

            return redirect()->route('lahan.dokumentasi.index', $lahan->lahan_id)
                        ->with('success', 'Dokumentasi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting dokumentasi', [
                'user' => Auth::user()->username,
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
        }
    }
}
