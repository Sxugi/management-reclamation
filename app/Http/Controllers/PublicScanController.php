<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use App\Models\PlotProgres;
use App\Services\PlotService;
use App\Services\ProgresReklamasiService;
use Illuminate\Http\Request;

class PublicScanController extends Controller
{
    public function show($uuid)
    {
        $plot = Plot::where('uuid', $uuid)
            ->with([
                'lahan',
                'handover', // Retrieve lokasi
                'activityLogs', // Retrieve logs
                'progres.fieldValues.fieldDefinition', 
                'progres.jenisAktivitas.kategoriAktivitas',
                'progres.dokumentasi'
            ])
            ->firstOrFail();

        $plotProgress = PlotProgres::where('plot_id', $plot->plot_id)->first();
        $progressPercent = $plotProgress ? $plotProgress->percent : 0;
        
        // Calculate progress delta
        $progresDelta = ProgresReklamasiService::getProgresDelta($plot);

        // Get photo marker data
        $photoMarkersData = PlotService::getPhotoMarkerData($plot);
        
        // Get technical summary
        $technicalSummary = PlotService::getTechnicalSummary($plot);

        return view('public.plot-scan', [
            'plot' => $plot,
            'lahan' => $plot->lahan,
            'handover' => $plot->handover,
            'activityLogs' => $plot->activityLogs, // Pass logs
            'progressPercent' => $progressPercent,
            'progresDelta' => $progresDelta, 
            'photoMarkersData' => $photoMarkersData,
            'technicalSummary' => $technicalSummary,
        ]);
    }
}