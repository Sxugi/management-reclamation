<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Illuminate\Support\Str;

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
     * The attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
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

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($plot) {
            if (empty($plot->uuid)) {
                $plot->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the coordinates of the polygon as an array.
     *
     * @return array
     */
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
        return $this->belongsTo(Lahan::class, 'lahan_id', 'lahan_id');
    }

    public function target()
    {
        return $this->hasMany(TargetProgresReklamasi::class, 'plot_id', 'plot_id');
    }

    public function progres()
    {
        return $this->hasMany(ProgresReklamasi::class, 'plot_id', 'plot_id');
    }

    public function plotProgres(): HasOne
    {
        return $this->hasOne(PlotProgres::class, 'plot_id', 'plot_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'plot_id', 'plot_id');
    }

    public function handover(): HasOne
    {
        return $this->hasOne(PlotHandover::class, 'plot_id', 'plot_id');
    }
}