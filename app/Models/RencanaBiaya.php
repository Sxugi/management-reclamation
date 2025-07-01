<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RencanaBiaya extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'rencana_biaya_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lahan_id',
        'tahun',
        'biaya_langsung',
        'biaya_tidak_langsung',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'biaya_langsung' => 'array',
        'biaya_tidak_langsung' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'rencana_biaya_id';
    }

    /**
     * Get the RencanaBiaya by lahan_id and tahun.
     *
     * @param int $lahan_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function byLahanPerTahun($lahan_id)
    {
        return self::where('lahan_id', $lahan_id)->get()->keyBy('tahun');
    }

    /**
     * Get the lahan that owns the RencanaBiaya.
     */
    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class);
    }
}
