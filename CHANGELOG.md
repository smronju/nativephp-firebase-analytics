# Changelog

All notable changes to this project will be documented in this file.

## [1.0.0] - 2026-10-10

### Added

- Android: installs `com.google.firebase:firebase-analytics`, applies the Google Services Gradle plugin to the app module, and copies the app's root `google-services.json` into the generated Android project (`project_files`, required).
- `FirebaseAnalytics::setCollectionEnabled($enabled)` turns collection on or off; Firebase persists the choice across launches.
- `FirebaseAnalytics::logEvent($name, $params)` logs a custom event with string/number/bool params.
- `FirebaseAnalytics::logScreenView($screenName, $screenClass)` names native screens, which Firebase's Activity-based auto tracking cannot do in a single-Activity NativePHP app.
