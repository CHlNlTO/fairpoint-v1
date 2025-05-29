<?php
// app/Helpers/BusinessContext.php

namespace App\Helpers;

use App\Models\Business;
use Illuminate\Support\Facades\Cache;

class BusinessContext
{
    /**
     * Get the current business for the authenticated user
     */
    public static function current(): ?Business
    {
        if (!auth()->check()) {
            return null;
        }

        $businessId = session('current_business_id');

        if (!$businessId) {
            return null;
        }

        return Cache::remember(
            "business.{$businessId}",
            now()->addMinutes(5),
            fn() => Business::find($businessId)
        );
    }

    /**
     * Get the current business ID
     */
    public static function id(): ?int
    {
        return session('current_business_id');
    }

    /**
     * Set the current business
     */
    public static function set(Business $business): bool
    {
        if (auth()->user()->hasBusinessAccess($business)) {
            session(['current_business_id' => $business->id]);
            Cache::forget("business.{$business->id}");
            return true;
        }

        return false;
    }

    /**
     * Clear the current business context
     */
    public static function clear(): void
    {
        $businessId = session('current_business_id');
        if ($businessId) {
            Cache::forget("business.{$businessId}");
        }
        session()->forget('current_business_id');
    }

    /**
     * Check if user has a current business context
     */
    public static function has(): bool
    {
        return self::current() !== null;
    }

    /**
     * Get all businesses for the current user
     */
    public static function all()
    {
        if (!auth()->check()) {
            return collect();
        }

        return auth()->user()->activeBusinesses()
            ->with(['status', 'businessType', 'industry'])
            ->get();
    }
}
