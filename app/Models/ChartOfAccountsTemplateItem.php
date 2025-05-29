<?php
// app/Models/ChartOfAccountsTemplateItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccountsTemplateItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'code',
        'name',
        'account_type_id',
        'sub_account_type_id',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccountsTemplate::class, 'template_id');
    }

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    public function subAccountType(): BelongsTo
    {
        return $this->belongsTo(SubAccountType::class);
    }

    public function businessChartOfAccounts(): HasMany
    {
        return $this->hasMany(BusinessChartOfAccount::class, 'template_item_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('code');
    }
}
