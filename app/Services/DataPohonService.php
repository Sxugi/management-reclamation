<?php

namespace App\Services;

use App\Models\Pohon;
use Illuminate\Http\Request;
use App\Models\Lahan; 

class DataPohonService
{
    protected static array $allowedSorts = [
        'jenis_pohon',
        'tahun',
        'jumlah',
        'total'
    ];

    public static function getFilteredData(Request $request, Lahan $lahan)
    {
        $query = Pohon::where('lahan_id', $lahan->lahan_id);

        if ($request->filled('pohonType')) {
            $query->where('jenis_pohon', $request->pohonType);
        }

        $relationFilter = function ($q) use ($request) {
            if ($request->filled('year')) {
                $q->where('tahun', $request->year);
            } else {    
                $start = $request->startYear;
                $end   = $request->endYear;
                if ($request->filled('startYear') && $request->filled('endYear')) {
                    if ($start > $end) {
                        [$start, $end] = [$end, $start];
                    }
                    $q->whereBetween('tahun', [$start, $end]);
                } elseif ($request->filled('startYear')) {
                    $q->where('tahun', '>=', $start);
                } elseif ($request->filled('endYear')) {
                    $q->where('tahun', '<=', $end);
                }
            }

            if ($request->filled('minQuantity')) {
                $q->where('jumlah', '>=', $request->minQuantity);
            }
        };

        $query->whereHas('dataPohon', function($q) use ($relationFilter) {
            $relationFilter($q);
        });

        // Eager load filtered relation
        $query->whereHas('dataPohon', function($q) use ($relationFilter) {
            $relationFilter($q);
        });

        $sort = $request->get('tableSortColumn');
        $direction = $request->get('tableSortDirection');

        if ($sort && in_array($sort, self::$allowedSorts) && in_array($direction, ['asc', 'desc'])) {
            if ($sort === 'jenis_pohon') {
                $query->orderBy($sort, $direction);
            } elseif ($sort === 'total') {
                $query->withCount(['dataPohon as total' => function($q) use ($relationFilter) {
                    $relationFilter($q);
                    $q->select(\DB::raw('COALESCE(SUM(jumlah), 0)'));
                }])->orderBy('total', $direction);
            } else {
                $query->with(['dataPohon' => function($q) use ($sort, $direction) {
                    $q->orderBy($sort, $direction);
                }]);
            }
        }

        return $query->paginate(8)->appends($request->query());
    }

    public static function mapDataPohonByTahun($pohonCollection, $sortDirection = 'asc')
    {
        // If paginator passed, transform its collection externally; caller typically passes getCollection()
        foreach ($pohonCollection as $pohon) {
            // Ensure relation exists
            $dataPohon = $pohon->dataPohon ?? collect();

            // sort relation by tahun
            $sortedDataPohon = $sortDirection === 'desc'
                ? $dataPohon->sortByDesc('tahun')
                : $dataPohon->sortBy('tahun');

            $tahunMap = [];
            foreach ($sortedDataPohon as $dp) {
                $tahunMap[(int)$dp->tahun] = $dp;
            }
            $pohon->dataPohonByTahun = $tahunMap;

            // Compute SUM total jumlah across all loaded dataPohon for this jenis pohon
            $pohon->SUM = (int) $dataPohon->sum(function ($r) {
                return (int) ($r->jumlah ?? 0);
            });
        }

        return $pohonCollection;
    }

    public static function getTahunList($pohonCollection, $sort = 'tahun', $direction = 'asc')
    {
        $tahunList = collect($pohonCollection)
            ->flatMap(function ($data) {
                return array_keys($data->dataPohonByTahun ?? []);
            })
            ->unique()
            ->toArray();

        if ($sort === 'tahun' && in_array($direction, ['asc', 'desc'])) {
            usort($tahunList, function ($a, $b) use ($direction) {
                return $direction === 'asc' ? $a <=> $b : $b <=> $a;
            });
        } else {
            sort($tahunList);
        }

        return $tahunList;
    }

    public static function hasFilter(Request $request)
    {
        return $request->filled('year') ||
               $request->filled('pohonType') ||
               $request->filled('minQuantity') ||
               $request->filled('startYear') ||
               $request->filled('endYear') ||
               $request->filled('total');
    }

    public static function getJenisPohonList($lahan_id)
    {
        return Pohon::where('lahan_id', $lahan_id)
            ->distinct()
            ->pluck('jenis_pohon')
            ->toArray();
    }
}
