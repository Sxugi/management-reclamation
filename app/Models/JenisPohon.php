<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPohon extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jenis_pohon';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'jenis_pohon_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_pohon',
        'kategori',
    ];

    /**
     * Kategori constants
     */
    public const KATEGORI = [
        'PIONIR'      => 'Tanaman Pionir (Cepat Tumbuh)',
        'LOKAL'       => 'Tanaman Lokal (Native Species)',
        'MPTS'        => 'MPTS (Multi Purpose Tree Species)',
        'COVER_CROP'  => 'Cover Crops (Penutup Tanah)',
    ];

    /**
     * Get the pohon for the jenis pohon.
     */
    public function pohon()
    {
        return $this->hasMany(Pohon::class, 'jenis_pohon_id', 'jenis_pohon_id');
    }

    /**
     * Get the data pohon realisasi through pohon.
     */
    public function dataRealisasi()
    {
        return $this->hasManyThrough(
            DataPohonRealisasi::class,
            Pohon::class,
            'jenis_pohon_id',
            'pohon_id',
            'jenis_pohon_id',
            'pohon_id' 
        );
    }

    /**
     * Get the data pohon manual through pohon.
     */
    public function dataManual()
    {
        return $this->hasManyThrough(
            DataPohonManual::class,
            Pohon::class,
            'jenis_pohon_id',
            'pohon_id',
            'jenis_pohon_id',
            'pohon_id' 
        );
    }

    /**
     * Get total realisasi batang
     */
    public function getTotalRealisasiAttribute(): int
    {
        return $this->dataRealisasi()->sum('jumlah_batang');
    }

    /**
     * Get total manual batang
     */
    public function getTotalManualAttribute(): int
    {
        return $this->dataManual()->sum('jumlah_batang');
    }

    /**
     * Get grand total (realisasi + manual)
     */
    public function getGrandTotalAttribute(): int
    {
        return $this->total_realisasi + $this->total_manual;
    }

    /**
     * Get kategori label
     */
    public function getKategoriLabelAttribute(): string
    {
        return self::KATEGORI[$this->kategori] ?? $this->kategori;
    }

    /**
     * Scope: With totals (for better query performance)
     */
    public function scopeWithTotals($query)
    {
        return $query->withCount('pohon')
                     ->withSum('dataRealisasi as total_realisasi', 'jumlah_batang')
                     ->withSum('dataManual as total_manual', 'jumlah_batang');
    }

    /**
     * Scope: By kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope: Search by name
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_pohon', 'ILIKE', "%{$search}%");
    }
}
