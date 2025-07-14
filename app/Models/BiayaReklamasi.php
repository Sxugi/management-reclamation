<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'type',
        'penataan_tanah',
        'penebaran_tanah_pengakaran',
        'pengendalian_erosi',
        'kualitas_tanah',
        'pemupukan',
        'pengadaan_bibit',
        'penanaman',
        'pemeliharaan_tanaman',
        'pencegahan_air_asam',
        'pekerjaan_sipil',
        'stabilisasi_lereng',
        'pengamanan_lubang',
        'pemulihan_kualitas_air',
        'pemeliharaan_lubang',
        'subtotal_1',
        'rekapitulasi_biaya_id',
        'mobilisasi_demobilisasi_alat',
        'perencanaan_reklamasi',
        'administrasi_pihak_ketiga',
        'supervisi',
        'subtotal_2',
    ];

    public function getRouteKeyName()
    {
        return 'biaya_reklamasi_id';
    }

    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class);
    }
}
