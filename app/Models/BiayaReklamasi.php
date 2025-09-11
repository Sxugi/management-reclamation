<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BiayaReklamasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'biaya_reklamasi';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'biaya_reklamasi_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lahan_id',
        'tahun',
        'tipe',
        'currency',
        'subtotal_1',
        'subtotal_2',
    ];

    public function getRouteKeyName()
    {
        return 'biaya_reklamasi_id';
    }

    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class , 'lahan_id', 'lahan_id');
    }

    public function detailBiayaReklamasi(): HasMany
    {
        return $this->hasMany(DetailBiayaReklamasi::class, 'biaya_reklamasi_id', 'biaya_reklamasi_id');
    }
}
