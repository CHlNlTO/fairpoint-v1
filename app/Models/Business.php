<?php
// app/Models/Business.php

namespace App\Models;

use App\Traits\HasPhilippineAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Yajra\Address\Entities\Barangay;
use Yajra\Address\Entities\City;
use Yajra\Address\Entities\Province;

class Business extends Model
{
    use HasFactory, HasPhilippineAddress;

    protected $fillable = [
        'name',
        'tin',
        'email',
        'address_sub_street',
        'address_street',
        'barangay_id',
        'city_id',
        'province_id',
        'zip_code',
        'business_type_id',
        'business_structure_id',
        'industry_id',
        'fiscal_year_start',
        'fiscal_year_end',
        'status_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fiscal_year_start' => 'date',
        'fiscal_year_end' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($business) {
            if (!$business->status_id) {
                $business->status_id = BusinessStatus::where('code', 'DRAFT')->first()?->id;
            }
            $business->created_by = auth()->id();
        });

        static::updating(function ($business) {
            $business->updated_by = auth()->id();
        });
    }

    // Relationships
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function businessType(): BelongsTo
    {
        return $this->belongsTo(BusinessType::class);
    }

    public function businessStructure(): BelongsTo
    {
        return $this->belongsTo(BusinessStructure::class);
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(BusinessStatus::class, 'status_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'business_users')
            ->using(BusinessUser::class)
            ->withPivot(['status_id', 'joined_at'])
            ->withTimestamps();
    }

    public function businessUsers(): HasMany
    {
        return $this->hasMany(BusinessUser::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(BusinessRegistration::class);
    }

    public function taxInformation(): HasOne
    {
        return $this->hasOne(BusinessTaxInformation::class);
    }

    public function chartOfAccounts(): HasMany
    {
        return $this->hasMany(BusinessChartOfAccount::class);
    }

    public function registrationProgress(): HasMany
    {
        return $this->hasMany(BusinessRegistrationProgress::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(BusinessHistory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('code', 'ACTIVE');
        });
    }

    public function scopeForUser($query, $userId = null)
    {
        $userId = $userId ?? auth()->id();

        return $query->whereHas('businessUsers', function ($q) use ($userId) {
            $q->where('user_id', $userId)
                ->whereHas('status', function ($sq) {
                    $sq->where('code', 'ACTIVE');
                });
        });
    }

    // Helper methods
    public function isComplete()
    {
        $requiredSteps = ['basic_info', 'tax_info', 'registrations', 'chart_of_accounts'];

        $completedSteps = $this->registrationProgress()
            ->whereIn('step_code', $requiredSteps)
            ->where('is_completed', true)
            ->count();

        return $completedSteps === count($requiredSteps);
    }

    public function copyChartOfAccountsFromTemplate($templateId = null)
    {
        if (!$templateId) {
            $template = ChartOfAccountsTemplate::forIndustryAndStructure(
                $this->industry_id,
                $this->business_structure_id
            )->default()->active()->first();
        } else {
            $template = ChartOfAccountsTemplate::find($templateId);
        }

        if (!$template) {
            return false;
        }

        $items = $template->items()->active()->ordered()->get();

        foreach ($items as $item) {
            $this->chartOfAccounts()->create([
                'code' => $item->code,
                'name' => $item->name,
                'account_type_id' => $item->account_type_id,
                'sub_account_type_id' => $item->sub_account_type_id,
                'description' => $item->description,
                'amount' => 0,
                'is_from_template' => true,
                'template_item_id' => $item->id,
                'sort_order' => $item->sort_order,
                'is_active' => true,
            ]);
        }

        return true;
    }

    public function logHistory($modelType, $modelId, $fieldName, $oldValue, $newValue)
    {
        BusinessHistory::create([
            'business_id' => $this->id,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'field_name' => $fieldName,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'changed_by' => auth()->id(),
        ]);
    }
}
