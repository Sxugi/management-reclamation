<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataPohonManual extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_pohon_manual';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'data_pohon_manual_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pohon_id',
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
            if ($model->jumlah_batang <= 0) {
                throw new \InvalidArgumentException('Jumlah batang harus lebih dari 0!');
            }
        });
    }

    /**
     * Get the route key name for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'data_pohon_manual_id';
    }

    // Relations
    public function pohon()
    {
        return $this->belongsTo(Pohon::class, 'pohon_id');
    }

    // Helpers
    public function getTipeAttribute(): string
    {
        return 'manual';
    }

    public function getSourceLabel(): string
    {
        return 'Input Manual';
    }

    public function canBeDeleted(): bool
    {
        return true;
    }
}