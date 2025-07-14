<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'type',
        'area_penambangan',
        'timbuman_tanah_pengakaran',
        'timbuman_batuan_samping',
        'timbuman_komoditas_tambang',
        'timbuman_limbah_fasilitas',
        'jalan_tambang',
        'kolam_sedimen',
        'fasilitas_pengolahan',
        'kantor_perumahan',
        'bengkel',
        'fasilitas_penunjang',
        'lahan_selesai_ditambang',
        'lahan_aktif_ditambang',
        'volume_batuan_samping',
        'penimbunan_bekas_tambang',
        'penimbunan_diluar_bekas_tambang',
        'volume_bekas_tambang',
        'volume_diluar_bekas_tambang',
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
        'pemeliharaan_lubang'
    ];

    public function getRouteKeyName()
    {
        return 'data_reklamasi_id';
    }

    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class);
    }
}