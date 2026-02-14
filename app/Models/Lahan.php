<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Clickbar\Magellan\Data\Geometries\Point;

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
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lahan';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_lahan',
        'luas_lahan',
        'luas_lahan_original',
        'tahun_awal',
        'tahun_akhir',
        'pic_id',
        'location',
        'fase',
    ];

    protected $casts = [
        'location' => Point::class,
        'luas_lahan' => 'decimal:2',
        'luas_lahan_original' => 'decimal:2',
        'tahun_awal' => 'integer',
        'tahun_akhir' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const FASE = [
        'BARU' => 'Lahan Baru',
        'PERAWATAN' => 'Perawatan',
        'REHAB' => 'Rehabilitasi',
        'PENGAYAAN' => 'Pengayaan',
        'SELESAI' => 'Selesai', 
    ];

    /**
     * Get all users assigned to this lahan (many-to-many)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'lahan_user',       // Pivot table
            'lahan_id',          // Foreign key
            'user_id'            // Related key
        )
        ->withPivot('role')      // Include role
        ->withTimestamps()       // Include timestamps
        ->orderBy('lahan_user.role');       // Order:  owner, editor, viewer
    }

    /**
     * Get the owner(s) of this lahan
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function owners()
    {
        return $this->users()->wherePivot('role', 'owner');
    }

    /**
     * Get the first owner (primary owner)
     * 
     * @return \App\Models\User|null
     */
    public function primaryOwner()
    {
        return $this->owners()->first();
    }

    /**
     * Get editors of this lahan
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function editors()
    {
        return $this->users()->wherePivot('role', 'editor');
    }

    /**
     * Get viewers of this lahan
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function viewers()
    {
        return $this->users()->wherePivot('role', 'viewer');
    }

    /**
     * Get users who can edit (owners + editors)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function editableByUsers()
    {
        return $this->users()->wherePivotIn('role', ['owner', 'editor']);
    }

    /** 
     * Get PIC (person in charge) user
     */
    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_id', 'user_id');
    }

    /**
     * Get plots for this lahan
     */
    public function plots(): HasMany
    {
        return $this->hasMany(Plot::class, 'lahan_id', 'lahan_id');
    }

    /**
     * Get anggaran reklamasi
     */
    public function anggaran(): HasMany
    {
        return $this->hasMany(AnggaranReklamasi:: class, 'lahan_id', 'lahan_id');
    }

    /**
     * Get data reklamasi
     */
    public function dataReklamasi(): HasMany
    {
        return $this->hasMany(DataReklamasi::class, 'lahan_id', 'lahan_id');
    }

    /**
     * Get biaya reklamasi
     */
    public function biayaReklamasi(): HasMany
    {
        return $this->hasMany(BiayaReklamasi::class, 'lahan_id', 'lahan_id');
    }

    /**
     * Get kriteria keberhasilan
     */
    public function kriteriaKeberhasilan(): HasOne
    {
        return $this->hasOne(KriteriaKeberhasilan::class, 'lahan_id', 'lahan_id');
    }

    /**
     * Get dokumentasi
     */
    public function dokumentasi(): HasMany
    {
        return $this->hasMany(Dokumentasi::class, 'lahan_id', 'lahan_id');
    }

    /**
     * Get reklamasi files
     */
    public function reklamasiFile(): HasMany
    {
        return $this->hasMany(ReklamasiFile::class, 'lahan_id', 'lahan_id');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'lahan_id';
    }

    /**
     * Check if user has access to this lahan
     * 
     * @param \App\Models\User|int $user
     * @return bool
     */
    public function isAccessibleBy($user): bool
    {
        $userId = $user instanceof User ? $user->user_id : $user;
        
        // Check if user is admin
        if ($user instanceof User && $user->isAdmin()) {
            return true;
        }
        
        // Check if user is assigned
        return $this->users()->where('lahan_user.user_id', $userId)->exists();
    }

    /**
     * Check if user is owner of this lahan
     * 
     * @param \App\Models\User|int $user
     * @return bool
     */
    public function isOwnedBy($user): bool
    {
        $userId = $user instanceof User ? $user->user_id : $user;
        
        return $this->owners()->where('lahan_user.user_id', $userId)->exists();
    }

    /**
     * Check if user can edit this lahan
     * 
     * @param \App\Models\User|int $user
     * @return bool
     */
    public function isEditableBy($user): bool
    {
        // Admin can edit all
        if ($user instanceof User && $user->isAdmin()) {
            return true;
        }
        
        $userId = $user instanceof User ? $user->user_id : $user;
        
        return $this->editableByUsers()->where('lahan_user.user_id', $userId)->exists();
    }

    /**
     * Get role of specific user on this lahan
     * 
     * @param \App\Models\User|int $user
     * @return string|null
     */
    public function getUserRole($user): ?string
    {
        $userId = $user instanceof User ? $user->user_id : $user;
        
        $userRecord = $this->users()->where('lahan_user.user_id', $userId)->first();
        
        return $userRecord ? $userRecord->pivot->role : null;
    }

    /**
     * Assign user to this lahan with specific role
     * 
     * @param \App\Models\User|int $user
     * @param string $role  'owner', 'editor', or 'viewer'
     * @return void
     */
    public function assignUser($user, string $role = 'owner'): void
    {
        $userId = $user instanceof User ? $user->user_id : $user;
        
        // Use sync to prevent duplicates (will update if exists)
        $this->users()->syncWithoutDetaching([
            $userId => ['role' => $role, 'updated_at' => now()]
        ]);
    }
 
    /**
     * Remove user from this lahan
     * 
     * @param \App\Models\User|int $user
     * @return void
     */
    public function removeUser($user): void
    {
        $userId = $user instanceof User ? $user->user_id : $user;
        
        $this->users()->detach($userId);
    }

    /**
     * Update user's role on this lahan
     * 
     * @param \App\Models\User|int $user
     * @param string $newRole
     * @return void
     */
    public function updateUserRole($user, string $newRole): void
    {
        $userId = $user instanceof User ? $user->user_id : $user;
        
        $this->users()->updateExistingPivot($userId, [
            'role' => $newRole,
            'updated_at' => now()
        ]);
    }

    /**
     * Scope:  Get lahan accessible by specific user
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        // Admin sees all
        if ($user->isAdmin()) {
            return $query;
        }
        
        // Filter by assigned lahan only
        return $query->whereHas('users', function ($q) use ($user) {
            $q->where('lahan_user.user_id', $user->user_id);
        });
    }

    /**
     * Scope: Get lahan where user is owner
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User|int $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwnedBy(Builder $query, $user): Builder
    {
        $userId = $user instanceof User ? $user->user_id : $user;
        
        return $query->whereHas('users', function ($q) use ($userId) {
            $q->where('lahan_user.user_id', $userId)
              ->where('lahan_user.role', 'owner');
        });
    }

    /**
     * Scope: Get lahan editable by user
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User|int $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEditableBy(Builder $query, $user): Builder
    {
        $userId = $user instanceof User ? $user->user_id : $user;
        
        return $query->whereHas('users', function ($q) use ($userId) {
            $q->where('lahan_user.user_id', $userId)
              ->whereIn('lahan_user.role', ['owner', 'editor']);
        });
    }

    /**
     * Scope: Get lahan by fase
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $fase
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByFase(Builder $query, string $fase): Builder
    {
        return $query->where('fase', $fase);
    }

    /** 
     * Scope: Get Active Lahan
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('fase', '!=', self::FASE['SELESAI']);
    }

    /**
     * Scope: Get active lahan
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeArsip(Builder $query): Builder
    {
        return $query->where('fase', self::FASE['SELESAI']);
    }

    /**
     * Get total luas area dari semua plot
     * 
     * @return float
     */
    public function getTotalLuasPlotAttribute(): float
    {
        return (float) $this->plots()->sum('luas_area');
    }

    /**
     * Get sisa luas lahan yang tersedia
     * 
     * @return float
     */
    public function getSisaLuasAttribute(): float
    {
        $totalLuasPlot = $this->plots()->sum('luas_area');
        return max(0, $this->luas_lahan - $totalLuasPlot);
    }

    /**
     * Get persentase penggunaan lahan berdasarkan original
     * 
     * @return float
     */
    public function getPersentasePenggunaanAttribute(): float
    {
        $baseline = $this->luas_lahan_original ?? $this->luas_lahan;
        
        if ($baseline <= 0) {
            return 0;
        }

        $totalLuasPlot = $this->plots()->sum('luas_area');
        return round(($totalLuasPlot / $baseline) * 100, 2);
    }

    /**
     * Check if luas lahan has been expanded from original
     * 
     * @return bool
     */
    public function isLuasExpanded(): bool
    {
        return $this->luas_lahan > ($this->luas_lahan_original ?? $this->luas_lahan);
    }

    /**
     * Get selisih antara current luas dengan original
     * 
     * @return float
     */
    public function getSelisihLuasAttribute(): float
    {
        return $this->luas_lahan - ($this->luas_lahan_original ?? $this->luas_lahan);
    }

    /**
     * Get status luas lahan
     * 
     * @return string 'normal'|'expanded'|'available'
     */
    public function getStatusLuasAttribute(): string
    {
        $total = $this->total_luas_plot;
        $original = $this->luas_lahan_original ?? $this->luas_lahan;

        if ($total > $original) {
            return 'expanded';
        }

        if ($total < $original) {
            return 'available';
        }

        return 'normal';
    }
}
