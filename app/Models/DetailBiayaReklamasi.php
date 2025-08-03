<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailBiayaReklamasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detail_biaya_reklamasi';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'detail_biaya_reklamasi_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'biaya_reklamasi_id',
        'kategori',
        'kegiatan',
        'biaya',
    ];

    public function getRouteKeyName()
    {
        return 'detail_biaya_reklamasi_id';
    }

    public function biayaReklamasi(): BelongsTo
    {
        return $this->belongsTo(BiayaReklamasi::class, 'biaya_reklamasi_id', 'biaya_reklamasi_id');
    }
}
