package com.smronju.plugins.nativephp_firebase_analytics

import android.content.Context
import android.os.Bundle
import com.google.firebase.analytics.FirebaseAnalytics
import com.nativephp.mobile.bridge.BridgeFunction

/**
 * Builds a Firebase params Bundle from the bridge payload's `params` map.
 *
 * Firebase only accepts String, Long and Double values; anything else (nested
 * maps, lists, nulls) is dropped rather than coerced, so a malformed param
 * never takes the whole event down with it.
 */
private fun toBundle(raw: Any?): Bundle {
    val bundle = Bundle()
    val params = raw as? Map<*, *> ?: return bundle

    for ((key, value) in params) {
        val name = key as? String ?: continue

        when (value) {
            is String -> bundle.putString(name, value)
            is Boolean -> bundle.putLong(name, if (value) 1L else 0L)
            is Int -> bundle.putLong(name, value.toLong())
            is Long -> bundle.putLong(name, value)
            is Float -> bundle.putDouble(name, value.toDouble())
            is Double -> bundle.putDouble(name, value)
        }
    }

    return bundle
}

object FirebaseAnalyticsFunctions {

    /**
     * Turn analytics collection on or off for this device.
     *
     * Firebase persists this across launches itself, so it only needs calling
     * when the user's choice changes — collection resumes or stays off on the
     * next cold start without PHP having to run first.
     */
    class SetCollectionEnabled(private val context: Context) : BridgeFunction {
        override fun execute(parameters: Map<String, Any>): Map<String, Any> {
            val enabled = parameters["enabled"] as? Boolean ?: true

            FirebaseAnalytics.getInstance(context).setAnalyticsCollectionEnabled(enabled)

            return mapOf("success" to true, "enabled" to enabled)
        }
    }

    /**
     * Log a custom event. A no-op on Firebase's side while collection is off.
     */
    class LogEvent(private val context: Context) : BridgeFunction {
        override fun execute(parameters: Map<String, Any>): Map<String, Any> {
            val name = parameters["name"] as? String

            if (name.isNullOrBlank()) {
                return mapOf("success" to false, "error" to "Event name is required")
            }

            FirebaseAnalytics.getInstance(context).logEvent(name, toBundle(parameters["params"]))

            return mapOf("success" to true)
        }
    }

    /**
     * Log a screen_view. NativePHP renders every screen inside one Activity, so
     * Firebase's automatic Activity-based screen tracking only ever sees
     * "MainActivity" — this is how individual native screens get named.
     */
    class LogScreenView(private val context: Context) : BridgeFunction {
        override fun execute(parameters: Map<String, Any>): Map<String, Any> {
            val screenName = parameters["screen_name"] as? String

            if (screenName.isNullOrBlank()) {
                return mapOf("success" to false, "error" to "Screen name is required")
            }

            val bundle = Bundle().apply {
                putString(FirebaseAnalytics.Param.SCREEN_NAME, screenName)
                putString(
                    FirebaseAnalytics.Param.SCREEN_CLASS,
                    parameters["screen_class"] as? String ?: screenName
                )
            }

            FirebaseAnalytics.getInstance(context).logEvent(FirebaseAnalytics.Event.SCREEN_VIEW, bundle)

            return mapOf("success" to true)
        }
    }
}
