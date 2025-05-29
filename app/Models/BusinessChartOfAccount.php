<?php
// app/Models/BusinessChartOfAccount.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessChartOfAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'code',
        'name',
        'account_type_id',
        'sub_account_type_id',
        'description',
        'amount',
        'is_from_template',
        'template_item_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_from_template' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    public function subAccountType(): BelongsTo
    {
        return $this->belongsTo(SubAccountType::class);
    }

    public function templateItem(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccountsTemplateItem::class, 'template_item_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('code');
    }

    public function scopeByType($query, $typeCode)
    {
        return $query->whereHas('accountType', function ($q) use ($typeCode) {
            $q->where('code', $typeCode);
        });
    }

    public function getFullNameAttribute()
    {
        return "{$this->code} - {$this->name}";
    }

    public function isDebit()
    {
        $debitTypes = ['ASSET', 'EXPENSE', 'COGS'];
        return in_array($this->accountType?->code, $debitTypes);
    }

    public function isCredit()
    {
        $creditTypes = ['LIABILITY', 'EQUITY', 'REVENUE'];
        return in_array($this->accountType?->code, $creditTypes);
    }
}
