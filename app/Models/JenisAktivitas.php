<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisAktivitas extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jenis_aktivitas';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'jenis_aktivitas_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kategori_id',
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

    public function scopeForKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    public function kategoriAktivitas()
    {
        return $this->belongsTo(KategoriAktivitas::class, 'kategori_id', 'kategori_id');
    }

    public function fieldDefinitions()
    {
        return $this->hasMany(FieldDefinition::class, 'jenis_aktivitas_id', 'jenis_aktivitas_id');
    }

    public function progres()
    {
        return $this->hasMany(ProgresReklamasi::class, 'jenis_aktivitas_id', 'jenis_aktivitas_id');
    }

    public function getConfigAttribute()
    {
        $kategori = $this->kategoriAktivitas;
        return config('indicators.categories.' . $kategori->field . '.activities.' . $this->field, []);
    }
}
