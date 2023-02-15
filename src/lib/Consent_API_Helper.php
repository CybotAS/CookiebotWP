<?php

namespace cybot\cookiebot\lib;

use InvalidArgumentException;

class Consent_API_Helper {
	public function register_hooks() {
		// Include integration to WP Consent Level API if available
		if ( $this->is_wp_consent_api_active() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'cookiebot_enqueue_consent_api_scripts' ) );
		}
	}

	/**
	 * Cookiebot_WP Check if WP Cookie Consent API is active
	 *
	 * @version 3.5.0
	 * @since       3.5.0
	 */
	public function is_wp_consent_api_active() {
		return class_exists( 'WP_CONSENT_API' );
	}

	/**
	 * Cookiebot_WP Enqueue JS for integration with WP Consent Level API
	 *
	 * @throws InvalidArgumentException
	 * @since   3.5.0
	 * @version 3.5.0
	 */
	public function cookiebot_enqueue_consent_api_scripts() {
		wp_register_script(
			'cookiebot-wp-consent-level-api-integration',
			asset_url( 'js/frontend/cookiebot-wp-consent-level-api-integration.js' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			false
		);
		wp_enqueue_script( 'cookiebot-wp-consent-level-api-integration' );
		wp_localize_script(
			'cookiebot-wp-consent-level-api-integration',
			'cookiebot_category_mapping',
			$this->get_wp_consent_api_mapping()
		);
	}

	/**
	 * Cookiebot_WP Get the mapping between Consent Level API and Cookiebot
	 * Returns array where key is the consent level api category and value
	 * is the mapped Cookiebot category.
	 *
	 * @version 3.5.0
	 * @since   3.5.0
	 */
	public function get_wp_consent_api_mapping() {
		$default_wp_consent_api_mapping = $this->get_default_wp_consent_api_mapping();
		$mapping                        = get_option( 'cookiebot-consent-mapping', $default_wp_consent_api_mapping );

		$mapping = ( '' === $mapping ) ? $default_wp_consent_api_mapping : $mapping;

		foreach ( $default_wp_consent_api_mapping as $k => $v ) {
			if ( ! isset( $mapping[ $k ] ) ) {
				$mapping[ $k ] = $v;
			} else {
				foreach ( $v as $vck => $vcv ) {
					if ( ! isset( $mapping[ $k ][ $vck ] ) ) {
						$mapping[ $k ][ $vck ] = $vcv;
					}
				}
			}
		}

		return $mapping;
	}

	/**
	 * Cookiebot_WP Default consent level mappings
	 *
	 * @version 3.5.0
	 * @since   3.5.0
	 */
	public function get_default_wp_consent_api_mapping() {
		return array(
			'n=1;p=1;s=1;m=1' =>
				array(
					'preferences'          => 1,
					'statistics'           => 1,
					'statistics-anonymous' => 0,
					'marketing'            => 1,
				),
			'n=1;p=1;s=1;m=0' =>
				array(
					'preferences'          => 1,
					'statistics'           => 1,
					'statistics-anonymous' => 1,
					'marketing'            => 0,
				),
			'n=1;p=1;s=0;m=1' =>
				array(
					'preferences'          => 1,
					'statistics'           => 0,
					'statistics-anonymous' => 0,
					'marketing'            => 1,
				),
			'n=1;p=1;s=0;m=0' =>
				array(
					'preferences'          => 1,
					'statistics'           => 0,
					'statistics-anonymous' => 0,
					'marketing'            => 0,
				),
			'n=1;p=0;s=1;m=1' =>
				array(
					'preferences'          => 0,
					'statistics'           => 1,
					'statistics-anonymous' => 0,
					'marketing'            => 1,
				),
			'n=1;p=0;s=1;m=0' =>
				array(
					'preferences'          => 0,
					'statistics'           => 1,
					'statistics-anonymous' => 0,
					'marketing'            => 0,
				),
			'n=1;p=0;s=0;m=1' =>
				array(
					'preferences'          => 0,
					'statistics'           => 0,
					'statistics-anonymous' => 0,
					'marketing'            => 1,
				),
			'n=1;p=0;s=0;m=0' =>
				array(
					'preferences'          => 0,
					'statistics'           => 0,
					'statistics-anonymous' => 0,
					'marketing'            => 0,
				),
		);
	}
}
