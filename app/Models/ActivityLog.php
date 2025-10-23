<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_logs';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'log_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plot_id',
        'action',
        'table_name',
        'record_id',
        'description',
    ];

    public $timestamps = false;
    
    protected $dates = ['created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function scopeForAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeForTable($query, $tableName)
    {
        return $query->where('table_name', $tableName);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public static function createLog(
        int $plotId, 
        string $action, 
        string $tableName, 
        ?int $recordId = null, 
        ?string $description = null
    ): self {
        return self::create([
            'plot_id' => $plotId,
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'description' => $description,
            'created_at' => now(),
        ]);
    }

    public function plot()
    {
        return $this->belongsTo(Plot::class, 'plot_id', 'plot_id');
    }
}
