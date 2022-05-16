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

	public function menu() {
		add_submenu_page(
			'cookiebot',
			__( 'Debug info', 'cookiebot' ),
			__( 'Debug info', 'cookiebot' ),
			'manage_options',
			'cookiebot_debug',
			array( $this, 'display' )
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
		$consent_api_helper          = new Consent_API_Helper();

		if ( ! function_exists( 'get_plugins' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins        = get_plugins();
		$active_plugins = get_option( 'active_plugins' );

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
		$debug_output .= 'IAB: ' . ( get_option( 'cookiebot-iab' ) === '1' ? 'Enabled' : 'Not enabled' ) . "\n";
		$debug_output .= 'CCPA banner for visitors from California: ' . ( get_option( 'cookiebot-ccpa' ) === '1' ? 'Enabled' : 'Not enabled' ) . "\n";
		$debug_output .= 'CCPA domain group id: ' . get_option( 'cookiebot-ccpa-domain-group-id' ) . "\n";
		$debug_output .= 'Add async/defer to banner tag: ' . ( get_option( 'cookiebot-script-tag-uc-attribute' ) !== '' ? get_option( 'cookiebot-script-tag-uc-attribute' ) : 'None' ) . "\n";
		$debug_output .= 'Add async/defer to declaration tag: ' . ( get_option( 'cookiebot-script-tag-cd-attribute' ) !== '' ? get_option( 'cookiebot-script-tag-cd-attribute' ) : 'None' ) . "\n";
		$debug_output .= 'Auto update: ' . ( get_option( 'cookiebot-autoupdate' ) === '1' ? 'Enabled' : 'Not enabled' ) . "\n";
		$debug_output .= 'Hide Cookie Popup: ' . ( get_option( 'cookiebot-nooutput' ) === '1' ? 'Yes' : 'No' ) . "\n";
		$debug_output .= 'Disable Cookiebot in WP Admin: ' . ( get_option( 'cookiebot-nooutput-admin' ) === '1' ? 'Yes' : 'No' ) . "\n";
		$debug_output .= 'Enable Cookiebot on front end while logged in: ' . ( get_option( 'cookiebot-output-logged-in' ) === '1' ? 'Yes' : 'No' ) . "\n";
		$debug_output .= 'List of ignored javascript files: ' . $this->get_ignored_scripts() . "\n";
		$debug_output .= 'Banner tag: ' . $cookiebot_javascript_helper->include_cookiebot_js( true ) . "\n";
		$debug_output .= 'Declaration tag: ' . Cookiebot_Declaration_Shortcode::show_declaration() . "\n";

		if ( get_option( 'cookiebot-gtm' ) !== false ) {
			$debug_output .= 'GTM tag: ' . $cookiebot_javascript_helper->include_google_tag_manager_js( true ) . "\n";
		}

		if ( get_option( 'cookiebot-gcm' ) !== false ) {
			$debug_output .= 'GCM tag: ' . $cookiebot_javascript_helper->include_google_consent_mode_js( true ) . "\n";
		}

		if ( $consent_api_helper->is_wp_consent_api_active() ) {
			$debug_output .= "\n--- WP Consent Level API Mapping ---\n";
			$debug_output .= 'F = Functional, N = Necessary, P = Preferences, M = Marketing, S = Statistics, SA = Statistics Anonymous' . "\n";
			$m             = $consent_api_helper->get_wp_consent_api_mapping();
			foreach ( $m as $k => $v ) {
				$debug_output .= strtoupper( str_replace( ';', ', ', $k ) ) . '   =>   ';
				$debug_output .= 'F=1, ';
				$debug_output .= 'P=' . $v['preferences'] . ', ';
				$debug_output .= 'M=' . $v['marketing'] . ', ';
				$debug_output .= 'S=' . $v['statistics'] . ', ';
				$debug_output .= 'SA=' . $v['statistics-anonymous'] . "\n";
			}
		}

		try {
			$cookiebot_addons = new Cookiebot_Addons();
			/** @var Settings_Service_Interface $settings_service */
			$settings_service = $cookiebot_addons->container->get( 'Settings_Service_Interface' );
			$addons           = $settings_service->get_active_addons();
			$debug_output    .= "\n--- Activated Cookiebot Addons ---\n";
			/** @var Base_Cookiebot_Addon $addon */
			foreach ( $addons as $addon ) {
				$debug_output .= $addon::ADDON_NAME . ' (' . implode( ', ', $addon->get_cookie_types() ) . ")\n";
			}
		} catch ( Exception $exception ) {
			$debug_output .= PHP_EOL . '--- Cookiebot Addons could not be activated ---' . PHP_EOL;
			$debug_output .= $exception->getMessage() . PHP_EOL;
		}

		$debug_output .= "\n--- Activated Plugins ---\n";
		foreach ( $active_plugins as $p ) {
			if ( $p !== 'cookiebot/cookiebot.php' ) {
				$debug_output .= $plugins[ $p ]['Name'] . ' (Version: ' . $plugins[ $p ]['Version'] . ")\n";
			}
		}

		$debug_output .= "\n##### Debug Information END #####";

		return $debug_output;
	}
}
