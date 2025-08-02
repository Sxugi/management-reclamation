<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataReklamasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_reklamasi';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'data_reklamasi_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lahan_id',
        'tahun',
        'tipe',
    ];

    public function getRouteKeyName()
    {
        return 'data_reklamasi_id';
    }

    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class, 'lahan_id', 'lahan_id');
    }

    public function detailReklamasi(): HasMany
    {
        return $this->hasMany(DetailReklamasi::class, 'data_reklamasi_id', 'data_reklamasi_id');
    }
}