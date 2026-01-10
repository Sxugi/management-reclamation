<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggaranReklamasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'anggaran_reklamasi';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'anggaran_reklamasi_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lahan_id',
        'jenis_anggaran',
        'kategori_anggaran_id',
        'tahun',
        'bulan',
        'nominal',
        'quarter',
        'quarter_label'
    ];

    public function getRouteKeyName(): string
    {
        return 'anggaran_reklamasi_id';
    }

    /**
     * Get the lahan that owns the AnggaranReklamasi.
     */
    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class, 'lahan_id', 'lahan_id');
    }

    /**
     * Get the kategori anggaran that owns the AnggaranReklamasi.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriAnggaran::class, 'kategori_anggaran_id', 'kategori_anggaran_id');
    }
}
