<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lahan extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'lahan_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'nama_lahan',
        'luas_lahan',   
        'tahun_awal',
        'tahun_akhir',
        'pic_reklamasi',
        'status',
    ];

    // Set location (point) from latitude and longitude
    public function setLocation($longitude, $latitude)
    {
        DB::statement(
            "UPDATE lahans SET location = ST_SetSRID(ST_MakePoint(?, ?), 4326) WHERE lahan_id = ?",
            [$longitude, $latitude, $this->lahan_id]
        );
        return $this;
    }

    // Get latitude
    public function getLatitudeAttribute()
    {
        if (!$this->exists) return null;
        
        $point = DB::select(
            "SELECT ST_Y(location::geometry) as latitude FROM lahans WHERE lahan_id = ?", 
            [$this->lahan_id]
        );
        return $point[0]->latitude ?? null;
    }

    // Get longitude
    public function getLongitudeAttribute()
    {
        if (!$this->exists) return null;
        
        $point = DB::select(
            "SELECT ST_X(location::geometry) as longitude FROM lahans WHERE lahan_id = ?", 
            [$this->lahan_id]
        );
        return $point[0]->longitude ?? null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
