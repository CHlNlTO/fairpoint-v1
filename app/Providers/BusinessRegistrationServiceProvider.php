<?php
// app/Providers/BusinessRegistrationServiceProvider.php

namespace App\Providers;

use App\Models\Business;
use App\Observers\BusinessObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;

class BusinessRegistrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/business-registration.php',
            'business-registration'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register observers
        Business::observe(BusinessObserver::class);

        // Register Gates for business access
        Gate::define('view-business', function ($user, Business $business) {
            return $user->hasBusinessAccess($business);
        });

        Gate::define('manage-business', function ($user, Business $business) {
            return $user->businessUsers()
                ->where('business_id', $business->id)
                ->whereHas('status', function ($query) {
                    $query->where('code', 'ACTIVE');
                })
                ->exists() && $user->hasRole(['super_admin', "business_{$business->id}_owner", "business_{$business->id}_admin"]);
        });

        Gate::define('create-business', function ($user) {
            return config('business-registration.features.allow_multiple_businesses_per_user', true)
                || $user->businesses()->count() === 0;
        });

        // Register event listeners
        Event::listen('business.created', function (Business $business) {
            // Send welcome email if enabled
            if (config('business-registration.notifications.send_welcome_email', true)) {
                // Implement email notification
            }
        });

        Event::listen('business.user.added', function (Business $business, $user) {
            // Notify user when added to business
            if (config('business-registration.notifications.notify_on_user_added', true)) {
                // Implement notification
            }
        });

        // Publish config
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/business-registration.php' => config_path('business-registration.php'),
            ], 'business-registration-config');
        }
    }
}
