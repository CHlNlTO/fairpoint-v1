<?php
// app/Models/AccountType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function subAccountTypes(): HasMany
    {
        return $this->hasMany(SubAccountType::class);
    }

    public function chartOfAccountsTemplateItems(): HasMany
    {
        return $this->hasMany(ChartOfAccountsTemplateItem::class);
    }

    public function businessChartOfAccounts(): HasMany
    {
        return $this->hasMany(BusinessChartOfAccount::class);
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
