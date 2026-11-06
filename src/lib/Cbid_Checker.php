<?php

namespace cybot\cookiebot\lib;

use function home_url;
use function is_wp_error;
use function rawurlencode;
use function wp_parse_url;
use function wp_remote_get;
use function wp_remote_retrieve_body;
use function wp_remote_retrieve_response_code;

/**
 * Validates the format of a Cookiebot / Usercentrics ID and checks whether the
 * current site's domain is set up for a Domain Group ID.
 *
 * NOTE: the consent.cookiebot.com/<id>/cc.js endpoint used for the domain check
 * is undocumented; its response markers may change without notice. Anything
 * unexpected maps to RESULT_UNKNOWN or RESULT_MATCH so a change never blocks
 * a user from connecting.
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
	 * Classify an ID: UUID = Domain Group ID, 9 or 14 chars = Settings ID.
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

		if ( self::OUT_OF_REGION === $body ) {
			return self::RESULT_UNKNOWN;
		}

		return self::RESULT_MATCH;
	}
}
