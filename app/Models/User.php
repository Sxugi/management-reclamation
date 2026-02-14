<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all lahan assigned to this user (many-to-many)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lahans()
    {
        return $this->belongsToMany(
            Lahan::class,
            'lahan_user',      // Pivot table name
            'user_id',          // Foreign key on pivot table
            'lahan_id'          // Related key on pivot table
        )
        ->withPivot('role')     // Include role column from pivot
        ->withTimestamps()      // Include created_at & updated_at from pivot
        ->orderBy('lahan_user.created_at', 'desc');
    }

    /**
     * Get lahan where user is the owner
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ownedLahans()
    {
        return $this->lahans()->wherePivot('role', 'owner');
    }

    /**
     * Get lahan where user can edit (owner or editor)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function editableLahans()
    {
        return $this->lahans()->wherePivotIn('role', ['owner', 'editor']);
    }

    /**
     * Get lahan where user is viewer only
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function viewableLahans()
    {
        return $this->lahans()->wherePivot('role', 'viewer');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user has access to specific lahan
     * 
     * @param \App\Models\Lahan|int $lahan
     * @return bool
     */
    public function hasAccessToLahan($lahan): bool
    {
        $lahanId = $lahan instanceof Lahan ? $lahan->lahan_id : $lahan;
        
        // Admin has access to all
        if ($this->isAdmin()) {
            return true;
        }
        
        // Check if user is assigned to this lahan
        return $this->lahans()->where('lahan_user.lahan_id', $lahanId)->exists();
    }

    /**
     * Get role of user on specific lahan
     * 
     * @param \App\Models\Lahan|int $lahan
     * @return string|null
     */
    public function getRoleOnLahan($lahan): ?string
    {
        $lahanId = $lahan instanceof Lahan ? $lahan->lahan_id : $lahan;

        $pivot = $this->lahans()
            ->where('lahan_user.lahan_id', $lahanId)
            ->first();
        
        return $pivot ? $pivot->pivot->role : null;
    }

    /**
     * Check if user is owner of specific lahan
     * 
     * @param \App\Models\Lahan|int $lahan
     * @return bool
     */
    public function isOwnerOfLahan($lahan): bool
    {
        return $this->getRoleOnLahan($lahan) === 'owner';
    }

    /**
     * Check if user can edit specific lahan
     * 
     * @param \App\Models\Lahan|int $lahan
     * @return bool
     */
    public function canEditLahan($lahan): bool
    {
        // Admin can edit all
        if ($this->isAdmin()) {
            return true;
        }
        
        $role = $this->getRoleOnLahan($lahan);
        return in_array($role, ['owner', 'editor']);
    }

    /**
     * Check if user can delete specific lahan
     * 
     * @param \App\Models\Lahan|int $lahan
     * @return bool
     */
    public function canDeleteLahan($lahan): bool
    {
        // Admin can delete all
        if ($this->isAdmin()) {
            return true;
        }
        
        // Only owner can delete
        return $this->isOwnerOfLahan($lahan);
    }

    /**
     * Scope untuk filter active users
     */
    public function scopeActive($query)
    {
        return $query->whereRaw('LOWER(status) = ?', ['active']);
    }

    /**
     * Scope untuk filter inactive users
     */
    public function scopeInactive($query)
    {
        return $query->whereRaw('LOWER(status) = ?', ['inactive']);
    }

    /**
     * Scope untuk filter suspended users
     */
    public function scopeSuspended($query)
    {
        return $query->whereRaw('LOWER(status) = ?', ['suspended']);
    }

    /**
     * Scope untuk filter admins
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope untuk filter regular users
     */
    public function scopeUsers($query)
    {
        return $query->where('role', 'user');
    }
}
