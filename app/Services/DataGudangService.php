<?php

namespace App\Services;

use App\Models\DataGudang;
use Illuminate\Http\Request;
use App\Models\Lahan;

class DataGudangService
{
    protected static array $allowedSorts = [
        'tanggal_masuk',
        'jenis_barang',
        'nama_barang',
        'jumlah_barang',
        'lokasi_penyimpanan',
        'status_barang'
    ];

    public static function getFilteredData(Request $request, Lahan $lahan)
    {
        $query = DataGudang::where('lahan_id', $lahan->lahan_id);

        if ($request->filled('startDate') && $request->filled('endDate')) {
            if ($request->startDate > $request->endDate) {
                [$request->startDate, $request->endDate] = [$request->endDate, $request->startDate];
            }
            $query->whereBetween('tanggal_masuk', [$request->startDate, $request->endDate]);
        } elseif ($request->filled('startDate')) {
            $query->whereDate('tanggal_masuk', '>=', $request->startDate);
        } elseif ($request->filled('endDate')) {
            $query->whereDate('tanggal_masuk', '<=', $request->endDate);
        }
        if ($request->filled('jenisBarang')) {
            $query->where('jenis_barang', $request->jenisBarang);
        }
        if ($request->filled('statusBarang')) {
            $query->where('status_barang', $request->statusBarang);
        }

        $sort = $request->get('tableSortColumn');
        $direction = $request->get('tableSortDirection');

        if ($sort && in_array($sort, self::$allowedSorts) && in_array($direction, ['asc', 'desc'])) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        return $query->paginate(8)->appends($request->query());
    }
}
