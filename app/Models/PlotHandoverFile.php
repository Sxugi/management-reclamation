<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlotHandoverFile extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plot_handover_files';

    /**
     * The primary key associated with the table.
     * 
     * @var string
     */
    protected $primaryKey = 'plot_handover_file_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plot_handover_id',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
    ];

    /**
     * Get the handover that owns the file.
     */
    public function handover(): BelongsTo
    {
        return $this->belongsTo(PlotHandover::class, 'plot_handover_id');
    }

    /**
     * Get the formatted file size.
     *
     * @return string
     */
    public function getFormattedSizeAttribute(): string
    {
        $size = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        
        return round($size, 2) . ' ' . $units[$i];
    }
}