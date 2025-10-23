<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgresReklamasi\StoreTargetProgresReklamasiRequest;
use App\Models\Plot;
use App\Services\TargetProgresService;

class TargetProgresReklamasiController extends Controller
{
    protected TargetProgresService $service;

    public function __construct(TargetProgresService $service)
    {
        $this->service = $service;
    }

    public function store(StoreTargetProgresReklamasiRequest $request, Plot $plot)
    {
        // Authorization check
        if ($plot->lahan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validated();
        $selected = $data['selected'] ?? [];
        $values = $data['value'] ?? [];

        // Check if any targets exist before operation
        $hasExistingTargets = $plot->target()->exists();

        // Handle empty selection - delete all targets
        if (empty($selected)) {
            if ($hasExistingTargets) {
                $this->service->syncTargets($plot, [], []);
                return redirect()
                    ->route('plot.show', $plot->plot_id)
                    ->with('success', 'Semua target berhasil dihapus.');
            } else {
                return redirect()
                    ->route('plot.show', $plot->plot_id)
                    ->with('info', 'Tidak ada target yang dipilih.');
            }
        }

        // Sync targets through service
        $result = $this->service->syncTargets($plot, $selected, $values);

        $message = $hasExistingTargets ? 'Target berhasil diperbarui.' : 'Target berhasil ditambahkan.';

        return redirect()
            ->route('plot.show', $plot->plot_id)
            ->with('success', $message);
    }

    public function getEditData(Plot $plot)
    {
        if ($plot->lahan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return response()->json(
            $this->service->getExistingTargets($plot)
        );
    }
}