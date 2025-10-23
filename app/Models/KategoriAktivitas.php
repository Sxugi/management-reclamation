<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriAktivitas extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kategori_aktivitas';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'kategori_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'field',
        'label',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('label');
    }

    public function scopeByField($query, $field)
    {
        return $query->where('field', $field);
    }

    public function jenisAktivitas()
    {
        return $this->hasMany(JenisAktivitas::class, 'kategori_id', 'kategori_id');
    }

    public function getConfigAttribute()
    {
        return config('indicators.categories.' . $this->field, []);
    }
}
