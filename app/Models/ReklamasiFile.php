<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ReklamasiFile extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'reklamasi_file_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reklamasi_file';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lahan_id',
        'type',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'tahun'
    ];

    protected $casts = [
        'tahun' => 'integer',
        'file_size' => 'integer',
    ];

    public function scopeRencana($query)
    {
        return $query->where('type', 'rencana');
    }

    public function scopeLaporan($query)
    {
        return $query->where('type', 'laporan');
    }

    public function scopeLaporanByYear($query, $tahun)
    {
        return $query->where('type', 'laporan')
                     ->where('tahun', $tahun);
    }

    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    public function fileExists()
    {
        return Storage::exists($this->file_path);
    }

    public function getRouteKeyName()
    {
        return 'reklamasi_file_id';
    }

    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class, 'lahan_id', 'lahan_id');
    }

}
