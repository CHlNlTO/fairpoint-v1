<?php
// app/Http/Middleware/SetBusinessContext.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetBusinessContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $businessId = session('current_business_id');

            if (!$businessId) {
                $business = auth()->user()->getCurrentBusiness();
                if ($business) {
                    session(['current_business_id' => $business->id]);
                }
            } else {
                // Verify user still has access to this business
                if (!auth()->user()->hasBusinessAccess($businessId)) {
                    session()->forget('current_business_id');
                    $business = auth()->user()->getCurrentBusiness();
                    if ($business) {
                        session(['current_business_id' => $business->id]);
                    }
                }
            }
        }

        return $next($request);
    }
}
