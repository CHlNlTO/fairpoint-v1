<?php
// app/Models/RegistrationType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegistrationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'requires_expiry',
        'requires_document',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'requires_expiry' => 'boolean',
        'requires_document' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function businessRegistrations(): HasMany
    {
        return $this->hasMany(BusinessRegistration::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
