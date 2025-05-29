<?php
// app/Models/BusinessUser.php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Permission\Models\Role;

class BusinessUser extends Pivot
{
    protected $table = 'business_users';

    public $incrementing = true;

    protected $fillable = [
        'business_id',
        'user_id',
        'status_id',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($businessUser) {
            if (!$businessUser->joined_at) {
                $businessUser->joined_at = now();
            }
            if (!$businessUser->status_id) {
                $businessUser->status_id = BusinessUserStatus::where('code', 'ACTIVE')->first()?->id;
            }
        });
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(BusinessUserStatus::class, 'status_id');
    }

    public function isActive()
    {
        return $this->status?->code === 'ACTIVE';
    }

    public function assignBusinessRole($roleName)
    {
        // Create a business-specific role name
        $businessRoleName = "business_{$this->business_id}_{$roleName}";

        // Check if role exists, if not create it
        $role = Role::firstOrCreate(
            ['name' => $businessRoleName, 'guard_name' => 'web'],
            ['team_id' => $this->business_id] // If using teams feature in spatie/permission
        );

        // Assign role to user
        $this->user->assignRole($role);
    }

    public function removeBusinessRole($roleName)
    {
        $businessRoleName = "business_{$this->business_id}_{$roleName}";
        $this->user->removeRole($businessRoleName);
    }

    public function hasBusinessRole($roleName)
    {
        $businessRoleName = "business_{$this->business_id}_{$roleName}";
        return $this->user->hasRole($businessRoleName);
    }
}
