<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use App\Models\PlotHandover;
use App\Models\PlotHandoverFile;
use App\Http\Requests\PlotHandover\StoreHandoverRequest;
use App\Http\Requests\PlotHandover\UpdateHandoverRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class PlotHandoverController extends Controller
{
    use AuthorizesRequests;

    public function store(StoreHandoverRequest $request, $plotId)
    {
        $plot = Plot::findOrFail($plotId);
        $this->authorize('create', [PlotHandover::class, $plot]);

        DB::beginTransaction();
        try {
            $handover = PlotHandover::create([
                'plot_id' => $plotId,
                'luas' => $request->validated('luas'),
                'lokasi' => $request->validated('lokasi'),
                'tanggal' => $request->validated('tanggal'),
            ]);

            $this->handleFileUploads($request, $handover, 'surat');
            $this->handleFileUploads($request, $handover, 'peta');

            DB::commit();

            return redirect()->route('plot.show', $plotId)
                ->with('success', 'Data serah terima lahan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create handover: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data serah terima lahan. Silakan coba lagi.');
        }
    }

    public function update(UpdateHandoverRequest $request, $plotId, $handoverId)
    {
        $plot = Plot::findOrFail($plotId);
        $handover = PlotHandover::where('plot_id', $plotId)->findOrFail($handoverId);
        
        $this->authorize('update', $handover);

        DB::beginTransaction();
        try {
            $handover->update([
                'luas' => $request->validated('luas'),
                'lokasi' => $request->validated('lokasi'),
                'tanggal' => $request->validated('tanggal'),
            ]);

            $this->handleFileUploads($request, $handover, 'surat');
            $this->handleFileUploads($request, $handover, 'peta');

            DB::commit();

            return redirect()->route('plot.show', $plotId)
                ->with('success', 'Data serah terima lahan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update handover: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data serah terima lahan. Silakan coba lagi.');
        }
    }

    /**
     * Delete entire handover record including all files
     */
    public function destroy($plotId, $handoverId)
    {
        $plot = Plot::findOrFail($plotId);
        $handover = PlotHandover::where('plot_id', $plotId)->findOrFail($handoverId);
        
        $this->authorize('delete', $handover);

        DB::beginTransaction();
        try {
            // Delete all files from storage
            foreach ($handover->files as $file) {
                if (Storage::disk('public')->exists($file->file_path)) {
                    Storage::disk('public')->delete($file->file_path);
                }
            }

            // Delete handover record (files will be cascade deleted by foreign key)
            $handover->delete();

            DB::commit();

            return redirect()->route('plot.show', $plotId)
                ->with('success', 'Data serah terima lahan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to delete handover: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Gagal menghapus data serah terima lahan. Silakan coba lagi.');
        }
    }

    /**
     * Delete individual file only
     */
    public function deleteFile($plotId, $handoverId, $fileId)
    {
        $plot = Plot::findOrFail($plotId);
        $handover = PlotHandover::where('plot_id', $plotId)->findOrFail($handoverId);
        $file = PlotHandoverFile::where('plot_handover_id', $handoverId)->findOrFail($fileId);
        
        $this->authorize('deleteFiles', $handover);

        try {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            
            $file->delete();

            return back()->with('success', 'File berhasil dihapus.');

        } catch (\Exception $e) {
            \Log::error('Failed to delete file: ' . $e->getMessage());
            
            return back()->with('error', 'Gagal menghapus file. Silakan coba lagi.');
        }
    }

    private function handleFileUploads($request, PlotHandover $handover, string $type)
    {
        // Handle removed files
        $removeKey = 'remove_' . $type;
        if ($request->has($removeKey)) {
            $filesToRemove = $request->input($removeKey, []);
            foreach ($filesToRemove as $fileId) {
                $file = PlotHandoverFile::where('plot_handover_id', $handover->getKey())
                    ->where('type', $type)
                    ->where('plot_handover_file_id', $fileId)
                    ->first();
                
                if ($file) {
                    if (Storage::disk('public')->exists($file->file_path)) {
                        Storage::disk('public')->delete($file->file_path);
                    }
                    $file->delete();
                }
            }
        }

        // Handle new uploads
        if (!$request->hasFile($type)) {
            return;
        }

        $files = $request->file($type);
        
        foreach ($files as $file) {
            try {
                $path = $file->store("handover/{$type}", 'public');
                
                PlotHandoverFile::create([
                    'plot_handover_id' => $handover->getKey(),
                    'type' => $type,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to upload {$type} file: " . $e->getMessage(), [
                    'handover_id' => $handover->getKey(),
                    'file_name' => $file->getClientOriginalName(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw new \Exception("Gagal mengupload file {$type}");
            }
        }
    }
}