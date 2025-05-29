<?php
// app/Models/ChartOfAccountsTemplate.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccountsTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'industry_id',
        'business_structure_id',
        'version',
        'description',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function businessStructure(): BelongsTo
    {
        return $this->belongsTo(BusinessStructure::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ChartOfAccountsTemplateItem::class, 'template_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(ChartOfAccountsTemplateHistory::class, 'template_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeForIndustryAndStructure($query, $industryId, $structureId)
    {
        return $query->where('industry_id', $industryId)
            ->where('business_structure_id', $structureId);
    }

    public function createNewVersion($description = null, $userId = null)
    {
        // Save current version to history
        ChartOfAccountsTemplateHistory::create([
            'template_id' => $this->id,
            'version' => $this->version,
            'template_data' => [
                'template' => $this->toArray(),
                'items' => $this->items->toArray(),
            ],
            'change_description' => $description,
            'changed_by' => $userId ?? auth()->id(),
        ]);

        // Increment version
        $versionParts = explode('.', $this->version);
        $versionParts[count($versionParts) - 1]++;
        $this->version = implode('.', $versionParts);
        $this->save();

        return $this;
    }
}
