<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Clickbar\Magellan\Data\Geometries\Point;

class Lahan extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'lahan_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lahan';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'nama_lahan',
        'luas_lahan',   
        'tahun_awal',
        'tahun_akhir',
        'pic_reklamasi',
        'location',
        'status',
    ];

    protected $casts = [
        'location' => Point::class,
    ];

    public function getRouteKeyName()
    {
        return 'lahan_id';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plot(): HasMany
    {
        return $this->hasMany(Plot::class);
    }

    public function dataReklamasi(): HasMany
    {
        return $this->hasMany(DataReklamasi::class);
    }

    public function biayaReklamasi(): HasMany
    {
        return $this->hasMany(BiayaReklamasi::class);
    }

    public function kriteriaKeberhasilan(): HasOne
    {
        return $this->hasOne(KriteriaKeberhasilan::class);
    }

    public function dokumentasi(): HasMany
    {
        return $this->hasMany(Dokumentasi::class, 'lahan_id', 'lahan_id');
    }

    public function reklamasiFile(): HasMany
    {
        return $this->hasMany(ReklamasiFile::class, 'lahan_id', 'lahan_id');
    }
}
