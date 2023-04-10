<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Addon;
use cybot\cookiebot\addons\Cookiebot_Addons;
use cybot\cookiebot\lib\Consent_API_Helper;
use cybot\cookiebot\lib\Cookiebot_Javascript_Helper;
use cybot\cookiebot\lib\Settings_Service_Interface;
use cybot\cookiebot\lib\Cookiebot_WP;
use cybot\cookiebot\shortcode\Cookiebot_Declaration_Shortcode;
use InvalidArgumentException;
use RuntimeException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;
use Exception;

class Debug_Page implements Settings_Page_Interface {

	const ADMIN_SLUG = 'cookiebot_debug';

	public function menu() {
		add_submenu_page(
			'cookiebot',
			__( 'Debug info', 'cookiebot' ),
			__( 'Debug info', 'cookiebot' ),
			'manage_options',
			self::ADMIN_SLUG,
			array( $this, 'display' ),
			25
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		wp_enqueue_script(
			'cookiebot-debug-page-js',
			asset_url( 'js/backend/debug-page.js' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			true
		);

		$style_sheets = array(
			array( 'cookiebot-debug-css', 'css/backend/debug_info.css' ),
		);

		foreach ( $style_sheets as $style ) {
			wp_enqueue_style(
				$style[0],
				asset_url( $style[1] ),
				null,
				Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
			);
		}

		$debug_output = $this->prepare_debug_data();

		include_view( 'admin/settings/debug-page.php', array( 'debug_output' => $debug_output ) );
	}

	private function get_ignored_scripts() {
		$ignored_scripts = get_option( 'cookiebot-ignore-scripts' );

		$ignored_scripts = array_map(
			function( $ignore_tag ) {
				return trim( $ignore_tag );
			},
			explode( PHP_EOL, $ignored_scripts )
		);

		$ignored_scripts = apply_filters( 'cybot_cookiebot_ignore_scripts', $ignored_scripts );

		return implode( ', ', $ignored_scripts );
	}

	/**
	 * @throws InvalidArgumentException
	 */
	private function prepare_debug_data() {
		global $wpdb;

		$cookiebot_javascript_helper = new Cookiebot_Javascript_Helper();

		$debug_output = '';
		// phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
		$debug_output .= '##### Debug Information for ' . get_site_url() . ' generated at ' . date( 'c' ) . " #####\n\n";
		$debug_output .= 'WordPress Version: ' . get_bloginfo( 'version' ) . "\n";
		$debug_output .= 'WordPress Language: ' . get_bloginfo( 'language' ) . "\n";
		$debug_output .= 'PHP Version: ' . phpversion() . "\n";
		$debug_output .= 'MySQL Version: ' . $wpdb->db_version() . "\n";
		$debug_output .= "\n--- Cookiebot Information ---\n";
		$debug_output .= 'Plugin Version: ' . Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION . "\n";
		$debug_output .= 'Cookiebot ID: ' . Cookiebot_WP::get_cbid() . "\n";
		$debug_output .= 'Blocking mode: ' . get_option( 'cookiebot-cookie-blocking-mode' ) . "\n";
		$debug_output .= 'Language: ' . get_option( 'cookiebot-language' ) . "\n";
		$debug_output .= 'IAB: ' . $this->print_option_enabled( 'cookiebot-iab' ) . "\n";
		$debug_output .= 'CCPA banner for visitors from California: ' . $this->print_option_enabled( 'cookiebot-ccpa' ) . "\n";
		$debug_output .= 'CCPA domain group id: ' . get_option( 'cookiebot-ccpa-domain-group-id' ) . "\n";
		$debug_output .= 'Add async/defer to banner tag: ' . $this->print_option_if_not_empty( 'cookiebot-script-tag-uc-attribute' ) . "\n";
		$debug_output .= 'Add async/defer to declaration tag: ' . $this->print_option_if_not_empty( 'cookiebot-script-tag-cd-attribute' ) . "\n";
		$debug_output .= 'Auto update: ' . $this->print_option_enabled( 'cookiebot-autoupdate' ) . "\n";
		$debug_output .= 'Hide Cookie Popup: ' . $this->print_option_active( 'cookiebot-nooutput' ) . "\n";
		$debug_output .= 'Disable Cookiebot in WP Admin: ' . $this->print_option_active( 'cookiebot-nooutput-admin' ) . "\n";
		$debug_output .= 'Enable Cookiebot on front end while logged in: ' . $this->print_option_active( 'cookiebot-output-logged-in' ) . "\n";
		$debug_output .= 'List of ignored javascript files: ' . $this->get_ignored_scripts() . "\n";
		$debug_output .= 'Banner tag: ' . $cookiebot_javascript_helper->include_cookiebot_js( true ) . "\n";
		$debug_output .= 'Declaration tag: ' . Cookiebot_Declaration_Shortcode::show_declaration() . "\n";

		if ( get_option( 'cookiebot-gtm' ) !== false ) {
			$debug_output .= 'GTM tag: ' . $cookiebot_javascript_helper->include_google_tag_manager_js( true ) . "\n";
		}

		if ( get_option( 'cookiebot-gcm' ) !== false ) {
			$debug_output .= 'GCM tag: ' . $cookiebot_javascript_helper->include_google_consent_mode_js( true ) . "\n";
		}

		$debug_output .= $this->print_wp_consent_level_api_mapping();
		$debug_output .= $this->print_activated_addons();
		$debug_output .= $this->print_activated_plugins();

		$debug_output .= "\n##### Debug Information END #####";

		return $debug_output;
	}

	/**
	 * Print the value of the option if it's not empty.
	 *
	 * @param string $option_name Name of the option to print.
	 *
	 * @return string
	 */
	private function print_option_if_not_empty( $option_name ) {
		$option_value = get_option( $option_name );
		return $option_value !== '' ? $option_value : 'None';
	}

	/**
	 * Print "Enabled" or "Not enabled" depending on the option value. Option value should be "1" or "0".
	 *
	 * @param string $option_name Name of the option to check.
	 *
	 * @return string
	 */
	private function print_option_enabled( $option_name ) {
		return $this->print_option_active( $option_name, 'Enabled', 'Not enabled' );
	}

	/**
	 * Print "Yes" or "No" depending on the option value. Option value should be "1" or "0". If <b>$active_text</b> or
	 * <b>$disabled_text</b> is set, it will be used instead of default values "Yes" or "No".
	 *
	 * @param string $option_name   Name of the option to check.
	 * @param string $active_text   (Optional) Text to print if option is active. Default is "Yes".
	 * @param string $disabled_text (Optional) Text to print if option is disabled. Default is "No".
	 *
	 * @return string
	 */
	private function print_option_active( $option_name, $active_text = 'Yes', $disabled_text = 'No' ) {
		return get_option( $option_name ) === '1' ? $active_text : $disabled_text;
	}

	/**
	 * Render debug information about WP Consent Level API mapping.
	 *
	 * @return string
	 */
	private function print_wp_consent_level_api_mapping() {
		$output = '';

		$consent_api_helper = new Consent_API_Helper();

		if ( $consent_api_helper->is_wp_consent_api_active() ) {
			$output .= "\n--- WP Consent Level API Mapping ---\n";
			$output .= 'F = Functional, N = Necessary, P = Preferences, M = Marketing, S = Statistics, SA = Statistics Anonymous' . "\n";
			$map     = $consent_api_helper->get_wp_consent_api_mapping();
			foreach ( $map as $key => $value ) {
				$output .= strtoupper( str_replace( ';', ', ', $key ) ) . '   =>   ';
				$output .= 'F=1, ';
				$output .= 'P=' . $value['preferences'] . ', ';
				$output .= 'M=' . $value['marketing'] . ', ';
				$output .= 'S=' . $value['statistics'] . ', ';
				$output .= 'SA=' . $value['statistics-anonymous'] . "\n";
			}
		}

		return $output;
	}

	/**
	 * Print information about activated cookiebot addons.
	 *
	 * @return string
	 */
	private function print_activated_addons() {
		$output = '';

		try {
			$cookiebot_addons = new Cookiebot_Addons();
			/** @var Settings_Service_Interface $settings_service */
			$settings_service = $cookiebot_addons->container->get( 'Settings_Service_Interface' );
			$addons           = $settings_service->get_active_addons();
			$output          .= "\n--- Activated Cookiebot Addons ---\n";
			/** @var Base_Cookiebot_Addon $addon */
			foreach ( $addons as $addon ) {
				$output .= $addon::ADDON_NAME . ' (' . implode( ', ', $addon->get_cookie_types() ) . ")\n";
			}
		} catch ( Exception $exception ) {
			$output .= PHP_EOL . '--- Cookiebot Addons could not be activated ---' . PHP_EOL;
			$output .= $exception->getMessage() . PHP_EOL;
		}

		return $output;
	}

	/**
	 * Print information about activated plugins
	 *
	 * @return string
	 */
	private function print_activated_plugins() {
		if ( ! function_exists( 'get_plugins' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins        = get_plugins();
		$active_plugins = get_option( 'active_plugins' );

		$output = "\n--- Activated Plugins ---\n";

		foreach ( $active_plugins as $plugin_key ) {
			if ( $plugin_key !== 'cookiebot/cookiebot.php' ) {
				$output .= $plugins[ $plugin_key ]['Name'] . ' (Version: ' . $plugins[ $plugin_key ]['Version'] . ")\n";
			}
		}

		return $output;
	}
}
