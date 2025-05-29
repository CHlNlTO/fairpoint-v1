<?php
// app/Models/BusinessHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessHistory extends Model
{
    use HasFactory;

    protected $table = 'business_history';

    protected $fillable = [
        'business_id',
        'model_type',
        'model_id',
        'field_name',
        'old_value',
        'new_value',
        'changed_by',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function getModelAttribute()
    {
        if ($this->model_type && $this->model_id) {
            return $this->model_type::find($this->model_id);
        }

        return null;
    }

    public function scopeForModel($query, $modelType, $modelId = null)
    {
        $query->where('model_type', $modelType);

        if ($modelId) {
            $query->where('model_id', $modelId);
        }

        return $query;
    }

    public function scopeForField($query, $fieldName)
    {
        return $query->where('field_name', $fieldName);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('changed_by', $userId);
    }

    public function getFormattedChangeAttribute()
    {
        return [
            'field' => $this->field_name,
            'from' => $this->old_value,
            'to' => $this->new_value,
            'by' => $this->changedBy?->name,
            'at' => $this->created_at->format('M d, Y H:i'),
        ];
    }
}
