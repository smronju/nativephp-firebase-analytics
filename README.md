# NativePHP Firebase Analytics

Google Analytics for Firebase in a [NativePHP Mobile](https://nativephp.com) app, on Android.

Installing it is what gets AdMob's user and engagement metrics (sessions, active users, retention, ad revenue per user) flowing once your AdMob app is linked to a Firebase app. On top of what Firebase collects automatically, it lets PHP log custom events and screen views, and turn collection off for a user who opts out.

## Installation

```bash
composer require smronju/nativephp-firebase-analytics
php artisan native:plugin:register smronju/nativephp-firebase-analytics
```

Download `google-services.json` for your Android app from **Firebase console → Project settings → Your apps** and put it in your Laravel project root. The build copies it into the Android project and fails loudly if it is missing.

Then rebuild: `php artisan native:run android`.

## Usage

```php
use Smronju\NativephpFirebaseAnalytics\Facades\FirebaseAnalytics;

// Respect a user's opt-out. Firebase remembers this across launches.
FirebaseAnalytics::setCollectionEnabled(false);

// Custom event — name: up to 40 letters/digits/underscores, starting with a letter.
FirebaseAnalytics::logEvent('entry_created', ['direction' => 'lent', 'has_due_date' => true]);

// Name a native screen (NativePHP runs every screen in one Activity).
FirebaseAnalytics::logScreenView('settings');
```

Every method is a silent no-op outside the native runtime (tests, `php artisan serve`).

## Platforms

Android only. iOS needs `GoogleService-Info.plist` and the Firebase pod and is not implemented yet.

## Privacy

Firebase Analytics collects an app-instance ID, device and app information, and usage events, and shares them with Google. Disclose it in your privacy policy and in Google Play's Data safety form, and give users a way to opt out (`setCollectionEnabled(false)`).

## License

MIT
