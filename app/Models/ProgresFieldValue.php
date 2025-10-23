<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgresFieldValue extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'progres_field_values';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'progres_field_value_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'progres_id',
        'field_definition_id',
        'field_value',
    ];

    public function scopeForProgres($query, $progresId)
    {
        return $query->where('progres_id', $progresId);
    }

    public function scopeForField($query, $fieldDefinitionId)
    {
        return $query->where('field_definition_id', $fieldDefinitionId);
    }

    public function progres()
    {
        return $this->belongsTo(ProgresReklamasi::class, 'progres_id', 'progres_id');
    }

    public function fieldDefinition()
    {
        return $this->belongsTo(FieldDefinition::class, 'field_definition_id', 'field_definition_id');
    }

    public function getFormattedValueAttribute()
    {
        $field = $this->fieldDefinition;
        
        if ($field->field_type === 'number') {
            return number_format((float)$this->field_value, 2);
        }
        
        return $this->field_value;
    }

    public function getValueWithUnitAttribute()
    {
        $field = $this->fieldDefinition;
        $value = $this->getFormattedValueAttribute();
        
        return $field->satuan ? $value . ' ' . $field->satuan : $value;
    }
}
