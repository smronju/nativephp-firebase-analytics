<?php

namespace Smronju\NativephpFirebaseAnalytics\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void setCollectionEnabled(bool $enabled)
 * @method static void logEvent(string $name, array<string, string|int|float|bool> $params = [])
 * @method static void logScreenView(string $screenName, ?string $screenClass = null)
 *
 * @see \Smronju\NativephpFirebaseAnalytics\FirebaseAnalytics
 */
class FirebaseAnalytics extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Smronju\NativephpFirebaseAnalytics\FirebaseAnalytics::class;
    }
}
