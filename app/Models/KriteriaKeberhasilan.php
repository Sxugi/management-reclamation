<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KriteriaKeberhasilan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kriteria_keberhasilan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'kriteria_keberhasilan_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lahan_id',
        'rencana_luas_ditata',
        'realisasi_luas_ditata',
        'evaluasi_luas_ditata',
        'rencana_stabilitas_ditata',
        'realisasi_stabilitas_ditata',
        'evaluasi_stabilitas_ditata',
        'rencana_luas_ditimbun',
        'realisasi_luas_ditimbun',
        'evaluasi_luas_ditimbun',
        'rencana_stabilitas_ditimbun',
        'realisasi_stabilitas_ditimbun',
        'evaluasi_stabilitas_ditimbun',
        'rencana_luas_ditebar',
        'realisasi_luas_ditebar',
        'evaluasi_luas_ditebar',
        'rencana_ph_tanah',
        'realisasi_ph_tanah',
        'evaluasi_ph_tanah',
        'rencana_saluran_drainase',
        'realisasi_saluran_drainase',
        'evaluasi_saluran_drainase',
        'rencana_pengendalian_erosi',
        'realisasi_pengendalian_erosi',
        'evaluasi_pengendalian_erosi',
        'rencana_luas_penanaman',
        'realisasi_luas_penanaman',
        'evaluasi_luas_penanaman',
        'rencana_pertumbuhan_tanaman',
        'realisasi_pertumbuhan_tanaman',
        'evaluasi_pertumbuhan_tanaman',
        'rencana_pengelolaan_material',
        'realisasi_pengelolaan_material',
        'evaluasi_pengelolaan_material',
        'rencana_bangunan_erosi',
        'realisasi_bangunan_erosi',
        'evaluasi_bangunan_erosi',
        'rencana_kolam_sedimen',
        'realisasi_kolam_sedimen',
        'evaluasi_kolam_sedimen',
        'rencana_penutupan_tajuk',
        'realisasi_penutupan_tajuk',
        'evaluasi_penutupan_tajuk',
        'rencana_pemupukan',
        'realisasi_pemupukan',
        'evaluasi_pemupukan',
        'rencana_pengendalian_hama',
        'realisasi_pengendalian_hama',
        'evaluasi_pengendalian_hama',
        'rencana_penyulaman',
        'realisasi_penyulaman',
        'evaluasi_penyulaman',
    ];

    public function getRouteKeyName()
    {
        return 'kriteria_keberhasilan_id';
    }

    /**
     * Get the lahan that owns the KriteriaKeberhasilan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class);
    }    
}
