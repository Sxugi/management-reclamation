<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetProgresReklamasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'target';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'target_id';

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plot_id',
        'indikator_id',
        'value',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'float',
    ];

    public function scopeForPlot($query, $plotId)
    {
        return $query->where('plot_id', $plotId);
    }

    public function plot()
    {
        return $this->belongsTo(Plot::class, 'plot_id', 'plot_id');
    }

    public function indikator()
    {
        return $this->belongsTo(IndikatorProgresReklamasi::class, 'indikator_id', 'indikator_id');
    }
}
