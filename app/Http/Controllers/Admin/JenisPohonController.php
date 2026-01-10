<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisPohon;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class JenisPohonController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisPohon::query()
            ->withCount('pohon')
            ->withSum('dataPohon', 'jumlah')
            ->with(['pohon' => function($q) {
                $q->with('lahan:lahan_id,nama_lahan')
                  ->withSum('dataPohon', 'jumlah');
            }]);

        if ($request->filled('search')) {
            $query->where('nama_pohon', 'ILIKE', "%{$request->search}%");
        }

        $data = $query->orderBy('nama_pohon', 'asc')->paginate(8)->withQueryString();

        return view('admin.jenis-pohon.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pohon' => 'required|string|max:100|unique:jenis_pohon,nama_pohon'
        ], [
            'nama_pohon.unique' => 'Nama jenis pohon ini sudah ada.'
        ]);

        JenisPohon::create($request->all());

        return back()->with('success', 'Jenis Pohon berhasil ditambahkan.');
    }

    public function update(Request $request, JenisPohon $jenisPohon)
    {
        $request->validate([
            'nama_pohon' => 'required|string|max:100|unique:jenis_pohon,nama_pohon,' . $jenisPohon->jenis_pohon_id . ',jenis_pohon_id'
        ], [
            'nama_pohon.unique' => 'Nama jenis pohon ini sudah ada.'
        ]);

        $jenisPohon->update($request->all());

        return back()->with('success', 'Jenis Pohon berhasil diperbarui.');
    }

    public function destroy(JenisPohon $jenisPohon)
    {
        if ($jenisPohon->pohon()->exists()) {
            return back()->with('error', 'Gagal hapus! Jenis Pohon ini sedang digunakan di data lahan.');
        }
        
        try {
            $jenisPohon->delete();
            return back()->with('success', 'Jenis Pohon berhasil dihapus.');
        } catch (QueryException $e) {
            if ($e->getCode() == "23000") {
                return back()->with('error', 'Gagal hapus! Jenis Pohon ini sedang digunakan di data lahan.');
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}