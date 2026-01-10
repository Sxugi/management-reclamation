<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use App\Models\Lahan;
use App\Models\PlotProgres;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\Plot\StorePlotRequest;
use App\Http\Requests\Plot\UpdatePlotRequest;
use App\Services\PlotService;
use App\Services\ProgresReklamasiService;

class PlotController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     * Uses nested route: /lahan/{lahan}/plot
     */
    public function index(Lahan $lahan)
    {
        // Check if user has access to view plots for this lahan
        $this->authorize('viewAny', [Plot::class, $lahan]);
        
        $plot = Plot::where('lahan_id', $lahan->lahan_id)
            ->orderBy('nama_plot')
            ->get();

        return view('detail-lahan.plot.index', compact('plot', 'lahan'));
    }

    /**
     * Show the form for creating a new resource.
     * Uses nested route: /lahan/{lahan}/plot/create
     */
    public function create(Lahan $lahan)
    {
        // Check if user can create plot in this lahan
        $this->authorize('create', [Plot::class, $lahan]);

        return view('detail-lahan.plot.create', compact('lahan'));
    }

    /**
     * Store a newly created resource in storage.
     * Uses nested route: /lahan/{lahan}/plot (POST)
     */
    public function store(StorePlotRequest $request, Lahan $lahan)
    {
        // Check if user can edit this lahan
        $this->authorize('create', [Plot::class, $lahan]);

        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $validated['luas_area'] = (float) $validated['luas_area'];
            $validated['lahan_id'] = $lahan->lahan_id;
            $validated['polygon'] = PlotService::processPolygon($request->polygon);

            $plot = Plot::create($validated);

            DB::commit();

        
            return redirect()->route('lahan.plot.index', $lahan->lahan_id)
                            ->with('success', 'Plot berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create plot', [
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan plot:  ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     * Uses shallow route: /plot/{plot}
     */
    public function show(Plot $plot, Request $request)
    {
        // Authorization check using policy
        $this->authorize('view', $plot);

        // Load plot with related lahan
        $plot->load([
            'lahan',
            'target.indikator', 
            'activityLogs',
        ]);

        // Get progres data with pagination
        $progresData = ProgresReklamasiService::getFilteredData($request, $plot);
        $progresData = PlotService:: transformProgresForModal($progresData, $plot);

        // Get kategori aktivitas options and filter status
        $kategori = ProgresReklamasiService::getKategoriAktivitasOptions();
        $hasFilter = ProgresReklamasiService::hasFilter($request);

        // Get progress percentage
        $plotProgress = PlotProgres::where('plot_id', $plot->plot_id)->first();
        $progressPercent = $plotProgress ? $plotProgress->percent : 0;
        $progresDelta = ProgresReklamasiService::getProgresDelta($plot);

        return view('detail-lahan.plot.show', [
            'plot' => $plot,
            'lahan' => $plot->lahan,
            'target' => $plot->target,
            'progres' => $progresData,
            'activityLogs' => $plot->activityLogs,
            'kategori' => $kategori,
            'hasFilter' => $hasFilter,
            'progressPercent' => $progressPercent,
            'progresDelta' => $progresDelta,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * Uses shallow route: /plot/{plot}/edit
     */
    public function edit(Plot $plot)
    {
        // Load lahan relation
        $plot->load('lahan');
        
        // Check if user can update this plot
        $this->authorize('update', $plot);

        return view('detail-lahan.plot.edit', [
            'plot' => $plot,
            'lahan' => $plot->lahan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * Uses shallow route: /plot/{plot} (PUT/PATCH)
     */
    public function update(UpdatePlotRequest $request, Plot $plot)
    {
        // Load lahan relation
        $plot->load('lahan');
        
        // Check if user can update this plot
        $this->authorize('update', $plot);

        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $validated['luas_area'] = (float) $validated['luas_area'];
            $validated['polygon'] = PlotService::processPolygon($request->polygon);

            $plot->update($validated);

            DB::commit();
        
            return redirect()->route('lahan.plot.index', $plot->lahan_id)
                            ->with('success', 'Plot berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log:: error('Failed to update plot', [
                'plot_id' => $plot->plot_id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui plot: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * Uses shallow route: /plot/{plot} (DELETE)
     */
    public function destroy(Plot $plot)
    {
        // Load lahan relation
        $plot->load('lahan');

        // Check if user can delete this plot (only owner)
        $this->authorize('delete', $plot);

        $lahan_id = $plot->lahan_id;
        $plotName = $plot->nama_plot;
        
        DB::beginTransaction();
        try {
            $plot->delete();

            DB::commit();
        
            return redirect()->route('lahan.plot.index', $lahan_id)
                            ->with('success', 'Plot berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to delete plot', [
                'plot_id' => $plot->plot_id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('lahan.plot.index', $lahan_id)
                ->with('error', 'Gagal menghapus plot:  ' . $e->getMessage());
        }
    }

    /**
     * Get activity logs for a plot
     */
    public function getActivityLogs(Request $request, Plot $plot)
    {
        // Check if user can view this plot
        $this->authorize('view', $plot);

        $validated = $request->validate([
            'sort' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:5|max:50',
        ]);

        $sort = $validated['sort'] ?? 'desc';
        $perPage = $validated['per_page'] ?? 5;

        $logs = ActivityLog::where('plot_id', $plot->plot_id)
            ->orderBy('created_at', $sort)
            ->paginate($perPage);

        return response()->json($logs);
    }
}