<?php

namespace Smronju\NativephpFirebaseAnalytics;

class FirebaseAnalytics
{
    /**
     * Turn analytics collection on or off for this device. Firebase persists
     * the choice across launches, so call this when the user changes it —
     * calling it again with the same value is harmless.
     */
    public function setCollectionEnabled(bool $enabled): void
    {
        if (function_exists('nativephp_call')) {
            nativephp_call('FirebaseAnalytics.SetCollectionEnabled', json_encode(['enabled' => $enabled]));
        }
    }

    /**
     * Log a custom event. Names follow Firebase's rules: up to 40
     * alphanumeric/underscore characters, starting with a letter.
     *
     * @param  array<string, string|int|float|bool>  $params
     */
    public function logEvent(string $name, array $params = []): void
    {
        if (function_exists('nativephp_call')) {
            nativephp_call('FirebaseAnalytics.LogEvent', json_encode([
                'name' => $name,
                'params' => (object) $params,
            ]));
        }
    }

    /**
     * Log a screen_view for a native screen. NativePHP renders every screen in
     * one Activity, so Firebase cannot name screens on its own.
     */
    public function logScreenView(string $screenName, ?string $screenClass = null): void
    {
        if (function_exists('nativephp_call')) {
            nativephp_call('FirebaseAnalytics.LogScreenView', json_encode(array_filter([
                'screen_name' => $screenName,
                'screen_class' => $screenClass,
            ])));
        }
    }
}
