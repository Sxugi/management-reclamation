<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Lahan\CreateLahanRequest;
use App\Http\Requests\Lahan\UpdateLahanRequest;

class LahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lahan = Lahan::where('user_id', Auth::user()->user_id)->get();

        // Sorting the lahan by created_at in ascending order
        $lahan = $lahan->sortBy('created_at');

        return view('lahan.index', compact('lahan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lahan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLahanRequest $request)
    {     
        // Validate the request data
        $validated = $request->validated();

        // Add the current user's ID
        $validated['user_id'] = Auth::user()->user_id;
        
        $lahan = Lahan::create($validated);

        // Save the location after the model is created
        $lahan->setLocation($validated['longitude'], $validated['latitude']);
        
        return redirect()->route('lahan.index')
                        ->with('success', 'Data lahan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($lahan_id)
    {
        $lahan = Lahan::findOrFail($lahan_id);
        
        // Check if user owns this lahan or is an admin
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('detail-lahan.dashboard', compact('lahan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($lahan_id)
    {
        $lahan = Lahan::findOrFail($lahan_id);
        
        // Check if user owns this lahan or is an admin
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('lahan.edit', compact('lahan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLahanRequest $request, $lahan_id)
    {
        $lahan = Lahan::findOrFail($lahan_id);
        
        // Check if user owns this lahan or is an admin
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();

        $lahan->update($validated);

        if (isset($validated['longitude']) && isset($validated['latitude'])) {
            $lahan->setLocation(
                $validated['longitude'],
                $validated['latitude']
            );
        }

        return redirect()->route('lahan.index')
                        ->with('success', 'Data lahan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($lahan_id)
    {
        $lahan = Lahan::findOrFail($lahan_id);
        
        // Check if user owns this lahan or is an admin
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $lahan->delete();
        
        return redirect()->route('lahan.index')
                        ->with('success', 'Data lahan berhasil dihapus');
    }

    /**
     * Update the specified lahan status.
     */
    public function updateStatus(Request $request, Lahan $lahan)
    {
        $validated = $request->validate([
            'status' => 'required|in:Active,Non - Active,Finished',
        ]);
        
        $lahan->status = $validated['status'];
        $lahan->save();
        
        return redirect()->back()->with('success', 'Status lahan berhasil diperbarui.');
    }
}
