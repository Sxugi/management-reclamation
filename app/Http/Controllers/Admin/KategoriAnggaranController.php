<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriAnggaran;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class KategoriAnggaranController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriAnggaran::query()
            ->withCount('anggaran') 
            ->withSum('anggaran', 'nominal')
            ->with(['anggaran' => function($q) {
                $q->select('anggaran_reklamasi_id', 'kategori_anggaran_id', 'lahan_id', 'nominal')
                  ->with('lahan:lahan_id,nama_lahan');
            }]);

        if ($request->filled('search')) {
            $query->where('nama_kategori', 'ILIKE', "%{$request->search}%");
        }

        $data = $query->orderBy('nama_kategori', 'asc')->paginate(8)->withQueryString();

        return view('admin.kategori-anggaran.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_anggaran,nama_kategori'
        ], [
            'nama_kategori.unique' => 'Nama kategori ini sudah ada.'
        ]);

        KategoriAnggaran::create($request->all());

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriAnggaran $kategoriAnggaran)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_anggaran,nama_kategori,' . $kategoriAnggaran->kategori_anggaran_id . ',kategori_anggaran_id'
        ], [
            'nama_kategori.unique' => 'Nama kategori ini sudah ada.'
        ]);

        $kategoriAnggaran->update($request->all());

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriAnggaran $kategoriAnggaran)
    {
        try {
            $kategoriAnggaran->delete();
            return back()->with('success', 'Kategori berhasil dihapus.');
        } catch (QueryException $e) {
            if ($e->getCode() == "23000") {
                return back()->with('error', 'Gagal hapus! Kategori ini sedang digunakan di data anggaran.');
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}