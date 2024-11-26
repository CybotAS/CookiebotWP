<?php

namespace cybot\cookiebot\lib;

use cybot\cookiebot\shortcode\Cookiebot_Declaration_Shortcode;
use InvalidArgumentException;

class Cookiebot_Javascript_Helper {
	public function register_hooks() {
		self::get_hooks_by_frame();
	}

	private function get_hooks_by_frame() {
		$frame          = Cookiebot_Frame::is_cb_frame_type();
		$admin_disabled = is_admin() && ! Cookiebot_WP::cookiebot_disabled_in_admin();

		if ( $frame === true ) {
			if ( $admin_disabled ) {
				// adding cookie banner in admin area too
				add_action( 'admin_head', array( $this, 'include_cookiebot_js' ), - 9999 );
			}

			// add JS
			if ( self::is_tcf_enabled() ) {
				add_action( 'wp_head', array( $this, 'include_publisher_restrictions_js' ), -9999 );
			}
			add_action( 'wp_head', array( $this, 'include_google_consent_mode_js' ), - 9998 );
			add_action( 'wp_head', array( $this, 'include_google_tag_manager_js' ), - 9997 );
			add_action( 'wp_head', array( $this, 'include_cookiebot_js' ), - 9996 );
			( new Cookiebot_Declaration_Shortcode() )->register_hooks();
		}

		if ( $frame === false ) {
			if ( $admin_disabled ) {
				// adding cookie banner in admin area too
				add_action( 'admin_head', array( $this, 'include_uc_cmp_js' ), - 9999 );
			}

			add_action( 'wp_head', array( $this, 'include_uc_cmp_js' ), - 9998 );
			add_action( 'wp_head', array( $this, 'include_google_consent_mode_js' ), - 9997 );
			add_action( 'wp_head', array( $this, 'include_google_tag_manager_js' ), - 9996 );
		}
	}

	public function include_uc_cmp_js( $return_html = false ) {
		$cbid = Cookiebot_WP::get_cbid();

		if ( ! empty( $cbid ) && ! defined( 'COOKIEBOT_DISABLE_ON_PAGE' ) ) {
			if (
				// Is multisite - and disabled output is checked as network setting
				( is_multisite() && get_site_option( 'cookiebot-nooutput', false ) ) ||
				// Do not show JS - output disabled
				get_option( 'cookiebot-nooutput', false ) ||
				// Do not show js if logged in output is disabled
				(
					Cookiebot_WP::can_current_user_edit_theme() &&
					$return_html === '' &&
					(
						get_option( 'cookiebot-output-logged-in' ) === false ||
						get_option( 'cookiebot-output-logged-in' ) === ''
					)
				)
			) {
				return '';
			}

			$view_path = 'frontend/scripts/uc_frame/uc-cmp-js.php';
			$view_args = array(
				'cbid' => $cbid,
				'ruleset_id' => ! empty( get_option( 'cookiebot-ruleset-id' ) ) ?
					get_option( 'cookiebot-ruleset-id' ) : 'settings',
				'source' => get_option( 'cookiebot-ruleset-id' ) === 'ruleset' ?
					'https://app.usercentrics.eu/browser-ui/latest/loader.js' :
					'https://web.cmp.usercentrics.eu/ui/loader.js',
				'auto' => Cookiebot_WP::get_cookie_blocking_mode() === 'auto'
			);

			if ( $return_html ) {
				return get_view_html( $view_path, $view_args );
			} else {
				include_view( $view_path, $view_args );
			}
		}
		return '';
	}

	private function is_tcf_enabled() {
		return ! empty( get_option( 'cookiebot-iab' ) );
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
						get_option( 'cookiebot-output-logged-in' ) === false ||
						get_option( 'cookiebot-output-logged-in' ) === ''
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

			$view_path = 'frontend/scripts/cb_frame/cookiebot-js.php';
			$view_args = array(
				'cbid'                 => $cbid,
				'lang'                 => $lang,
				'tag_attr'             => $tag_attr,
				'cookie_blocking_mode' => Cookiebot_WP::get_cookie_blocking_mode(),
				'data_regions'         => self::get_data_regions(),
				'tcf'                  => self::get_tcf_attribute(),
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
		$option        = get_option( 'cookiebot-gtm' );
		$blocking_mode = Cookiebot_WP::get_cookie_blocking_mode();

		if ( $option !== false && $option !== '' ) {
			$cookiebot_gtm_id  = get_option( 'cookiebot-gtm-id' );
			$data_layer        = empty( get_option( 'cookiebot-data-layer' ) ) ? 'dataLayer' : get_option( 'cookiebot-data-layer' );
			$iab               = Cookiebot_Frame::is_cb_frame_type() === true && ! empty( get_option( 'cookiebot-iab' ) ) ?
				get_option( 'cookiebot-iab' ) :
				false;
			$cookie_categories = get_option( 'cookiebot-gtm-cookies' );

			$view_path = 'frontend/scripts/common/google-tag-manager-js.php';

			$view_args = array(
				'gtm_id'            => $cookiebot_gtm_id,
				'data_layer'        => $data_layer,
				'consent_attribute' => Cookiebot_Frame::is_cb_frame_type() === true ?
					self::get_consent_attribute( $blocking_mode, $cookie_categories ) :
					false,
				'iab'               => $iab,
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
		$option        = get_option( 'cookiebot-gcm' );
		$blocking_mode = Cookiebot_WP::get_cookie_blocking_mode();

		if ( $option !== false && $option !== '' ) {
			$data_layer                 = empty( get_option( 'cookiebot-data-layer' ) ) ? 'dataLayer' : get_option( 'cookiebot-data-layer' );
			$is_url_passthrough_enabled = Cookiebot_Frame::is_cb_frame_type() === true && ! empty( get_option( 'cookiebot-gcm-url-passthrough' ) ) ?
				get_option( 'cookiebot-gcm-url-passthrough' ) :
				false;
			$cookie_categories          = get_option( 'cookiebot-gcm-cookies' );

			$view_path = 'frontend/scripts/common/google-consent-mode-js.php';

			$view_args = array(
				'data_layer'        => $data_layer,
				'url_passthrough'   => $is_url_passthrough_enabled,
				'consent_attribute' => Cookiebot_Frame::is_cb_frame_type() === true ?
					self::get_consent_attribute( $blocking_mode, $cookie_categories ) :
					false,
			);
			if ( $return_html ) {
				return get_view_html( $view_path, $view_args );
			} else {
				include_view( $view_path, $view_args );
			}
		}
		return '';
	}

	public function include_publisher_restrictions_js( $return_html = false ) {
		$view_path = 'frontend/scripts/cb_frame/publisher-restrictions-js.php';

		$custom_tcf_purposes         = get_option( 'cookiebot-tcf-purposes' );
		$custom_tcf_special_purposes = get_option( 'cookiebot-tcf-special-purposes' );
		$custom_tcf_features         = get_option( 'cookiebot-tcf-features' );
		$custom_tcf_special_features = get_option( 'cookiebot-tcf-special-features' );
		$custom_tcf_vendors          = get_option( 'cookiebot-tcf-vendors' );
		$custom_tcf_ac_vendors       = get_option( 'cookiebot-tcf-ac-vendors' );

		$view_args = array(
			'allowed_purposes'          => self::get_custom_tcf_ids( $custom_tcf_purposes ),
			'allowed_special_purposes'  => self::get_custom_tcf_ids( $custom_tcf_special_purposes ),
			'allowed_features'          => self::get_custom_tcf_ids( $custom_tcf_features ),
			'allowed_special_features'  => self::get_custom_tcf_ids( $custom_tcf_special_features ),
			'allowed_vendors'           => self::get_custom_tcf_ids( $custom_tcf_vendors ),
			'allowed_google_ac_vendors' => self::get_custom_tcf_ids( $custom_tcf_ac_vendors ),
			'vendor_restrictions'       => self::get_custom_tcf_restrictions(),
		);
		if ( $return_html ) {
			return get_view_html( $view_path, $view_args );
		} else {
			include_view( $view_path, $view_args );
		}
		return '';
	}

	private function get_custom_tcf_ids( $option ) {
		if ( empty( $option ) ) {
			return '';
		}

		return implode( ', ', $option );
	}

	private function get_custom_tcf_restrictions() {
		if ( empty( get_option( 'cookiebot-tcf-disallowed' ) ) ) {
			return '';
		}

		$custom_tcf_restrictions = get_option( 'cookiebot-tcf-disallowed' );

		$attribute = array();

		foreach ( $custom_tcf_restrictions as $vendor => $restrictions ) {
			$purposes     = is_array( $restrictions ) && array_key_exists( 'purposes', $restrictions ) ? $restrictions['purposes'] : array();
			$attribute [] = '{"VendorId":' . $vendor . ',"DisallowPurposes":[' . implode( ', ', $purposes ) . ']}';
		}

		return implode( ',', $attribute );
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

	public static function get_tcf_attribute() {
		$attribute   = false;
		$iab_enabled = ! empty( get_option( 'cookiebot-iab' ) );
		$tcf_version = get_option( 'cookiebot-tcf-version' );

		if ( $iab_enabled ) {
			$attribute = $tcf_version;
		}

		return $attribute;
	}
}
