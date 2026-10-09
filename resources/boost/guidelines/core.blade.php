## smronju/nativephp-firebase-analytics

Google Analytics for Firebase on Android. Requires `google-services.json` in the Laravel project root — the build copies it into the Android project and fails if it is missing.

@verbatim
<code-snippet name="Using the FirebaseAnalytics facade" lang="php">
use Smronju\NativephpFirebaseAnalytics\Facades\FirebaseAnalytics;

FirebaseAnalytics::setCollectionEnabled(false); // persisted by Firebase across launches
FirebaseAnalytics::logEvent('entry_created', ['direction' => 'lent']);
FirebaseAnalytics::logScreenView('settings');
</code-snippet>
@endverbatim

- Every method is a no-op outside the native runtime, so tests need no fakes.
- Event names: up to 40 letters/digits/underscores, starting with a letter. Param values: string, int, float or bool only.
- Never log personal data (names, emails, phone numbers, amounts tied to a person) as event params.
