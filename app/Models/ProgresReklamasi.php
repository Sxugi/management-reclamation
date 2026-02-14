<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgresReklamasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'progres';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'progres_id';

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plot_id',
        'indikator_id',
        'jenis_aktivitas_id',
        'tanggal',
        'value',
        'catatan',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'float',
        'tanggal' => 'date',
    ];

    public function getRouteKeyName()
    {
        return 'progres_id';
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    public function scopeForPlot($query, $plotId)
    {
        return $query->where('plot_id', $plotId);
    }

    public function scopeForActivity($query, $jenisAktivitasId)
    {
        return $query->where('jenis_aktivitas_id', $jenisAktivitasId);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc');
    }

    public function getFormattedDateAttribute()
    {
        return $this->tanggal->format('d F Y');
    }

    public function plot()
    {
        return $this->belongsTo(Plot::class, 'plot_id', 'plot_id');
    }

    public function indikator()
    {
        return $this->belongsTo(IndikatorProgresReklamasi::class, 'indikator_id', 'indikator_id')
                    ->withDefault();
    }

    public function jenisAktivitas()
    {
        return $this->belongsTo(JenisAktivitas::class, 'jenis_aktivitas_id', 'jenis_aktivitas_id');
    }

    public function fieldValues()
    {
        return $this->hasMany(ProgresFieldValue::class, 'progres_id', 'progres_id');
    }

    public function dokumentasi()
    {
        return $this->hasMany(ProgresDokumentasi::class, 'progres_id', 'progres_id');
    }

    public function getKategoriAttribute()
    {
        return $this->jenisAktivitas?->kategoriAktivitas;
    }

    /**
     * Get field values as associative array
     */
    public function getFieldValuesArrayAttribute()
    {
        $values = [];
        
        foreach ($this->fieldValues as $fieldValue) {
            if ($fieldValue->fieldDefinition) {
                $key = $fieldValue->fieldDefinition->field_key;
                $values[$key] = $fieldValue->field_value;
                
                // Special handling for 'jenis_pohon_id' to get the name
                if ($key === 'jenis_pohon_id' && is_numeric($fieldValue->field_value)) {
                    $jenisPohon = \App\Models\JenisPohon::find($fieldValue->field_value);
                    if ($jenisPohon) {
                        $values['jenis_pohon_nama'] = $jenisPohon->nama_lokal ?? $jenisPohon->nama_ilmiah;
                    }
                }
            }
        }
        
        return $values;
    }
}
