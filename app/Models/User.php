<?php
// app/Models/User.php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@gmail.com');
    }

    public function canAccessFilament(): bool
    {
        return true; // Or your specific logic for admin access
    }

    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'business_users')
            ->using(BusinessUser::class)
            ->withPivot(['status_id', 'joined_at'])
            ->withTimestamps();
    }

    public function businessUsers(): HasMany
    {
        return $this->hasMany(BusinessUser::class);
    }

    public function activeBusinesses(): BelongsToMany
    {
        return $this->businesses()
            ->wherePivotIn('status_id', function ($query) {
                $query->select('id')
                    ->from('business_user_statuses')
                    ->where('code', 'ACTIVE');
            });
    }

    public function createdBusinesses(): HasMany
    {
        return $this->hasMany(Business::class, 'created_by');
    }

    public function updatedBusinesses(): HasMany
    {
        return $this->hasMany(Business::class, 'updated_by');
    }

    public function businessHistories(): HasMany
    {
        return $this->hasMany(BusinessHistory::class, 'changed_by');
    }

    public function templateHistories(): HasMany
    {
        return $this->hasMany(ChartOfAccountsTemplateHistory::class, 'changed_by');
    }

    // Helper methods
    public function hasBusinessAccess(Business $business)
    {
        return $this->businessUsers()
            ->where('business_id', $business->id)
            ->whereHas('status', function ($query) {
                $query->where('code', 'ACTIVE');
            })
            ->exists();
    }

    public function getCurrentBusiness()
    {
        // This can be enhanced to use session or user preference
        return $this->activeBusinesses()->first();
    }

    public function switchBusiness(Business $business)
    {
        if ($this->hasBusinessAccess($business)) {
            session(['current_business_id' => $business->id]);
            return true;
        }

        return false;
    }
}
