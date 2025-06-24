<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard for a specific lahan.
     * Uses nested route: /lahan/{lahan}/dashboard
     */
    public function dashboard(Lahan $lahan, Plot $plot)
    {
        // Check if user owns this lahan
        if ($lahan->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get all plots for the lahan
        $plot = Plot::where('lahan_id', $lahan->lahan_id)->get();
        
        return view('detail-lahan.dashboard', compact('lahan', 'plot'));
    }
}
