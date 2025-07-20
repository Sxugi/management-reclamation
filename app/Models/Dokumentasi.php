<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumentasi extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'dokumentasi_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dokumentasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lahan_id',
        'nama',
        'deskripsi',
        'image_path'
    ];

    public function getRouteKeyName()
    {
        return 'dokumentasi_id';
    }

    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class);
    }
}
