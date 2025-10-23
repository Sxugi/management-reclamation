<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgresSnapshot extends Model
{
    /*
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'progres_snapshots';

    /*
    * The primary key associated with the table.
    *
    * @var string
    */
    protected $primaryKey = 'progres_snapshot_id';

    /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'plot_id',
        'date',
        'percent',
    ];

    public function plot()
    {
        return $this->belongsTo(Plot::class, 'plot_id');
    }
}
