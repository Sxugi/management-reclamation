<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailReklamasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detail_reklamasi';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'detail_reklamasi_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'data_reklamasi_id',
        'kegiatan',
        'kategori',
        'satuan',
        'volume',
    ];

    public function dataReklamasi(): BelongsTo
    {
        return $this->belongsTo(DataReklamasi::class, 'data_reklamasi_id', 'data_reklamasi_id');
    }
}
