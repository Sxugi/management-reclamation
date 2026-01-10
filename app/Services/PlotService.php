<?php

namespace App\Services;

use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Support\Facades\Storage;
use App\Models\Plot;
use App\Models\TargetProgresReklamasi;
use App\Models\ProgresReklamasi;

class PlotService
{
    /**
     * Transform progres data for modal display to prevent N+1 queries
     */
    public static function transformProgresForModal($progresData, Plot $plot)
    {
        $progresData->getCollection()->transform(function ($progres) use ($plot) {
            $progres->modal_data = [
                'progres_id' => $progres->progres_id,
                'tanggal' => optional($progres->tanggal)->format('Y-m-d'),
                'jenis_aktivitas' => [
                    'label' => $progres->jenisAktivitas->label ?? '-',
                    'nama' => $progres->jenisAktivitas->nama ?? '-'
                ],
                'kategori' => [
                    'label' => $progres->jenisAktivitas->kategoriAktivitas->label ?? '-'
                ],
                'catatan' => $progres->catatan ?? '',
                'created_at' => $progres->created_at ? $progres->created_at->format('Y-m-d H:i:s') : null,
                'updated_at' => $progres->updated_at ? $progres->updated_at->format('Y-m-d H:i:s') : null,
                
                // Transform field values (prevent N+1 query)
                'field_values' => $progres->fieldValues->map(function($fv) {
                    return [
                        'label' => $fv->fieldDefinition->field_label ?? '-',
                        'value' => $fv->field_value ?? '-',
                        'satuan' => $fv->fieldDefinition->satuan ?? ''
                    ];
                })->values()->all(),
                
                // Transform documentation (prevent N+1 query)
                'progres_dokumentasi' => $progres->dokumentasi->map(function($doc) {
                    return [
                        'image_path' => Storage::url($doc->image_path),
                        'nama' => $doc->nama ?? null,
                    ];
                })->values()->all(),
                
                // URLs
                'edit_url' => route('plot.progres.edit', [$plot, $progres]),
                'delete_url' => route('plot.progres.destroy', [$plot, $progres]),
                'can_update' => auth()->user()->can('update', $progres),
                'can_delete' => auth()->user()->can('delete', $progres),
            ];
            
            return $progres;
        });

        return $progresData;
    }

    /**
     * Process polygon input into a Magellan Polygon object
     */
    public static function processPolygon($polygonInput): Polygon
    {
        $polygonArray = is_string($polygonInput)
            ? json_decode($polygonInput, true)
            : $polygonInput;

        if (isset($polygonArray[0][0]) && is_numeric($polygonArray[0][0])) {
            $polygonArray = [$polygonArray];
        }

        foreach ($polygonArray as &$ring) {
            $first = $ring[0];
            $last = end($ring);
            if ($first !== $last) {
                $ring[] = $first;
            }
        }
        unset($ring);

        $lineStrings = array_map(function ($ring) {
            $points = array_map(fn($coords) => Point::make($coords[0], $coords[1]), $ring);
            return LineString::make($points);
        }, $polygonArray);

        return Polygon::make($lineStrings);
    }
}