<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Clickbar\Magellan\Database\Eloquent\HasPostgisColumns;
use Clickbar\Magellan\Data\Geometries\Point;

class ProgresDokumentasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'progres_dokumentasi';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'progres_dokumentasi_id';

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'progres_id',
        'image_path',
        'location',
    ];

    protected $casts = [
        'location' => Point::class,
    ];

    protected $postgisColumns = [
        'location' => [
            'type' => 'geometry',
            'srid' => 4326,
        ],
    ];

    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    public function scopeForProgres($query, $progresId)
    {
        return $query->where('progres_id', $progresId);
    }

    protected static function booted()
    {
        static::deleting(function ($model) {
            if ($model->path && \Storage::disk('public')->exists($model->path)) {
                \Storage::disk('public')->delete($model->path);
            }
        });
    }

    public function progres()
    {
        return $this->belongsTo(ProgresReklamasi::class, 'progres_id', 'progres_id');
    }
}
