<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgresReklamasi\StoreTargetProgresReklamasiRequest;
use App\Models\Plot;
use App\Models\TargetProgresReklamasi;
use App\Services\TargetProgresService;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TargetProgresReklamasiController extends Controller
{
    use AuthorizesRequests;

    protected TargetProgresService $service;

    public function __construct(TargetProgresService $service)
    {
        $this->service = $service;
    }

    public function store(StoreTargetProgresReklamasiRequest $request, Plot $plot)
    {
        // Load lahan relation
        $plot->load('lahan');

        // Authorization check
        $this->authorize('manage', [TargetProgresReklamasi::class, $plot]);

        $data = $request->validated();
        $selected = $data['selected'] ?? [];
        $values = $data['value'] ?? [];

        // Check if any targets exist before operation
        $hasExistingTargets = $plot->target()->exists();

        // Handle empty selection - delete all targets
        if (empty($selected)) {
            if ($hasExistingTargets) {
                DB::beginTransaction();
                try {
                    $this->service->syncTargets($plot, [], []);
                    
                    DB::commit();
                return redirect()
                    ->route('plot.show', $plot->plot_id)
                    ->with('success', 'Semua target berhasil dihapus.');
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log:: error('Failed to delete all targets', [
                        'plot_id' => $plot->plot_id,
                        'error' => $e->getMessage()
                    ]);

                    return redirect()
                        ->route('plot.show', $plot->plot_id)
                        ->with('error', 'Gagal menghapus target: ' . $e->getMessage());
                }
            } else {
                return redirect()
                    ->route('plot.show', $plot->plot_id)
                    ->with('info', '⚠️ Tidak ada target yang dipilih.');
            }
        }

        // Sync targets through service
        DB::beginTransaction();
        try {
            $result = $this->service->syncTargets($plot, $selected, $values);

            DB::commit();

            $message = $hasExistingTargets ? 'Target berhasil diperbarui.' : 'Target berhasil ditambahkan.';

            return redirect()
                ->route('plot.show', $plot->plot_id)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log:: error('Failed to sync targets', [
                'plot_id' => $plot->plot_id,
                'selected' => $selected,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan target: ' . $e->getMessage());
        }
    }

    public function getEditData(Plot $plot)
    {
        // Load lahan relation
        $plot->load('lahan');

        // Authorization check
        $this->authorize('manage', [TargetProgresReklamasi::class, $plot]);

        try {
            $targets = $this->service->getExistingTargets($plot);

            return response()->json([
                'success' => true,
                'data' => $targets
            ]);

        } catch (\Exception $e) {
            \Log:: error('Failed to get existing targets', [
                'plot_id' => $plot->plot_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data target:  ' . $e->getMessage()
            ], 500);
        }
    }
}