<?php

namespace cybot\cookiebot\lib;

use cybot\cookiebot\shortcode\Cookiebot_Declaration_Shortcode;
use InvalidArgumentException;

class Cookiebot_Javascript_Helper {
	public function register_hooks() {
		if ( is_admin() && ! Cookiebot_WP::cookiebot_disabled_in_admin() ) {
			// adding cookie banner in admin area too
			add_action( 'admin_head', array( $this, 'include_cookiebot_js' ), - 9999 );
		}

		// add JS
		add_action( 'wp_head', array( $this, 'include_cookiebot_js' ), - 9999 );
		add_action( 'wp_head', array( $this, 'include_google_consent_mode_js' ), - 9998 );
		add_action( 'wp_head', array( $this, 'include_google_tag_manager_js' ), - 9997 );
		( new Cookiebot_Declaration_Shortcode() )->register_hooks();
	}

	private function get_data_regions() {
		$is_multi_config      = ! empty( get_option( 'cookiebot-multiple-config' ) ) ?
			get_option( 'cookiebot-multiple-config' ) :
			false;
		$second_banner_region = ! empty( get_option( 'cookiebot-second-banner-regions' ) ) ?
			get_option( 'cookiebot-second-banner-regions' ) :
			false;
		$second_banner_id     = ! empty( get_option( 'cookiebot-second-banner-id' ) ) ?
			get_option( 'cookiebot-second-banner-id' ) :
			false;

		$extra_banners = ! empty( get_option( 'cookiebot-multiple-banners' ) ) ?
			get_option( 'cookiebot-multiple-banners' ) :
			false;

		$regions = array();

		if ( $is_multi_config !== false && $second_banner_region && $second_banner_id ) {
			$regions[ $second_banner_id ] = $second_banner_region;
		}

		if ( $is_multi_config !== false && $extra_banners ) {
			foreach ( $extra_banners as $data ) {
				$regions[ $data['group'] ] = $data['region'];
			}
		}

		return $regions;
	}

	/**
	 * Cookiebot_WP Add Cookiebot JS to <head>
	 *
	 * @param false $return_html
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function include_cookiebot_js( $return_html = false ) {
		$cbid = Cookiebot_WP::get_cbid();
		if ( ! empty( $cbid ) && ! defined( 'COOKIEBOT_DISABLE_ON_PAGE' ) ) {
			if (
				// Is multisite - and disabled output is checked as network setting
				( is_multisite() && get_site_option( 'cookiebot-nooutput', false ) ) ||
				// Do not show JS - output disabled
				get_option( 'cookiebot-nooutput', false ) ||
				// Do not show js if logged in output is disabled
				(
					Cookiebot_WP::get_cookie_blocking_mode() === 'auto' &&
					Cookiebot_WP::can_current_user_edit_theme() &&
					$return_html === '' &&
					(
						get_site_option( 'cookiebot-output-logged-in' ) === false ||
						get_site_option( 'cookiebot-output-logged-in' ) === ''
					)
				)
			) {
				return '';
			}

			$lang = cookiebot_get_language_from_setting();

			if ( ! is_multisite() || get_site_option( 'cookiebot-script-tag-uc-attribute', 'custom' ) === 'custom' ) {
				$tag_attr = get_option( 'cookiebot-script-tag-uc-attribute', 'async' );
			} else {
				$tag_attr = get_site_option( 'cookiebot-script-tag-uc-attribute' );
			}

			$view_path = 'frontend/scripts/cookiebot-js.php';
			$view_args = array(
				'cbid'                 => $cbid,
				'lang'                 => $lang,
				'tag_attr'             => $tag_attr,
				'cookie_blocking_mode' => Cookiebot_WP::get_cookie_blocking_mode(),
				'data_regions'         => self::get_data_regions(),
			);

			if ( $return_html ) {
				return get_view_html( $view_path, $view_args );
			} else {
				include_view( $view_path, $view_args );
			}
		}
		return '';
	}

	/**
	 * Cookiebot_WP Add Google Tag Manager JS to <head>
	 *
	 * @param bool $return_html
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function include_google_tag_manager_js( $return_html = false ) {
		$option            = get_option( 'cookiebot-gtm' );
		$blocking_mode     = get_option( 'cookiebot-cookie-blocking-mode' );
		$cookie_categories = get_option( 'cookiebot-gtm-cookies' );

		if ( $option !== false && $option !== '' ) {
			if ( empty( get_option( 'cookiebot-data-layer' ) ) ) {
				$data_layer = 'dataLayer';
			} else {
				$data_layer = get_option( 'cookiebot-data-layer' );
			}

			$view_path = 'frontend/scripts/google-tag-manager-js.php';

			$view_args = array(
				'data_layer'        => $data_layer,
				'consent_attribute' => self::get_consent_attribute( $blocking_mode, $cookie_categories ),
			);
			if ( $return_html ) {
				return get_view_html( $view_path, $view_args );
			} else {
				include_view( $view_path, $view_args );
			}
		}
		return '';
	}

	/**
	 * Cookiebot_WP Add Google Consent Mode JS to <head>
	 *
	 * @param bool $return_html
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function include_google_consent_mode_js( $return_html = false ) {
		$option                     = get_option( 'cookiebot-gcm' );
		$blocking_mode              = get_option( 'cookiebot-cookie-blocking-mode' );
		$is_url_passthrough_enabled = '1' === (string) get_option( 'cookiebot-gcm-url-passthrough', 1 );
		$cookie_categories          = get_option( 'cookiebot-gcm-cookies' );

		if ( $option !== false && $option !== '' ) {
			if ( empty( get_option( 'cookiebot-data-layer' ) ) ) {
				$data_layer = 'dataLayer';
			} else {
				$data_layer = get_option( 'cookiebot-data-layer' );
			}

			$view_path = 'frontend/scripts/google-consent-mode-js.php';

			$view_args = array(
				'data_layer'        => $data_layer,
				'url_passthrough'   => $is_url_passthrough_enabled,
				'consent_attribute' => self::get_consent_attribute( $blocking_mode, $cookie_categories ),
			);
			if ( $return_html ) {
				return get_view_html( $view_path, $view_args );
			} else {
				include_view( $view_path, $view_args );
			}
		}
		return '';
	}

	private function get_consent_attribute( $blocking_mode, $categories ) {
		$attribute = false;

		if ( $blocking_mode === 'auto' ) {
			$attribute = 'ignore';
		}

		if ( $categories && is_array( $categories ) ) {
			$attribute = join( ', ', $categories );
		}

		return $attribute;
	}
}
