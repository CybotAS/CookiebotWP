<?php

namespace cybot\cookiebot\settings\templates;

use InvalidArgumentException;
use function cybot\cookiebot\lib\include_view;

class Legacy_Settings {


	private $options = array(
		'cookiebot-ruleset-id',
		'cookiebot-language',
		'cookiebot-front-language',
		'cookiebot-nooutput',
		'cookiebot-nooutput-admin',
		'cookiebot-output-logged-in',
		'cookiebot-ignore-scripts',
		'cookiebot-autoupdate',
		'cookiebot-script-tag-uc-attribute',
		'cookiebot-script-tag-cd-attribute',
		'cookiebot-cookie-blocking-mode',
		'cookiebot-iab',
		'cookiebot-tcf-version',
		'cookiebot-tcf-purposes',
		'cookiebot-tcf-special-purposes',
		'cookiebot-tcf-features',
		'cookiebot-tcf-special-features',
		'cookiebot-tcf-vendors',
		'cookiebot-tcf-disallowed',
		'cookiebot-tcf-ac-vendors',
		'cookiebot-ccpa',
		'cookiebot-ccpa-domain-group-id',
		'cookiebot-gtm',
		'cookiebot-gtm-id',
		'cookiebot-gtm-cookies',
		'cookiebot-data-layer',
		'cookiebot-gcm',
		'cookiebot-gcm-first-run',
		'cookiebot-gcm-url-passthrough',
		'cookiebot-gcm-cookies',
		'cookiebot-multiple-config',
		'cookiebot-second-banner-regions',
		'cookiebot-second-banner-id',
		'cookiebot-multiple-banners',
	);

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		$args = $this->get_all_legacy_options();

		include_view( 'admin/common/templates/legacy.php', array( 'options' => $args ) );
	}

	private function get_all_legacy_options() {
		$options = array();
		foreach ( $this->options as $option ) {
			$option_value = get_option( $option );
			if ( ! empty( $option_value ) ) {
				$options[ $option ] = $option_value;
			}
		}
		return $options;
	}

	public static function get_legacy_option( $option, $value ) {
		if ( is_array( $value ) ) {
			if ( $option === 'cookiebot-tcf-disallowed' ) {
				return self::get_tcf_disallowed_legacy( $value );
			} elseif ( $option === 'cookiebot-multiple-banners' ) {
				return self::get_multiple_banners_legacy( $value );
			} else {
				$html = '';
				foreach ( $value as $key => $value ) {
					$html .= '<input type="hidden" value="' . $value . '" name="' . $option . '[' . $key . ']" />';
				}
				return $html;
			}
		} else {
			return '<input type="hidden" value="' . $value . '" name="' . $option . '" />';
		}
	}

	private static function get_tcf_disallowed_legacy( $value ) {
		$html = '';
		foreach ( $value as $vendor => $restrictions ) {
			foreach ( $restrictions['purposes'] as $key => $purpose ) {
				$html .= '<input type="hidden" value="' . $purpose . '" name="cookiebot-tcf-disallowed[' . $vendor . '][purposes][' . $key . ']" />';
			}
		}
		return $html;
	}

	private static function get_multiple_banners_legacy( $value ) {
		$html = '';
		foreach ( $value as $key => $settings ) {
			$html .= '<input type="hidden" value="' . $settings['group'] . '" name="cookiebot-multiple-banners[' . $key . '][group]" />';
			$html .= '<input type="hidden" value="' . $settings['region'] . '" name="cookiebot-multiple-banners[' . $key . '][region]" />';
		}
		return $html;
	}
}
