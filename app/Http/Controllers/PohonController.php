<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\Pohon;
use App\Models\DataPohonManual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\Pohon\StorePohonRequest;
use App\Http\Requests\Pohon\UpdatePohonRequest;
use App\Services\DataPohonService;

class PohonController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Lahan $lahan)
    {
        // Check if user owns the lahan
        $this->authorize('viewAny', [Pohon::class, $lahan]);

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
        $this->authorize('create', [Pohon::class, $lahan]);

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
        $this->authorize('create', [Pohon::class, $lahan]);

        $validated = $request->validated();
        
        try {
            DB::transaction(function () use ($validated, $lahan) {
                $pohon = Pohon::firstOrCreate(
                    [
                        'lahan_id' => $lahan->lahan_id,
                        'jenis_pohon_id' => $validated['jenis_pohon_id']
                    ]
                );
                DataPohonManual::create([
                    'pohon_id' => $pohon->pohon_id,
                    'tahun' => $validated['tahun'],
                    'jumlah_batang' => $validated['jumlah_batang'],
                ]);
            });

            return redirect()->route('lahan.pohon.index', $lahan->lahan_id)
                ->with('success', 'Data pohon berhasil ditambahkan.');
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
    public function edit(Lahan $lahan, Pohon $pohon, DataPohonManual $dataPohonManual)
    {
        // Safety Check
        if ($pohon->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Data pohon tidak ditemukan untuk lahan ini.');
        }
        
        // Verify that the DataPohon belongs to the Pohon
        if ($dataPohonManual->pohon_id !== $pohon->pohon_id) {
            abort(404, 'Data detail pohon tidak sesuai.');
        }

        $this->authorize('update', $pohon);

        $jenisPohonList = DataPohonService::getJenisPohonList($lahan->lahan_id);

        return view('detail-lahan.pohon.edit', [
            'lahan' => $lahan,
            'pohon' => $pohon,
            'jenisPohonList' => $jenisPohonList,
            'dataPohon' => $dataPohonManual
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePohonRequest $request, Lahan $lahan, Pohon $pohon, DataPohonManual $dataPohonManual)
    {
        // Safety Check
        if ($pohon->lahan_id !== $lahan->lahan_id) {
            abort(404);
        }

        $this->authorize('update', $pohon);
        $validated = $request->validated();
        
        try {
            DB::transaction(function () use ($pohon, $dataPohonManual, $validated, $lahan) {
                if ($pohon->jenis_pohon_id != $validated['jenis_pohon_id']) {
                    $newParent = Pohon::firstOrCreate([
                        'lahan_id' => $lahan->lahan_id,
                        'jenis_pohon_id' => $validated['jenis_pohon_id']
                    ]);
                    $dataPohonManual->pohon_id = $newParent->pohon_id;
                }

                $dataPohonManual->tahun = $validated['tahun'];
                $dataPohonManual->jumlah_batang = $validated['jumlah_batang'];
                $dataPohonManual->save();
            });

            return redirect()->route('lahan.pohon.index', $lahan->lahan_id)
                            ->with('success', 'Data pohon berhasil diperbarui.');
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
    public function destroy(Lahan $lahan, Pohon $pohon, DataPohonManual $dataPohonManual)
    {
        // Safety Check
        if ($pohon->lahan_id !== $lahan->lahan_id) {
            abort(404, 'Data pohon tidak ditemukan untuk lahan ini.');
        }

        if ($dataPohonManual->pohon_id !== $pohon->pohon_id) {
            abort(404, 'Data tidak sesuai.');
        }

        $this->authorize('delete', $pohon);
        
        try {
            $jenisPohon = $pohon->jenisPohon->nama_pohon ?? 'Pohon';
            $tahun = $dataPohonManual->tahun;
            
            $dataPohonManual->delete();

            return redirect()->route('lahan.pohon.index', $lahan)
                ->with('success', "Data {$jenisPohon} tahun {$tahun} berhasil dihapus.");

        } catch (\Exception $e) {
            \Log::error('Error deleting data pohon', [
                'data_pohon_manual_id' => $dataPohonManual->data_pohon_manual_id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('lahan.pohon.index', $lahan)
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Export Excel
     */
    public function export(Lahan $lahan)
    {
        // Check authorization (Pohon Policy)
        $this->authorize('viewAny', [Pohon::class, $lahan]);

        try {
            $service = new DataPohonService();
            return $service->exportExcel($lahan);

        } catch (\Exception $e) {
            \Log::error('Gagal export data pohon', [
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }
}
