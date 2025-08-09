<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPohon extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_pohon';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'data_pohon_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pohon_id',
        'tahun',
        'jumlah',
    ];

    public function getRouteKeyName(): string
    {
        return 'data_pohon_id';
    }

    /**
     * Get the pohon that owns the data pohon.
     */
    public function pohon(): BelongsTo
    {
        return $this->belongsTo(Pohon::class, 'pohon_id', 'pohon_id');
    }
}
