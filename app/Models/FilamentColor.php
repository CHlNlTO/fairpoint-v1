<?php
// app/Models/FilamentColor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FilamentColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'class_name',
        'hex_value',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function businessStatuses(): HasMany
    {
        return $this->hasMany(BusinessStatus::class, 'color_id');
    }

    public function businessUserStatuses(): HasMany
    {
        return $this->hasMany(BusinessUserStatus::class, 'color_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
