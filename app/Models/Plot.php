<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plot extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'plot_id';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $fillable = [
        'lahan_id',
        'nama_plot',
        'coordinates',
        'area'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'coordinates' => 'array',
        'area' => 'float',
    ];

    public function getRouteKeyName()
    {
        return 'plot_id';
    }

    // Accessor to ensure coordinates are in correct format for MapLibre
    public function getCoordinatesAttribute($value)
    {
        $coordinates = json_decode($value, true);
        
        if (!is_array($coordinates)) {
            return [];
        }
        
        // Convert coordinates to proper format
        return collect($coordinates)->map(function ($coord) {
            // If coordinate is in {lng, lat} object format
            if (is_array($coord) && isset($coord['lng']) && isset($coord['lat'])) {
                return [(float) $coord['lng'], (float) $coord['lat']];
            }
            
            // If coordinate is already in [lng, lat] array format
            if (is_array($coord) && count($coord) >= 2) {
                return [(float) $coord[0], (float) $coord[1]];
            }
            
            return $coord;
        })->toArray();
    }

    // Mutator to store coordinates consistently
    public function setCoordinatesAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }
        
        if (!is_array($value)) {
            $this->attributes['coordinates'] = json_encode([]);
            return;
        }
        
        // Ensure coordinates are stored as [lng, lat] arrays
        $normalized = collect($value)->map(function ($coord) {
            if (is_array($coord) && isset($coord['lng']) && isset($coord['lat'])) {
                return [(float) $coord['lng'], (float) $coord['lat']];
            }
            
            if (is_array($coord) && count($coord) >= 2) {
                return [(float) $coord[0], (float) $coord[1]];
            }
            
            return $coord;
        })->toArray();
        
        $this->attributes['coordinates'] = json_encode($normalized);
    }

    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class);
    }
}