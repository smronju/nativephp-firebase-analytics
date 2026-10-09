<?php

/**
 * Plugin validation tests for FirebaseAnalytics.
 *
 * Run with: ./vendor/bin/pest
 */
beforeEach(function () {
    $this->pluginPath = dirname(__DIR__);
    $this->manifest = json_decode(file_get_contents($this->pluginPath.'/nativephp.json'), true);
});

describe('Plugin Manifest', function () {
    it('is valid JSON with the required fields', function () {
        expect($this->manifest)->toBeArray()
            ->toHaveKeys(['name', 'namespace', 'bridge_functions'])
            ->and($this->manifest['name'])->toBe('smronju/nativephp-firebase-analytics')
            ->and($this->manifest['namespace'])->toBe('FirebaseAnalytics')
            ->and($this->manifest['platforms'])->toBe(['android']);
    });

    it('installs the Firebase Analytics SDK', function () {
        expect($this->manifest['android']['dependencies']['implementation'])
            ->toContain('com.google.firebase:firebase-analytics:23.0.0');
    });

    it('applies the Google Services Gradle plugin to the app module', function () {
        expect($this->manifest['android']['gradle_plugins'])->toContain([
            'id' => 'com.google.gms.google-services',
            'version' => '4.4.3',
            'apply_to' => 'app',
        ]);
    });

    it('requires google-services.json from the project root', function () {
        expect($this->manifest['android']['project_files'])->toContain([
            'sources' => 'google-services.json',
            'destination' => 'app/google-services.json',
            'required' => true,
        ]);
    });
});

describe('Native Code', function () {
    it('has a Kotlin class for every bridge function', function () {
        $kotlin = file_get_contents($this->pluginPath.'/resources/android/FirebaseAnalyticsFunctions.kt');

        expect($kotlin)->toContain('package com.smronju.plugins.nativephp_firebase_analytics')
            ->toContain('object FirebaseAnalyticsFunctions');

        foreach ($this->manifest['bridge_functions'] as $function) {
            $parts = explode('.', $function['android']);
            $className = end($parts);

            expect($kotlin)->toContain("class {$className}(private val context: Context)");
        }
    });
});

describe('PHP Classes', function () {
    it('no-ops outside the native runtime', function () {
        $analytics = new Smronju\NativephpFirebaseAnalytics\FirebaseAnalytics;

        $analytics->setCollectionEnabled(false);
        $analytics->logEvent('entry_created', ['direction' => 'lent']);
        $analytics->logScreenView('settings');

        expect(function_exists('nativephp_call'))->toBeFalse();
    });
});

describe('Composer Configuration', function () {
    it('is a nativephp plugin pointing at its manifest', function () {
        $composer = json_decode(file_get_contents($this->pluginPath.'/composer.json'), true);

        expect($composer['type'])->toBe('nativephp-plugin')
            ->and($composer['extra']['nativephp']['manifest'])->toBe('nativephp.json');
    });
});
