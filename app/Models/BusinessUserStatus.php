<?php
// app/Models/BusinessUserStatus.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessUserStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'color_id',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function color(): BelongsTo
    {
        return $this->belongsTo(FilamentColor::class, 'color_id');
    }

    public function businessUsers(): HasMany
    {
        return $this->hasMany(BusinessUser::class, 'status_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getColorClassAttribute()
    {
        return $this->color?->class_name ?? 'gray';
    }
}
