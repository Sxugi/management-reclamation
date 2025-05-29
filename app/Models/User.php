<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string'; 
    protected $fillable = [
        'username',
        'email',
        'password',
        'email_verified_at',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            // Only generate if it's not manually set
            if (empty($user->user_id)) {
                $prefix = 'user_';

                // Get the latest ID and increment it
                $latest = DB::table('users')
                    ->where('user_id', 'like', $prefix . '%')
                    ->orderBy('user_id', 'desc')
                    ->value('user_id');

                $number = $latest ? ((int) substr($latest, strlen($prefix))) + 1 : 1;

                // Pad with leading zeros (e.g., 001, 002)
                $user->user_id = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
            }
        });
    }

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
}
