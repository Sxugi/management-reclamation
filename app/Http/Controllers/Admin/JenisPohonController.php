<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisPohon;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;

class JenisPohonController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisPohon::query()
            ->withCount('pohon')
            ->with(['pohon' => function($q) {
                $q->with('lahan:lahan_id,nama_lahan')
                  ->withSum('dataRealisasi as total_realisasi', 'jumlah_batang')
                  ->withSum('dataManual as total_manual', 'jumlah_batang');
            }]);

        if ($request->filled('search')) {
            $query->where('nama_pohon', 'ILIKE', "%{$request->search}%");
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }


        $data = $query->orderBy('nama_pohon', 'asc')->paginate(8)->withQueryString();

        // Transform collection to add calculated totals
        $data->getCollection()->transform(function ($jenisPohon) {
            $jenisPohon->total_realisasi = $jenisPohon->pohon->sum('total_realisasi');
            $jenisPohon->total_manual = $jenisPohon->pohon->sum('total_manual');
            $jenisPohon->grand_total = $jenisPohon->total_realisasi + $jenisPohon->total_manual;
            
            return $jenisPohon;
        });

        return view('admin.jenis-pohon.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pohon' => [
                'required',
                'string',
                'max:100',
                'unique:jenis_pohon,nama_pohon'
            ],
            'kategori'   => [
                'required', 
                'string', 
                Rule::in(array_keys(JenisPohon::KATEGORI))
            ],
        ], [
            'nama_pohon.required' => 'Nama jenis pohon harus diisi.',
            'nama_pohon.unique' => 'Nama jenis pohon ini sudah ada.',
            'nama_pohon.max' => 'Nama jenis pohon maksimal 100 karakter.',
            'kategori.required' => 'Kategori harus diisi.',
        ]);

        try {
            JenisPohon::create($validated);

            return back()->with('success', 'Jenis Pohon berhasil ditambahkan.');
        } catch (QueryException $e) {
            \Log::error('Failed to create jenis pohon', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan jenis pohon.');
        }
    }

    public function update(Request $request, JenisPohon $jenisPohon)
    {
        $validated = $request->validate([
            'nama_pohon' => [
                'required',
                'string',
                'max:100',
                Rule::unique('jenis_pohon', 'nama_pohon')
                    ->ignore($jenisPohon->jenis_pohon_id, 'jenis_pohon_id')
            ],
            'kategori'   => [
                'required', 
                'string', 
                Rule::in(array_keys(JenisPohon::KATEGORI))
            ],
        ], [
            'nama_pohon.required' => 'Nama jenis pohon harus diisi.',
            'nama_pohon.unique' => 'Nama jenis pohon ini sudah ada.',
            'nama_pohon.max' => 'Nama jenis pohon maksimal 100 karakter.',
            'kategori.required' => 'Kategori harus diisi.',
        ]);

        try {
            $jenisPohon->update($validated);

            return back()->with('success', 'Jenis Pohon berhasil diperbarui.');
        } catch (QueryException $e) {
            \Log::error('Failed to update jenis pohon', [
                'jenis_pohon_id' => $jenisPohon->jenis_pohon_id,
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui jenis pohon.');
        }
    }

    public function destroy(JenisPohon $jenisPohon)
    {
        $pohonCount = $jenisPohon->pohon()->count();
        
        if ($pohonCount > 0) {
            return back()->with('error', 
                "Gagal hapus! Jenis Pohon ini sedang digunakan di {$pohonCount} lahan."
            );
        }

        try {
            $namaPohon = $jenisPohon->nama_pohon;
            $jenisPohon->delete();

            return back()->with('success', "Jenis Pohon '{$namaPohon}' berhasil dihapus.");
        } catch (QueryException $e) {
            if ($e->getCode() == "23000" || str_contains($e->getMessage(), 'foreign key constraint')) {
                return back()->with('error', 'Gagal hapus! Jenis Pohon ini sedang digunakan di data lahan.');
            }

            \Log::error('Failed to delete jenis pohon', [
                'jenis_pohon_id' => $jenisPohon->jenis_pohon_id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menghapus jenis pohon.');
        }
    }
}