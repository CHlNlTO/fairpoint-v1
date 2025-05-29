<?php
// app/Observers/BusinessObserver.php

namespace App\Observers;

use App\Models\Business;
use App\Models\BusinessHistory;
use App\Models\BusinessRegistrationProgress;

class BusinessObserver
{
    public function created(Business $business): void
    {
        // Initialize registration progress steps
        $steps = config('business-registration.registration_steps', []);

        foreach ($steps as $stepCode => $stepConfig) {
            BusinessRegistrationProgress::create([
                'business_id' => $business->id,
                'step_code' => $stepCode,
                'data' => [],
                'is_completed' => false,
            ]);
        }

        // Mark basic_info as completed if business has name
        if ($business->name) {
            $basicInfo = $business->registrationProgress()
                ->where('step_code', 'basic_info')
                ->first();

            if ($basicInfo) {
                $basicInfo->markAsCompleted([
                    'name' => $business->name,
                    'business_type_id' => $business->business_type_id,
                    'business_structure_id' => $business->business_structure_id,
                    'industry_id' => $business->industry_id,
                ]);
            }
        }
    }

    public function updating(Business $business): void
    {
        if (!config('business-registration.features.track_history', true)) {
            return;
        }

        $original = $business->getOriginal();
        $changes = $business->getDirty();

        foreach ($changes as $field => $newValue) {
            if (in_array($field, ['created_at', 'updated_at', 'updated_by'])) {
                continue;
            }

            BusinessHistory::create([
                'business_id' => $business->id,
                'model_type' => Business::class,
                'model_id' => $business->id,
                'field_name' => $field,
                'old_value' => $original[$field] ?? null,
                'new_value' => $newValue,
                'changed_by' => auth()->id() ?? $business->updated_by,
            ]);
        }
    }

    public function deleted(Business $business): void
    {
        // Log deletion in history
        BusinessHistory::create([
            'business_id' => $business->id,
            'model_type' => Business::class,
            'model_id' => $business->id,
            'field_name' => 'deleted',
            'old_value' => 'active',
            'new_value' => 'deleted',
            'changed_by' => auth()->id(),
        ]);
    }
}
