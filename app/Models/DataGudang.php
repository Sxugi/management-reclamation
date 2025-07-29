<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataGudang extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_gudang';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'data_gudang_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lahan_id',
        'tanggal_masuk',
        'jenis_barang',
        'nama_barang',
        'jumlah_barang',
        'lokasi_penyimpanan',
        'status_barang',
        'catatan',
    ];

    public function getRouteKeyName()
    {
        return 'data_gudang_id';
    }

    /**
     * Get the lahan that owns the DataGudang.
     */
    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class, 'lahan_id', 'lahan_id');
    }
}
