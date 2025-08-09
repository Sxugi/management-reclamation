<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pohon extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pohon';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'pohon_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lahan_id',
        'jenis_pohon',
    ];

    public function getRouteKeyName(): string
    {
        return 'pohon_id';
    }

    /**
     * Get the lahan that owns the pohon.
     */
    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class, 'lahan_id', 'lahan_id');
    }

    /**
     * Get the data pohon associated with the pohon.
     */
    public function dataPohon(): HasMany
    {
        return $this->hasMany(DataPohon::class, 'pohon_id', 'pohon_id');
    }
}
