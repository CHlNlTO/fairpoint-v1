<?php
// app/Models/BusinessRegistrationProgress.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessRegistrationProgress extends Model
{
    use HasFactory;

    protected $table = 'business_registration_progress';

    protected $fillable = [
        'business_id',
        'step_code',
        'data',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::updating(function ($progress) {
            if ($progress->is_completed && !$progress->completed_at) {
                $progress->completed_at = now();
            }
        });
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public static function getSteps()
    {
        return [
            'basic_info' => [
                'title' => 'Basic Business Information',
                'description' => 'Business name, type, and structure',
                'order' => 1,
            ],
            'address' => [
                'title' => 'Business Address',
                'description' => 'Complete business address details',
                'order' => 2,
            ],
            'tax_info' => [
                'title' => 'Tax Information',
                'description' => 'Income tax and business tax setup',
                'order' => 3,
            ],
            'registrations' => [
                'title' => 'Government Registrations',
                'description' => 'BIR, DTI, SEC, LGU, CDA registrations',
                'order' => 4,
            ],
            'fiscal_year' => [
                'title' => 'Fiscal Year Setup',
                'description' => 'Define fiscal year period',
                'order' => 5,
            ],
            'chart_of_accounts' => [
                'title' => 'Chart of Accounts',
                'description' => 'Setup or import chart of accounts',
                'order' => 6,
            ],
        ];
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeIncomplete($query)
    {
        return $query->where('is_completed', false);
    }

    public function markAsCompleted($data = null)
    {
        if ($data) {
            $this->data = array_merge($this->data ?? [], $data);
        }

        $this->is_completed = true;
        $this->completed_at = now();
        $this->save();
    }

    public function markAsIncomplete()
    {
        $this->is_completed = false;
        $this->completed_at = null;
        $this->save();
    }
}
