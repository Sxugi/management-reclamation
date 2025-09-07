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
    ];

    public static function getFilteredData(Request $request, Lahan $lahan)
    {
        $query = Pohon::where('lahan_id', $lahan->lahan_id);

        if ($request->filled('pohonType')) {
            $query->where('jenis_pohon', $request->pohonType);
        }

        $relationFilter = function ($q) use ($request) {
            if ($request->filled('tahun')) {
                $q->where('tahun', $request->tahun);
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

        // Eager load filtered relation
        $query->with(['dataPohon' => $relationFilter]);

        $sort = $request->get('tableSortColumn');
        $direction = $request->get('tableSortDirection');

        if ($sort && in_array($sort, self::$allowedSorts) && in_array($direction, ['asc', 'desc'])) {
            if ($sort === 'jenis_pohon') {
                $query->orderBy($sort, $direction);
            } else {
                $query->with(['dataPohon' => function($q) use ($sort, $direction) {
                    $q->orderBy($sort, $direction);
                }]);
            }
        }

        return $query->paginate(8)->appends($request->query());
    }

    public static function mapDataPohonByTahun($pohonCollection, $sortDirection)
    {
        foreach ($pohonCollection as $pohon) {
            $tahunMap = [];
            $sortedDataPohon = $sortDirection === 'desc'
                ? $pohon->dataPohon->sortByDesc('tahun')
                : $pohon->dataPohon->sortBy('tahun');
            foreach ($sortedDataPohon as $dp) {
                $tahunMap[$dp->tahun] = $dp;
            }
            $pohon->dataPohonByTahun = $tahunMap;
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
        return $request->filled('tahun') ||
               $request->filled('pohonType') ||
               $request->filled('minQuantity') ||
               $request->filled('startYear') ||
               $request->filled('endYear');
    }

    public static function getJenisPohonList($lahan_id)
    {
        return Pohon::where('lahan_id', $lahan_id)
            ->distinct()
            ->pluck('jenis_pohon')
            ->toArray();
    }
}
