<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->belongsTo(Lahan::class, 'lahan_id', 'lahan_id');
    }

    /**
     * Get all detail kriteria keberhasilan for this kriteria.
     */
    public function detailKriteriaKeberhasilan(): HasMany
    {
        return $this->hasMany(DetailKriteriaKeberhasilan::class, 'kriteria_keberhasilan_id', 'kriteria_keberhasilan_id');
    }
}
