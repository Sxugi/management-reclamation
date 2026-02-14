<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlotHandover extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plot_handovers';

    /**
     * The primary key associated with the table.
     * 
     * @var string
     */
    protected $primaryKey = 'plot_handover_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plot_id',
        'luas',
        'lokasi',
        'tanggal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal' => 'date',
        'luas' => 'decimal:2',
    ];

    /**
     * Get the plot that owns the handover.
     */
    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class, 'plot_id', 'plot_id');
    }

    /**
     * Get the files associated with the handover.
     */
    public function files(): HasMany
    {
        return $this->hasMany(PlotHandoverFile::class, 'plot_handover_id', 'plot_handover_id');
    }

    /**
     * Get the surat files associated with the handover.
     */
    public function suratFiles(): HasMany
    {
        return $this->hasMany(PlotHandoverFile::class, 'plot_handover_id', 'plot_handover_id')->where('type', 'surat');
    }

    /**
     * Get the peta files associated with the handover.
     */
    public function petaFiles(): HasMany
    {
        return $this->hasMany(PlotHandoverFile::class, 'plot_handover_id', 'plot_handover_id')->where('type', 'peta');
    }
}
