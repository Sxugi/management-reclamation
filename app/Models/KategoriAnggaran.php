<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriAnggaran extends Model
{
    use HasFactory;

    protected $table = 'kategori_anggaran';

    protected $primaryKey = 'kategori_anggaran_id';

    protected $fillable = [
        'nama_kategori',
    ];

    public function anggaran()
    {
        return $this->hasMany(AnggaranReklamasi::class, 'kategori_anggaran_id', 'kategori_anggaran_id');
    }
}
