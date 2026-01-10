<?php

namespace App\Http\Controllers;

use App\Models\ReklamasiFile;
use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FileLaporanController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Lahan $lahan)
    {
        $this->authorize('viewAny', [ReklamasiFile::class, $lahan]);

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
        $this->authorize('create', [ReklamasiFile::class, $lahan]);

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',
            'tahun' => 'required|integer'
        ]);

        DB::beginTransaction();
        $newPath = null;

        try {
            $file = $request->file('file');
            $newPath = $file->store("reklamasi_files/laporan/{$request->tahun}", 'private');

            // Check if file for this year already exists
            $existing = $lahan->reklamasiFile()
                            ->laporan()
                            ->where('tahun', $request->tahun)
                            ->first();

            $oldPath = $existing ? $existing->file_path : null;

            if ($existing) {
                $existing->delete();
            }

            ReklamasiFile::create([
                'lahan_id' => $lahan->lahan_id,
                'tipe' => 'laporan',
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $newPath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'tahun' => $request->tahun,
            ]);

            DB::commit();

            return back()->with('success', "Laporan tahun {$request->tahun} berhasil diunggah.");

        } catch (\Exception $e) {
            DB::rollBack();

            if ($newPath && Storage::disk('private')->exists($newPath)) {
                Storage::disk('private')->delete($newPath);
            }

            \Log::error('Error uploading laporan file', [
                'lahan_id' => $lahan->lahan_id,
                'tahun' => $request->tahun,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Gagal mengunggah file laporan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lahan $lahan, $tahun)
    {
        $this->authorize('delete', [ReklamasiFile::class, $lahan]);

        $file = $lahan->reklamasiFile()
                     ->laporan()
                     ->where('tahun', $tahun)
                     ->first();

        if (!$file) {
            return back()->with('error', 'File laporan tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            $path = $file->file_path;
            $file->delete();

            DB::commit();

            if ($path && Storage::disk('private')->exists($path)) {
                Storage::disk('private')->delete($path);
            }

            return back()->with('success', "Laporan tahun {$tahun} berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting laporan file', [
                'lahan_id' => $lahan->lahan_id,
                'tahun' => $tahun,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Gagal menghapus file laporan.');
        }
    }

    /**
     * Preview file
     */
    public function preview(Lahan $lahan, Request $request)
    {
        $this->authorize('view', [ReklamasiFile::class, $lahan]);

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
