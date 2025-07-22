<?php

namespace App\Http\Controllers;

use App\Models\ReklamasiFile;
use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FileLaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Get all laporan file grouped by year
        $file = $lahan->reklamasiFile()
                      ->laporan()
                      ->orderBy('tahun')
                      ->get()
                      ->keyBy('tahun');

        return view('detail-lahan.file-laporan.index', compact('lahan', 'file'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Lahan $lahan, Request $request)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',
            'tahun' => 'required|integer'
        ]);

        // Check if file for this year already exists
        $existing = $lahan->reklamasiFile()
                         ->laporan()
                         ->where('tahun', $request->tahun)
                         ->first();

        if ($existing) {
            // Replace existing file
            Storage::disk('private')->delete($existing->file_path);
            $existing->delete();
        }

        $file = $request->file('file');
        $path = $file->store("reklamasi_file/laporan/{$request->tahun}", 'private');

        ReklamasiFile::create([
            'lahan_id' => $lahan->lahan_id,
            'type' => 'laporan',
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'tahun' => $request->tahun,
        ]);

        return back()->with('success', "Laporan tahun {$request->tahun} berhasil diunggah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lahan $lahan, $tahun)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $file = $lahan->reklamasiFile()
                     ->laporan()
                     ->where('tahun', $tahun)
                     ->first();

        if ($file) {
            Storage::disk('private')->delete($file->file_path);
            $file->delete();
        }

        return back()->with('success', "Laporan tahun {$tahun} berhasil dihapus.");
    }

    /**
     * Preview file
     */
    public function preview(Lahan $lahan, Request $request)
    {
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $tahun = $request->input('tahun');

        $file = $lahan->reklamasiFile()
                     ->laporan()
                     ->where('tahun', $tahun)
                     ->first();

        if (!$file || !Storage::disk('private')->exists($file->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        $path = Storage::disk('private')->path($file->file_path);
        
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $file->file_name . '"'
        ]);
    }
}
