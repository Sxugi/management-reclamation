<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FieldDefinition extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'field_definitions';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'field_definition_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jenis_aktivitas_id',
        'field_key',
        'field_label',
        'field_type',
        'satuan',
        'indicator_key',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'options' => 'array',
    ];

    public function scopeForActivity($query, $jenisAktivitasId)
    {
        return $query->where('jenis_aktivitas_id', $jenisAktivitasId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('field_type', $type);
    }

    public function scopeWithIndicator($query)
    {
        return $query->whereNotNull('indicator_key');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('field_label');
    }

    public function jenisAktivitas()
    {
        return $this->belongsTo(JenisAktivitas::class, 'jenis_aktivitas_id', 'jenis_aktivitas_id');
    }

    public function fieldValues()
    {
        return $this->hasMany(ProgresFieldValue::class, 'field_definition_id', 'field_definition_id');
    }

    public function getConfigAttribute()
    {
        $jenisAktivitas = $this->jenisAktivitas;
        $kategori = $jenisAktivitas->kategoriAktivitas;
        
        return config('indicators.categories.' . $kategori->field . '.activities.' . $jenisAktivitas->field . '.fields.' . $this->field_key, []);
    }

    public function getOptionsAttribute()
    {
        $config = $this->getConfigAttribute();
        return $config['options'] ?? [];
    }

    public function getIsRequiredAttribute()
    {
        $config = $this->getConfigAttribute();
        return $config['required'] ?? false;
    }
}
