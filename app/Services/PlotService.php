<?php

namespace App\Services;

use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Support\Facades\Storage;
use App\Models\Plot;
use App\Models\JenisPohon;
use App\Models\TargetProgresReklamasi;
use App\Models\ProgresReklamasi;

class PlotService
{
    /**
     * Transform progres data for modal display to prevent N+1 queries
     */
    public static function transformProgresForModal($progresData, Plot $plot)
    {
        $jenisPohonMap = JenisPohon::all()->keyBy('jenis_pohon_id');

        $progresData->getCollection()->transform(function ($progres) use ($plot, $jenisPohonMap) {
            $transformedFieldValues = $progres->fieldValues->map(function($fv) use ($jenisPohonMap) {
                $fieldKey = $fv->fieldDefinition->field_key ?? null;
                $fieldValue = $fv->field_value ?? '-';

                if ($fieldKey === 'jenis_pohon_id' && isset($jenisPohonMap[$fieldValue])) {
                    $jenisPohon = $jenisPohonMap[$fieldValue];
                    $displayValue = $jenisPohon->nama_pohon;
                } elseif ($fieldKey === 'metode_sampling') {
                    $displayValue = ucwords(str_replace('_', ' ', $fieldValue));
                } else {
                    $displayValue = $fieldValue;
                }
                
                return [
                    'label' => $fv->fieldDefinition->field_label ?? '-',
                    'value' => $displayValue,  // Use resolved name
                    'raw_value' => $fieldValue,  // Keep original ID
                    'satuan' => $fv->fieldDefinition->satuan ?? '',
                    'field_key' => $fieldKey,  // Keep field key for reference
                ];
            })->values()->all();

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
                'field_values' => $transformedFieldValues,
                
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

    /**
     * Get photo marker data for a given plot's progres dokumentasi with location
     */
    public static function getPhotoMarkerData($plot)
    {
        $photoMarkersData = [];

        if (!$plot->progres) {
            return $photoMarkersData;
        }

        // Preload all jenis pohon to avoid N+1 queries
        $jenisPohonMap = \App\Models\JenisPohon::all()->keyBy('jenis_pohon_id');

        foreach ($plot->progres as $progres) {
            if (!$progres->dokumentasi || $progres->dokumentasi->isEmpty()) {
                continue;
            }

            // Load relations to prevent N+1
            $progres->load([
                'jenisAktivitas.kategoriAktivitas',
                'indikator',
                'fieldValues.fieldDefinition'
            ]);

            // Get kategori and jenis aktivitas
            $kategoriField = $progres->jenisAktivitas->kategoriAktivitas->field ?? null;
            $jenisAktivitasField = $progres->jenisAktivitas->field ?? null;
            $kategoriLabel = $progres->jenisAktivitas->kategoriAktivitas->label ?? '-';
            $jenisAktivitasLabel = $progres->jenisAktivitas->label ?? '-';

            // Parse field values to array and resolve jenis pohon
            $fieldValuesArray = [];
            foreach ($progres->fieldValues as $fv) {
                $key = $fv->fieldDefinition->field_key ?? null;
                $value = $fv->field_value;
                
                if ($key) {
                    $fieldValuesArray[$key] = $value;
                    
                    // Resolve jenis pohon nama
                    if ($key === 'jenis_pohon_id' && isset($jenisPohonMap[$value])) {
                        $jenisPohon = $jenisPohonMap[$value];
                        $fieldValuesArray['jenis_pohon_nama'] = $jenisPohon->nama_pohon;
                    }
                }
            }

            // Extract detail info
            $detailInfo = self::extractDetailInfo(
                $kategoriField,
                $jenisAktivitasField,
                $fieldValuesArray
            );

            // Process each dokumentasi with location
            foreach ($progres->dokumentasi as $dok) {
                if (!$dok->location) {
                    continue;
                }

                try {
                    $location = $dok->location;

                    // Convert if string
                    if (is_string($location)) {
                        $location = Point::fromWkt($location);
                    }

                    $lat = $location->getLatitude();
                    $lng = $location->getLongitude();

                    if (!is_numeric($lat) || !is_numeric($lng)) {
                        continue;
                    }

                    $photoMarkersData[] = [
                        'latitude' => (float) $lat,
                        'longitude' => (float) $lng,
                        'image_url' => asset('storage/' . $dok->image_path),
                        'image_path' => $dok->image_path,
                        'created_at' => $dok->created_at->format('d M Y H:i'),
                        'progres_id' => $dok->progres_id,
                        'progres_dokumentasi_id' => $dok->progres_dokumentasi_id,
                        
                        // Info progres
                        'kategori' => $kategoriField,
                        'kategori_label' => $kategoriLabel,
                        'jenis_aktivitas' => $jenisAktivitasField,
                        'jenis_aktivitas_label' => $jenisAktivitasLabel,
                        'tanggal_kegiatan' => $progres->tanggal ? $progres->tanggal->format('d M Y') : null,
                        'detail_info' => $detailInfo,
                    ];

                } catch (\Exception $e) {
                    \Log::warning('Error processing location for dokumentasi', [
                        'id' => $dok->progres_dokumentasi_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return $photoMarkersData;
    }

    /**
     * Extract detail information based on activity category
     */
    private static function extractDetailInfo($kategori, $jenisAktivitas, $fieldValues)
    {
        $info = [];

        if (!$fieldValues || !is_array($fieldValues)) {
            return $info;
        }

        // Extract based on category
        switch ($kategori) {
            case 'perbaikan_tanah':
                self::addInfoIfExists($info, 'Luas', $fieldValues, 'luas_area', 'ha');
                
                // Check jenis bahan
                if (isset($fieldValues['jenis_kompos'])) {
                    $info[] = ['label' => 'Bahan', 'value' => $fieldValues['jenis_kompos']];
                } elseif (isset($fieldValues['jenis_pupuk'])) {
                    $info[] = ['label' => 'Pupuk', 'value' => $fieldValues['jenis_pupuk']];
                } elseif (isset($fieldValues['jenis_kapur'])) {
                    $info[] = ['label' => 'Kapur', 'value' => $fieldValues['jenis_kapur']];
                }
                
                self::addInfoIfExists($info, 'Dosis', $fieldValues, 'dosis_aplikasi', 'kg/ha');
                self::addInfoIfExists($info, 'Total', $fieldValues, 'total_berat', 'kg');
                self::addInfoIfExists($info, 'Total', $fieldValues, 'total_pupuk', 'kg');
                self::addInfoIfExists($info, 'Total', $fieldValues, 'total_kapur', 'kg');
                break;

            case 'cover_crops':
                self::addInfoIfExists($info, 'Luas', $fieldValues, 'luas_area', 'ha');
                self::addInfoIfExists($info, 'Spesies', $fieldValues, 'jenis_pohon_nama');
                self::addInfoIfExists($info, 'Benih', $fieldValues, 'berat_benih', 'kg');
                self::addInfoIfExists($info, 'Pola', $fieldValues, 'pola_tanam');
                break;

            case 'penanaman_pohon':
                self::addInfoIfExists($info, 'Luas', $fieldValues, 'luas_area', 'ha');
                self::addInfoIfExists($info, 'Spesies', $fieldValues, 'jenis_pohon_nama');
                self::addInfoIfExists($info, 'Jumlah', $fieldValues, 'jumlah_bibit', 'btg');
                self::addInfoIfExists($info, 'Jarak', $fieldValues, 'jarak_tanam');
                break;

            case 'pemeliharaan':
                self::addInfoIfExists($info, 'Luas', $fieldValues, 'luas_area', 'ha');
                
                // Detail based on jenis aktivitas
                if ($jenisAktivitas === 'penyiangan') {
                    self::addInfoIfExists($info, 'Metode', $fieldValues, 'metode');
                } elseif ($jenisAktivitas === 'pemupukan') {
                    self::addInfoIfExists($info, 'Pupuk', $fieldValues, 'jenis_pupuk');
                    self::addInfoIfExists($info, 'Total', $fieldValues, 'total_pupuk', 'kg');
                    self::addInfoIfExists($info, 'Dosis', $fieldValues, 'dosis_aplikasi', 'gr/pohon');
                } elseif ($jenisAktivitas === 'pengendalian_hama') {
                    self::addInfoIfExists($info, 'Target', $fieldValues, 'target_hama');
                    self::addInfoIfExists($info, 'Obat', $fieldValues, 'nama_obat');
                } elseif ($jenisAktivitas === 'penyulaman') {
                    self::addInfoIfExists($info, 'Tanaman', $fieldValues, 'jenis_pohon_nama');
                    self::addInfoIfExists($info, 'Jumlah', $fieldValues, 'jumlah_tanaman', 'btg');
                }
                break;

            case 'monitoring':
                self::addInfoIfExists($info, 'Area', $fieldValues, 'luas_area_sampling', 'ha');
                self::addInfoIfExists($info, 'Spesies', $fieldValues, 'jenis_pohon_nama');
                
                if ($jenisAktivitas === 'monitoring_survival_rate') {
                    // Calculate SR if possible
                    if (isset($fieldValues['jumlah_bibit_hidup']) && isset($fieldValues['jumlah_bibit_mati'])) {
                        $hidup = (int) $fieldValues['jumlah_bibit_hidup'];
                        $mati = (int) $fieldValues['jumlah_bibit_mati'];
                        $total = $hidup + $mati;
                        
                        if ($total > 0) {
                            $sr = round(($hidup / $total) * 100, 1);
                            $info[] = ['label' => 'SR', 'value' => $sr . '%'];
                        }
                        
                        $info[] = ['label' => 'Hidup', 'value' => $hidup . ' btg'];
                    }
                } elseif ($jenisAktivitas === 'monitoring_pertumbuhan') {
                    self::addInfoIfExists($info, 'Sampel', $fieldValues, 'jumlah_sampel_diukur', 'btg');
                    self::addInfoIfExists($info, 'Tinggi', $fieldValues, 'tinggi_tanaman_rata', 'cm');
                    self::addInfoIfExists($info, 'Diameter', $fieldValues, 'diameter_batang_rata', 'mm');
                }
                break;
        }

        return $info;
    }

    /**
     * Helper to add info if field exists and is not empty
     */
    private static function addInfoIfExists(&$info, $label, $fieldValues, $key, $unit = null)
    {
        if (isset($fieldValues[$key]) && $fieldValues[$key] !== '' && $fieldValues[$key] !== null) {
            $value = $fieldValues[$key];
            if ($unit) {
                $value .= ' ' . $unit;
            }
            $info[] = ['label' => $label, 'value' => $value];
        }
    }

    /**
     * Get technical summary of a plot's progress for reporting purposes
     */
    public static function getTechnicalSummary(Plot $plot)
    {
        // Declaration of summary array
        $summary = [];
        $pohonMap = \App\Models\JenisPohon::pluck('nama_pohon', 'jenis_pohon_id')->toArray();
        $groupedByKategori = $plot->progres->groupBy('jenisAktivitas.kategoriAktivitas.label');

        // Loop through each Kategori
        foreach ($groupedByKategori as $kategoriLabel => $progresList) {
            
            $kategoriData = [
                'total_luas' => 0,
                'total_items' => 0, 
                'unit_utama' => '',
                'activities' => []
            ];

            $luasPerJenisAktivitas = []; 

            // Group by Jenis Aktivitas
            $groupedByActivity = $progresList->groupBy('jenisAktivitas.label');

            foreach ($groupedByActivity as $activityName => $items) {
                $actData = [
                    'luas' => 0,
                    'items_detail' => [], 
                    'monitoring_stats' => [] 
                ];

                // Get monitoring stats if this is a monitoring category
                if (str_contains(strtolower($kategoriLabel), 'monitoring')) {
                    
                    // Group by jenis pohon (resolve nama pohon)
                    $monitoringPerPohon = $items->groupBy(function ($item) {
                        return $item->fieldValues->firstWhere('fieldDefinition.field_key', 'jenis_pohon_id')?->field_value ?? 'unknown';
                    });

                    foreach ($monitoringPerPohon as $pohonId => $historyLogs) {
                        // Get log terbaru untuk pohon ini
                        $latestLog = $historyLogs->sortByDesc('tanggal')->first();
                        if (!$latestLog) continue;

                        $fields = $latestLog->fieldValues->mapWithKeys(fn($fv) => [$fv->fieldDefinition->field_key => $fv->field_value]);
                        $pohonLabel = $pohonId !== 'unknown' ? ($pohonMap[$pohonId] ?? 'Umum') : 'Umum';
                        $tgl = $latestLog->tanggal ? $latestLog->tanggal->format('d M Y') : '-';

                        // Initialize stats data
                        $statsData = [
                            'tanggal' => $tgl,
                            'sr_sum' => 0, 'sr_count' => 0, 'hidup' => 0, 'mati' => 0,
                            'h_sum' => 0, 'h_count' => 0
                        ];

                        // Survival Rate Calculation
                        if (isset($fields['jumlah_bibit_hidup'])) {
                            $hidup = (int)$fields['jumlah_bibit_hidup'];
                            $mati = (int)($fields['jumlah_bibit_mati'] ?? 0);
                            $total = $hidup + $mati;
                            $sr = $total > 0 ? ($hidup / $total) * 100 : 0; // Raw percent

                            $statsData['sr_sum'] = $sr; 
                            $statsData['sr_count'] = 1; // Count 1 untuk latest
                            $statsData['hidup'] = $hidup;
                            $statsData['mati'] = $mati;
                        }
                        
                        // Pertumbuhan (Tinggi Rata-rata)
                        if (isset($fields['tinggi_tanaman_rata'])) {
                            $tinggi = (float)$fields['tinggi_tanaman_rata'];
                            $statsData['h_sum'] = $tinggi;
                            $statsData['h_count'] = 1; // Count 1 untuk latest
                        }

                        // Save stats data per pohon to main array
                        if (!isset($actData['monitoring_stats'][$pohonLabel])) {
                            $actData['monitoring_stats'][$pohonLabel] = $statsData;
                        } else {
                            // Merge data if there update partial
                            $actData['monitoring_stats'][$pohonLabel] = array_merge($actData['monitoring_stats'][$pohonLabel], array_filter($statsData));
                        }
                    }
                    
                    // Save data or skip
                    $kategoriData['activities'][$activityName] = $actData;
                    continue; 
                }

                // Standard logic for other categories
                foreach ($items as $progres) {
                    $fields = $progres->fieldValues->mapWithKeys(fn($fv) => [$fv->fieldDefinition->field_key => $fv->field_value]);

                    // Luas
                    if (isset($fields['luas_area'])) {
                        $actData['luas'] += (float) $fields['luas_area'];
                    } elseif (isset($fields['luas_area_sampling'])) {
                        $actData['luas'] += (float) $fields['luas_area_sampling'];
                    }

                    // Metode & Dosis (try to find any field that contains metode or dosis)
                    $currentMetode = $fields['metode_aplikasi'] ?? ($fields['metode_tanam'] ?? ($fields['metode'] ?? ($fields['metode_sampling'] ?? null)));
                    if ($currentMetode) $currentMetode = ucwords(str_replace('_', ' ', $currentMetode));
                    $currentDosis = isset($fields['dosis_aplikasi']) ? (float)$fields['dosis_aplikasi'] : null;

                    // Item Identification Logic
                    $itemName = null;
                    $qty = 0;
                    $unit = '';

                    // Case A: Pohon (Penanaman atau Penyulaman)
                    if ((isset($fields['jumlah_bibit']) || isset($fields['jumlah_tanaman'])) && isset($fields['jenis_pohon_id'])) {
                        $qty = (int) ($fields['jumlah_bibit'] ?? $fields['jumlah_tanaman']);
                        $itemName = $pohonMap[$fields['jenis_pohon_id']] ?? 'Pohon #' . $fields['jenis_pohon_id'];
                        $unit = 'btg';
                        $kategoriData['unit_utama'] = 'btg';
                        $kategoriData['total_items'] += $qty;
                    }
                    // Case B: Cover Crops
                    elseif (isset($fields['berat_benih'])) {
                        $qty = (float) $fields['berat_benih'];
                        $itemName = isset($fields['jenis_pohon_id']) ? ($pohonMap[$fields['jenis_pohon_id']] ?? 'Legume Cover Crop') : 'Benih LCC';
                        $unit = 'Kg';
                    }
                    // Case C: Material
                    elseif (isset($fields['total_berat']) || isset($fields['total_pupuk']) || isset($fields['total_kapur']) || isset($fields['total_bahan'])) {
                        $qty = (float) ($fields['total_berat'] ?? ($fields['total_pupuk'] ?? ($fields['total_kapur'] ?? ($fields['total_bahan'] ?? 0))));
                        $itemName = $fields['jenis_kompos'] ?? ($fields['jenis_pupuk'] ?? ($fields['jenis_kapur'] ?? ($fields['nama_obat'] ?? 'Material')));
                        $unit = 'Kg';
                        
                        if ($kategoriData['unit_utama'] == '') $kategoriData['unit_utama'] = 'Kg';
                        if ($kategoriData['unit_utama'] == 'Kg') $kategoriData['total_items'] += $qty;
                    }

                    // Accumulate item data if we have an identifiable item
                    if ($itemName) {
                        if (!isset($actData['items_detail'][$itemName])) {
                            $actData['items_detail'][$itemName] = [
                                'qty' => 0,
                                'unit' => $unit,
                                'metodes' => [], 
                                'dosis_sum' => 0,
                                'dosis_count' => 0
                            ];
                        }
                        
                        $actData['items_detail'][$itemName]['qty'] += $qty;
                        
                        if ($currentMetode && !in_array($currentMetode, $actData['items_detail'][$itemName]['metodes'])) {
                            $actData['items_detail'][$itemName]['metodes'][] = $currentMetode;
                        }
                        
                        if ($currentDosis !== null) {
                            $actData['items_detail'][$itemName]['dosis_sum'] += $currentDosis;
                            $actData['items_detail'][$itemName]['dosis_count']++;
                        }
                    }
                } // End foreach items (Standard Logic)

                // Save luas for weighted average calculation if we have a valid luas
                if ($actData['luas'] > 0) {
                    $luasPerJenisAktivitas[] = $actData['luas'];
                }

                $kategoriData['activities'][$activityName] = $actData;
            } // End foreach GroupedByActivity

            // Weighted Average Calculation
            $countActivities = count($luasPerJenisAktivitas);
            $kategoriData['total_luas'] = $countActivities > 0 ? array_sum($luasPerJenisAktivitas) / $countActivities : 0;

            $summary[$kategoriLabel] = $kategoriData;
        } // End foreach GroupedByKategori

        return $summary;
    }
}