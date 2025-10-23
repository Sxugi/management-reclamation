<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\Pohon;
use App\Models\DataPohon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Pohon\StorePohonRequest;
use App\Http\Requests\Pohon\UpdatePohonRequest;
use App\Services\DataPohonService;
use Illuminate\Support\Facades\DB;

class PohonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Lahan $lahan)
    {
        // Check if user owns the lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $sort = $request->get('tableSortColumn');
        $direction = $request->get('tableSortDirection', 'asc');

        $pohon = DataPohonService::getFilteredData($request, $lahan);
        $pohonCollection = $pohon->getCollection();
        $pohonCollection = DataPohonService::mapDataPohonByTahun($pohonCollection, $direction);
        $pohon->setCollection($pohonCollection);

        $tahunList = DataPohonService::getTahunList($pohonCollection, $sort, $direction);

        $hasFilter = DataPohonService::hasFilter($request);

        $jenisPohonList = DataPohonService::getJenisPohonList($lahan->lahan_id);

        return view('detail-lahan.pohon.index', [
            'lahan' => $lahan,
            'pohon' => $pohon,
            'hasFilter' => $hasFilter,
            'tahunList' => $tahunList,
            'jenisPohonList' => $jenisPohonList
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Lahan $lahan)
    {
        // Check if user owns the lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $jenisPohonList = DataPohonService::getJenisPohonList($lahan->lahan_id);

        return view('detail-lahan.pohon.create', [
            'lahan' => $lahan,
            'jenisPohonList' => $jenisPohonList
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePohonRequest $request, Lahan $lahan)
    {
        $validated = $request->validated();
        $validated['lahan_id'] = $lahan->lahan_id;
        
        try {
            $pohon = null;
            DB::transaction(function () use ($validated, $lahan, &$pohon) {
                $pohon = Pohon::where('lahan_id', $lahan->lahan_id)
                            ->where('jenis_pohon', $validated['jenis_pohon'])
                            ->first();

                if (!$pohon) {
                    $pohon = Pohon::create([
                        'lahan_id' => $lahan->lahan_id,
                        'jenis_pohon' => $validated['jenis_pohon'],
                    ]);
                }

                $exists = $pohon->dataPohon()
                    ->where('tahun', $validated['tahun'])
                    ->exists();

                if ($exists) {
                    throw new \Exception("Data untuk tahun {$validated['tahun']} sudah ada.");
                }

                $pohon->dataPohon()->create([
                    'tahun' => $validated['tahun'],
                    'jumlah' => $validated['jumlah'],
                ]);
            });

            return redirect()->route('lahan.pohon.index', $pohon->lahan_id)
                ->with('success', 'Data pohon ' . $pohon->jenis_pohon . ' tahun ' . $validated['tahun'] . ' berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error creating data pohon', [
                'user' => Auth::user()->username,
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data pohon. Silakan coba lagi.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lahan $lahan, Pohon $pohon, DataPohon $dataPohon)
    {
        // Check if user owns the lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $jenisPohonList = DataPohonService::getJenisPohonList($lahan->lahan_id);

        return view('detail-lahan.pohon.edit', [
            'lahan' => $lahan,
            'pohon' => $pohon,
            'jenisPohonList' => $jenisPohonList,
            'dataPohon' => $dataPohon
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePohonRequest $request, Lahan $lahan, Pohon $pohon, DataPohon $dataPohon)
    {
        $validated = $request->validated();
        $validated['lahan_id'] = $lahan->lahan_id;
        
        try {
            DB::transaction(function () use ($pohon, $dataPohon, $validated) {
                $pohon->update([
                    'jenis_pohon' => $validated['jenis_pohon'],
                ]);

                $dataPohon->update([
                    'tahun' => $validated['tahun'],
                    'jumlah' => $validated['jumlah'],
                ]);
            });

            return redirect()->route('lahan.pohon.index', $pohon->lahan_id)
                            ->with('success', 'Data pohon ' . $pohon->jenis_pohon . ' tahun ' . $validated['tahun'] . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Error updating data pohon', [
                'user' => Auth::user()->username,
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data pohon. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lahan $lahan, Pohon $pohon, $tahun)
    {
        // Check if user owns the lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if pohon belongs to this lahan
        if ($pohon->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Data not found.');
        }

        $dataPohon = $pohon->dataPohon()->where('tahun', $tahun)->first();

        if (!$dataPohon) {
            abort(404, 'Data pohon di tahun tersebut tidak ditemukan.');
        }

        $dataPohon->delete();

        if ($pohon->dataPohon()->count() === 0) {
            $pohon->delete();
            $message = 'Data pohon ' . $pohon->jenis_pohon . ' tahun ' . $tahun . ' berhasil dihapus. Karena sudah tidak ada data pada jenis pohon ini, jenis pohon juga dihapus.';
        } else {
            $message = 'Data pohon ' . $pohon->jenis_pohon . ' tahun ' . $tahun . ' berhasil dihapus.';
        }

        return redirect()->route('lahan.pohon.index', $lahan)
                        ->with('success', $message);
    }
}
