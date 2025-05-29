<?php
// app/Models/ChartOfAccountsTemplateHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChartOfAccountsTemplateHistory extends Model
{
    use HasFactory;

    protected $table = 'chart_of_accounts_template_history';

    protected $fillable = [
        'template_id',
        'version',
        'template_data',
        'change_description',
        'changed_by',
    ];

    protected $casts = [
        'template_data' => 'array',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccountsTemplate::class, 'template_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
