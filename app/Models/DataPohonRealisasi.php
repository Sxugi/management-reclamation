<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataPohonRealisasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_pohon_realisasi';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'data_pohon_realisasi_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pohon_id',
        'plot_id',
        'tahun',
        'jumlah_batang',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tahun' => 'integer',
        'jumlah_batang' => 'integer',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::saving(function ($model) {
            if (!$model->plot_id) {
                throw new \InvalidArgumentException('Data realisasi harus memiliki plot_id!');
            }
            if ($model->jumlah_batang < 0) {
                throw new \InvalidArgumentException('Jumlah batang tidak boleh negatif!');
            }
        });
    }

    /**
     * Get the route key name for Laravel route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'data_pohon_realisasi_id';
    }

    /**
     * Relationships
     */
    public function pohon()
    {
        return $this->belongsTo(Pohon::class, 'pohon_id');
    }

    public function plot()
    {
        return $this->belongsTo(Plot::class, 'plot_id');
    }

    // Helpers
    public function getTipeAttribute(): string
    {
        return 'realisasi';
    }

    public function getSourceLabel(): string
    {
        return $this->plot ? $this->plot->nama_plot : '-';
    }

    public function canBeDeleted(): bool
    {
        return false;
    }
}