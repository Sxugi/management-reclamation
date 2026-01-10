<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Lahan\StoreLahanRequest;
use App\Http\Requests\Lahan\UpdateLahanRequest;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Support\Facades\Gate;

class LahanController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user can view list
        $this->authorize('viewAny', Lahan::class);

        // Get lahan based on user role (automatic filtering)
        $lahan = Lahan::forUser(auth()->user())
            ->with(['users' => function($query) {
                $query->wherePivot('role', 'owner')->limit(1);
            }])
            ->latest()
            ->paginate(5);

        return view('lahan.index', compact('lahan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user can create lahan
        $this->authorize('create', Lahan::class);

        return view('lahan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLahanRequest $request)
    {     
        // Check if user can create lahan
        $this->authorize('create', Lahan:: class);

        DB::beginTransaction();
        try {
            // Validate the request data
            $validated = $request->validated();
            $validated['location'] = Point::make($validated['longitude'], $validated['latitude']);

            $lahan = Lahan::create($validated);

            DB::commit();
            return redirect()
                ->route('lahan.index')
                ->with('success', 'Data lahan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create lahan: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan lahan:  ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lahan $lahan)
    {        
        // Check if user owns this lahan or is an admin
        $this->authorize('view', $lahan);
        
        return redirect()->route('detail-lahan.dashboard', $lahan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lahan $lahan)
    {        
        // Check if user owns this lahan or is an admin
        $this->authorize('update', $lahan);

        $point = $lahan->location;

        return view('lahan.edit', compact('lahan', 'point'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLahanRequest $request, Lahan $lahan)
    {        
        // Check if user owns this lahan or is an admin
        $this->authorize('update', $lahan);

        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $validated['location'] = Point::make($validated['longitude'], $validated['latitude']);

            $lahan->update($validated);

            DB::commit();

            return redirect()->route('lahan.index')
                    ->with('success', 'Data lahan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update lahan: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui lahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lahan $lahan)
    {        
        // Check if user owns this lahan or is an admin
        $this->authorize('delete', $lahan);
        
        try {
            $namaLahan = $lahan->nama_lahan;
            $lahan->delete();

            return redirect()
                ->route('lahan.index')
                ->with('success', "Lahan {$namaLahan} berhasil dihapus");

        } catch (\Exception $e) {
            \Log::error('Failed to delete lahan: ' . $e->getMessage());
            
            return redirect()
                ->route('lahan.index')
                ->with('error', 'Gagal menghapus lahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified lahan status.
     */
    public function updateStatus(Request $request, Lahan $lahan)
    {
        // Check if user owns this lahan or is an admin
        $this->authorize('update', $lahan);

        $validated = $request->validate([
            'status' => 'required|in:Active,Non - Active,Finished',
        ]);
        
        DB::beginTransaction();
        try {
            $lahan->update(['status' => $validated['status']]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Status lahan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update lahan status: ' . $e->getMessage());
            
            return back()->with('error', 'Gagal memperbarui status:  ' . $e->getMessage());
        }
    }
}
