<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'avatar_url',
        'role',
        'bio',
        'password',
        'mfa_enabled',
        'mfa_secret',
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
            'mfa_enabled' => 'boolean',
        ];
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    /**
     * Check if user has MFA enabled
     */
    public function hasMfaEnabled(): bool
    {
        return $this->mfa_enabled === true && !empty($this->mfa_secret);
    }

    /**
     * Get the site analytics records for the user.
     */
    public function siteAnalytics()
    {
        return $this->hasMany(SiteAnalytics::class);
    }

    /**
     * Get the latest site analytics record for the user.
     */
    public function latestSiteAnalytics()
    {
        return $this->hasOne(SiteAnalytics::class)->latestOfMany('created_at');
    }
}
