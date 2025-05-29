<?php
// app/Models/BusinessRegistration.php

namespace App\Models;

use App\Traits\TracksHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class BusinessRegistration extends Model
{
    use HasFactory, TracksHistory;

    protected $fillable = [
        'business_id',
        'registration_type_id',
        'is_registered',
        'registration_number',
        'document_path',
        'registration_date',
        'expiry_date',
        'notes',
    ];

    protected $casts = [
        'is_registered' => 'boolean',
        'registration_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function registrationType(): BelongsTo
    {
        return $this->belongsTo(RegistrationType::class);
    }

    public function getDocumentUrlAttribute()
    {
        return $this->document_path
            ? Storage::url($this->document_path)
            : null;
    }

    public function isExpired()
    {
        if (!$this->expiry_date) {
            return false;
        }

        return $this->expiry_date->isPast();
    }

    public function isExpiringSoon($days = 30)
    {
        if (!$this->expiry_date) {
            return false;
        }

        return $this->expiry_date->isBetween(now(), now()->addDays($days));
    }

    public function scopeRegistered($query)
    {
        return $query->where('is_registered', true);
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', now());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }
}
