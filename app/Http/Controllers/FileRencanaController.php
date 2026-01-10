<?php

namespace App\Http\Controllers;

use App\Models\ReklamasiFile;
use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FileRencanaController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Lahan $lahan)
    {
        $this->authorize('viewAny', [ReklamasiFile::class, $lahan]);

        $file = $lahan->reklamasiFile()->rencana()->first();

        return view('detail-lahan.file-rencana.index', compact('lahan', 'file'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Lahan $lahan, Request $request)
    {
        $this->authorize('create', [ReklamasiFile::class, $lahan]);

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',
        ]);

        DB::beginTransaction();
        $newPath = null;

        try {
            $file = $request->file('file');
            $newPath = $file->store('reklamasi_files/rencana', 'private');

            $existing = $lahan->reklamasiFile()->rencana()->first();
            $oldPath = $existing ? $existing->file_path : null;

            if ($existing) {
                    $existing->delete();
                }

            ReklamasiFile::create([
                'lahan_id'  => $lahan->lahan_id,
                'tipe'      => 'rencana',
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $newPath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);

            DB::commit();

            if ($oldPath && Storage::disk('private')->exists($oldPath)) {
                Storage::disk('private')->delete($oldPath);
            }

            return back()->with('success', 'File rencana berhasil diunggah.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($newPath && Storage::disk('private')->exists($newPath)) {
                Storage::disk('private')->delete($newPath);
            }

            \Log::error('Error uploading file rencana', [
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Gagal mengunggah file: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lahan $lahan)
    {
        $this->authorize('delete', [ReklamasiFile::class, $lahan]);

        $file = $lahan->reklamasiFile()->rencana()->first();

        if (!$file) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            $path = $file->file_path;
            $file->delete();

            DB::commit();

            if ($path && Storage::disk('private')->exists($path)) {
                Storage::disk('private')->delete($path);
            }

            return back()->with('success', 'File rencana berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting file rencana', [
                'lahan_id' => $lahan->lahan_id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Gagal menghapus file.');
        }
    }
    
    /**
     * Preview file
     */
    public function preview(Lahan $lahan)
    {
        $this->authorize('view', [ReklamasiFile::class, $lahan]);

        $file = $lahan->reklamasiFile()->rencana()->first();
        
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
