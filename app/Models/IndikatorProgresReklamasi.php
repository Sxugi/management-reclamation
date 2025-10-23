<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IndikatorProgresReklamasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'indikator';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'indikator_id';

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'label',
        'satuan',
        'is_active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->nama) && !empty($model->label)) {
                $base = Str::slug($model->label, '_');
                $slug = $base;
                $counter = 1;
                while (static::where('nama', $slug)->exists()) {
                    $slug = $base . '_' . $counter++;
                }
                $model->nama = $slug;
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('label');
    }

    public function getFormattedNamaAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->nama));
    }

    public function target()
    {
        return $this->hasMany(TargetProgresReklamasi::class, 'indikator_id', 'indikator_id');
    }

    public function progres()
    {
        return $this->hasMany(ProgresReklamasi::class, 'indikator_id', 'indikator_id');
    }
}

