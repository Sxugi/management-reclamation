<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPohon extends Model
{
    use HasFactory;

    protected $table = 'jenis_pohon';

    protected $primaryKey = 'jenis_pohon_id';

    protected $fillable = [
        'nama_pohon',
    ];

    public function pohon()
    {
        return $this->hasMany(Pohon::class, 'jenis_pohon_id', 'jenis_pohon_id');
    }

    public function dataPohon()
    {
        return $this->hasManyThrough(
            DataPohon::class,
            Pohon::class,
            'jenis_pohon_id',
            'pohon_id',
            'jenis_pohon_id',
            'pohon_id' 
        );
    }
}
