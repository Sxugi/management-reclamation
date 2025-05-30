<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LahanController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lahans = Lahan::where('user_id', Auth::user()->user_id)->get();
        return view('lahans.index', compact('lahans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lahans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lahan' => 'required|string|max:255',
            'luas_lahan' => 'required|numeric|min:0',
            'tahun_awal' => 'required|integer|min:1900|max:' . (date('Y') + 50),
            'tahun_akhir' => 'required|integer|min:' . $request->tahun_awal,
            'pic_reklamasi' => 'required|string|max:255',
        ]);
        
        // Add the current user's ID
        $validated['user_id'] = Auth::user()->user_id;
        
        Lahan::create($validated);
        
        return redirect()->route('lahans.index')
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
        
        return view('lahans.show', compact('lahan'));
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
        
        return view('lahans.edit', compact('lahan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $lahan_id)
    {
        $lahan = Lahan::findOrFail($lahan_id);
        
        // Check if user owns this lahan or is an admin
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'nama_lahan' => 'required|string|max:255',
            'luas_lahan' => 'required|numeric|min:0',
            'tahun_awal' => 'required|integer|min:1900|max:' . (date('Y') + 50),
            'tahun_akhir' => 'required|integer|min:' . $request->tahun_awal,
            'pic_reklamasi' => 'required|string|max:255',
        ]);
        
        $lahan->update($validated);
        
        return redirect()->route('lahans.index')
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
        
        return redirect()->route('lahans.index')
                        ->with('success', 'Data lahan berhasil dihapus');
    }
}
