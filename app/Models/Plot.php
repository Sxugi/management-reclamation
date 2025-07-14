<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Clickbar\Magellan\Data\Geometries\Polygon;

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
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plot';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $fillable = [
        'lahan_id',
        'nama_plot',
        'luas_area',
        'polygon'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'polygon' => Polygon::class,
        'luas_area' => 'float',
    ];

    public function getCoordinatesAttribute()
    {
        if (!$this->polygon) {
            return [];
        }
        $coordinates = [];
        foreach ($this->polygon->getLineStrings() as $ring) {
            $coords = [];
            foreach ($ring->getPoints() as $point) {
                $coords[] = [$point->getX(), $point->getY()];
            }
            $coordinates[] = $coords;
        }
        return $coordinates[0] ?? [];
    }

    public function getRouteKeyName()
    {
        return 'plot_id';
    }

    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class);
    }
}