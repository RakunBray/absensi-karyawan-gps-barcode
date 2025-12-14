<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasUlids;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nip',
        'name',
        'email',
        'password',
        'raw_password',
        'group',
        'phone',
        'gender',
        'birth_date',
        'birth_place',
        'address',
        'city',
        'education_id',
        'division_id',
        'job_title_id',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'raw_password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date'        => 'date:Y-m-d',
            'password'          => 'hashed',
        ];
    }

    // Daftar grup yang valid
    public static $groups = ['user', 'admin', 'superadmin'];

    // ===================================================================
    // ROLE ACCESSORS & METHODS — SUDAH DIPERBAIKI 100% & AMAN DIGUNAKAN!
    // ===================================================================

    // Accessor Laravel (bisa dipanggil $user->is_admin)
    public function getIsAdminAttribute(): bool
    {
        return in_array($this->group, ['admin', 'superadmin']);
    }

    public function getIsSuperadminAttribute(): bool
    {
        return $this->group === 'superadmin';
    }

    public function getIsUserAttribute(): bool
    {
        return $this->group === 'user';
    }

    public function getIsNotAdminAttribute(): bool
    {
        return !$this->isAdmin();
    }

    // Method biasa — DIPAKAI DI LoginResponse & Middleware
    public function isAdmin(): bool
    {
        return in_array($this->group, ['admin', 'superadmin']);
    }

    public function isSuperadmin(): bool
    {
        return $this->group === 'superadmin';
    }

    public function isUser(): bool
    {
        return $this->group === 'user';
    }

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function education()
    {
        return $this->belongsTo(Education::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}