<?php
// app/Traits/TracksHistory.php

namespace App\Traits;

use App\Models\BusinessHistory;

trait TracksHistory
{
    public static function bootTracksHistory()
    {
        static::updating(function ($model) {
            $model->trackChanges();
        });
    }

    protected function trackChanges()
    {
        if (!config('business-registration.features.track_history', true)) {
            return;
        }

        if (!method_exists($this, 'business')) {
            return;
        }

        $original = $this->getOriginal();
        $changes = $this->getDirty();

        foreach ($changes as $field => $newValue) {
            if ($this->shouldTrackField($field)) {
                BusinessHistory::create([
                    'business_id' => $this->business_id ?? $this->business->id,
                    'model_type' => static::class,
                    'model_id' => $this->id,
                    'field_name' => $field,
                    'old_value' => $original[$field] ?? null,
                    'new_value' => $newValue,
                    'changed_by' => auth()->id(),
                ]);
            }
        }
    }

    protected function shouldTrackField($field)
    {
        $ignoredFields = [
            'created_at',
            'updated_at',
            'updated_by',
            'remember_token',
            'password',
        ];

        return !in_array($field, $ignoredFields);
    }

    public function history()
    {
        return BusinessHistory::where('model_type', static::class)
            ->where('model_id', $this->id)
            ->orderBy('created_at', 'desc');
    }
}
