<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Invoice;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'user_status', // Role (Admin, User, Guest)
        'is_verified', // Email verification status
        'status', // Account status (active, inactive)
        'added_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'user_status' => 'integer',
    ];

    /**
     * Relationship with Invoice model.
     * A user has one invoice.
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Get the full name of the user.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the user role in readable format.
     */
    public function getRoleAttribute()
    {
        return match ($this->user_status) {
            1 => 'Admin',
            2 => 'User',
            3 => 'Guest',
            default => 'Unknown',
        };
    }

    /**
     * Scope to filter verified users.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', 1);
    }

    /**
     * Scope to filter unverified users.
     */
    public function scopeUnverified($query)
    {
        return $query->where('is_verified', 0);

    }


    public function roles()
    {
        return $this->belongsToMany(\App\Models\Role::class, 'role_user', 'user_id', 'role_id')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    public function activeRoleId()
    {
        return $this->roles()->wherePivot('is_active', true)->value('role_id');
    }

   public function activeRole()
    {
        return $this->roles()->wherePivot('is_active', true)->first();
    }

    // Users that this user has added
    public function usersAdded()
    {
        return $this->belongsToMany(User::class, 'added_users', 'adder_user_id', 'added_user_id')->withTimestamps();
    }

    // Users who added this user
    public function addedByUsers()
    {
        return $this->belongsToMany(User::class, 'added_users', 'added_user_id', 'adder_user_id')->withTimestamps();
    }

    public function plan()
    {
        return $this->hasOne(UserPlan::class);
    }

    public function hasMultipleRoles()
    {
        return $this->roles()->count() > 1;
    }
}
