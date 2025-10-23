<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlotProgres extends Model
{
    /*
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'plot_progres';

    /*
    * The primary key associated with the table.
    *
    * @var string
    */
    protected $primaryKey = 'plot_progres_id';

    /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'plot_id',
        'percent',
    ];

    public function plot()
    {
        return $this->belongsTo(Plot::class, 'plot_id');
    }
}
