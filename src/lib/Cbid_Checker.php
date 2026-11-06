<?php

namespace cybot\cookiebot\lib;

use function delete_transient;
use function get_transient;
use function home_url;
use function is_wp_error;
use function rawurlencode;
use function set_transient;
use function wp_parse_url;
use function wp_remote_get;
use function wp_remote_retrieve_body;
use function wp_remote_retrieve_response_code;

/**
 * Validates the format of a Cookiebot / Usercentrics ID and checks whether the
 * current site's domain is set up for a Domain Group ID.
 *
 * NOTE: the consent.cookiebot.com/<id>/cc.js endpoint used for the domain check
 * is undocumented; its response markers may change without notice. Only a
 * body with the banner script marker counts as RESULT_MATCH; anything
 * unexpected maps to RESULT_UNKNOWN, which still lets the user connect.
 *
 * The ID is sensitive: never log or echo it.
 */
class Cbid_Checker {

	const TYPE_DOMAIN_GROUP = 'domain_group';
	const TYPE_SETTINGS     = 'settings';
	const TYPE_INVALID      = 'invalid';

	const RESULT_MATCH      = 'match';
	const RESULT_NOT_SET_UP = 'not_set_up';
	const RESULT_INVALID    = 'invalid';
	const RESULT_UNKNOWN    = 'unknown';

	const ENDPOINT = 'https://consent.cookiebot.com/%s/cc.js?referer=%s';

	const NOT_AUTHORIZED_MARKER = 'is not authorized to show the cookie banner';
	const OUT_OF_REGION         = 'CookieConsent.setOutOfRegion();';
	// Near the start of the real banner script (about byte 70 of the response).
	const BANNER_SCRIPT_MARKER = 'CybotCookiebotHidden';

	const CACHE_PREFIX = 'cookiebot_cbid_check_';
	const CACHE_TTL    = 600;

	const TIMEOUT = 5;

	/**
	 * Trim an ID. Non-strings normalize to an empty string.
	 *
	 * 36-character (Domain Group) IDs are lowercased: Cookiebot_Frame only
	 * recognises lowercase UUIDs, so an uppercase ID would load the wrong script.
	 * Settings IDs are case-sensitive and keep their case.
	 *
	 * @param mixed $id Raw ID.
	 * @return string
	 */
	public static function normalize( $id ) {
		$id = is_string( $id ) ? trim( $id ) : '';

		return 36 === strlen( $id ) ? strtolower( $id ) : $id;
	}

	/**
	 * Classify an ID.
	 * - Domain Group ID: a UUID (lowercased by normalize()).
	 * - Settings ID: 9 or 14 chars of [A-Za-z0-9_-].
	 * - Anything else: invalid.
	 *
	 * @param mixed $id Raw ID.
	 * @return string One of the TYPE_* constants.
	 */
	public static function get_type( $id ) {
		$id     = self::normalize( $id );
		$length = strlen( $id );

		// Domain Group IDs are UUIDs; normalize() has already lowercased them.
		if ( 36 === $length && preg_match( '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $id ) ) {
			return self::TYPE_DOMAIN_GROUP;
		}

		if ( ( 9 === $length || 14 === $length ) && preg_match( '/^[A-Za-z0-9_-]+$/', $id ) ) {
			return self::TYPE_SETTINGS;
		}

		return self::TYPE_INVALID;
	}

	/**
	 * Lowercase host of the current site, without port or path.
	 *
	 * @return string
	 */
	public static function get_site_host() {
		$host = wp_parse_url( home_url(), PHP_URL_HOST );

		return is_string( $host ) ? strtolower( $host ) : '';
	}

	/**
	 * Check an ID. Settings IDs cannot be verified remotely, so they are treated
	 * as the success path (RESULT_MATCH) without an HTTP call.
	 *
	 * @param mixed       $id   Raw ID.
	 * @param string|null $host Site host to check; defaults to the current site host.
	 * @return string One of the RESULT_* constants.
	 */
	public static function check( $id, $host = null ) {
		$type = self::get_type( $id );

		if ( self::TYPE_INVALID === $type ) {
			return self::RESULT_INVALID;
		}

		if ( self::TYPE_SETTINGS === $type ) {
			return self::RESULT_MATCH;
		}

		$id   = self::normalize( $id );
		$host = null === $host ? self::get_site_host() : $host;

		$response = wp_remote_get(
			sprintf( self::ENDPOINT, rawurlencode( $id ), rawurlencode( $host ) ),
			array(
				'limit_response_size' => 1024,
				'timeout'             => self::TIMEOUT,
			)
		);

		if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return self::RESULT_UNKNOWN;
		}

		$body = trim( (string) wp_remote_retrieve_body( $response ) );

		if ( '' === $body ) {
			return self::RESULT_INVALID;
		}

		if ( false !== strpos( $body, self::NOT_AUTHORIZED_MARKER ) ) {
			return self::RESULT_NOT_SET_UP;
		}

		if ( false !== strpos( $body, self::BANNER_SCRIPT_MARKER ) ) {
			return self::RESULT_MATCH;
		}

		// Out-of-region bodies and any other unexpected response.
		return self::RESULT_UNKNOWN;
	}

	/**
	 * check() with a short cache, so the dashboard doesn't block on the
	 * remote call on every load. RESULT_UNKNOWN is not cached.
	 *
	 * @param mixed       $id   Raw ID.
	 * @param string|null $host Site host; defaults to the current site host.
	 * @return string One of the RESULT_* constants.
	 */
	public static function check_cached( $id, $host = null ) {
		$host   = null === $host ? self::get_site_host() : $host;
		$key    = self::cache_key( $id, $host );
		$cached = get_transient( $key );

		if ( is_string( $cached ) && '' !== $cached ) {
			return $cached;
		}

		$result = self::check( $id, $host );
		self::remember( $id, $result, $host );

		return $result;
	}

	/**
	 * Store a check result. RESULT_UNKNOWN is not cached.
	 *
	 * @param mixed       $id     Raw ID.
	 * @param string      $result One of the RESULT_* constants.
	 * @param string|null $host   Site host; defaults to the current site host.
	 * @return void
	 */
	public static function remember( $id, $result, $host = null ) {
		if ( self::RESULT_UNKNOWN === $result ) {
			return;
		}

		$host = null === $host ? self::get_site_host() : $host;
		set_transient( self::cache_key( $id, $host ), $result, self::CACHE_TTL );
	}

	/**
	 * Drop the cached result for an ID on the current site.
	 *
	 * @param mixed $id Raw ID.
	 * @return void
	 */
	public static function forget( $id ) {
		delete_transient( self::cache_key( $id, self::get_site_host() ) );
	}

	/**
	 * Hash of an ID, so it never appears in option names or values.
	 *
	 * @param mixed $id Raw ID.
	 * @return string
	 */
	public static function fingerprint( $id ) {
		return hash( 'sha256', self::normalize( $id ) );
	}

	/**
	 * Transient key for an ID and host.
	 *
	 * @param mixed  $id   Raw ID.
	 * @param string $host Site host.
	 * @return string
	 */
	private static function cache_key( $id, $host ) {
		return self::CACHE_PREFIX . md5( self::normalize( $id ) . '|' . $host );
	}
}
