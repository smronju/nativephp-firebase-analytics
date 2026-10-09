<?php

namespace Smronju\NativephpFirebaseAnalytics;

use Illuminate\Support\ServiceProvider;

class FirebaseAnalyticsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FirebaseAnalytics::class, function () {
            return new FirebaseAnalytics;
        });
    }
}
