<?php
// app/Models/BusinessTaxInformation.php

namespace App\Models;

use App\Traits\TracksHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessTaxInformation extends Model
{
    use HasFactory, TracksHistory;

    protected $table = 'business_tax_information';

    protected $fillable = [
        'business_id',
        'income_tax_type_id',
        'business_tax_type_id',
        'with_1601c',
        'with_ewt',
        'tamp',
    ];

    protected $casts = [
        'with_1601c' => 'boolean',
        'with_ewt' => 'boolean',
        'tamp' => 'boolean',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function incomeTaxType(): BelongsTo
    {
        return $this->belongsTo(IncomeTaxType::class);
    }

    public function businessTaxType(): BelongsTo
    {
        return $this->belongsTo(BusinessTaxType::class);
    }

    public function getIncomeTaxRateAttribute()
    {
        return $this->incomeTaxType?->rate ?? 0;
    }

    public function getBusinessTaxRateAttribute()
    {
        return $this->businessTaxType?->rate ?? 0;
    }
}
