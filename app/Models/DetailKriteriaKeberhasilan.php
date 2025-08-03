<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailKriteriaKeberhasilan extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detail_kriteria_keberhasilan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'detail_kriteria_keberhasilan_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kriteria_keberhasilan_id',
        'kategori',
        'indikator',
        'rencana',
        'realisasi',
        'standar_keberhasilan',
        'hasil_evaluasi',
        'satuan',
    ];

    public function getRouteKeyName()
    {
        return 'detail_kriteria_keberhasilan_id';
    }

    /**
     * Get the kriteria keberhasilan that owns the DetailKriteriaKeberhasilan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kriteriaKeberhasilan(): BelongsTo
    {
        return $this->belongsTo(KriteriaKeberhasilan::class, 'kriteria_keberhasilan_id', 'kriteria_keberhasilan_id');
    }
}
