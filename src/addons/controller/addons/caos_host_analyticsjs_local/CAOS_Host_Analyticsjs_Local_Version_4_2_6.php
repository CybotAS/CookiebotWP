<?php

namespace cybot\cookiebot\addons\controller\addons\caos_host_analyticsjs_local;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class CAOS_Host_Analyticsjs_Local_Version_4_2_6 extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Complete Analytics Optimization Suite (CAOS) 4.2.6';
	const OPTION_NAME                 = 'caos_host_analyticsjs_local';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const PLUGIN_FILE_PATH            = 'host-analyticsjs-local/save-ga-local.php';
	const SVN_URL_BASE_PATH           = 'https://plugins.svn.wordpress.org/host-analyticsjs-local/tags/4.2.6/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'save-ga-local.php';

	public function load_addon_configuration() {

		/* Priority need to be more than 0 so we are able to hook in before output begins */
		$script_priority = $this->cookiebot_addon_host_analyticsjs_local_priority();
		if ( $script_priority <= 0 ) {
			// Force priority to 2
			$script_priority = 2;
			update_option( 'sgal_enqueue_order', $script_priority );
		}

		/**
		 * ga scripts are loaded in wp_footer priority is defined in option variable
		 */
		if (
			has_action( 'wp_footer', 'caos_analytics_render_tracking_code' ) ||
			has_action( 'wp_footer', 'caos_render_tracking_code' ) ||
			has_action( 'wp_footer', 'add_ga_header_script' ) ||
			( defined( 'CAOS_OPT_SCRIPT_POSITION' ) && CAOS_OPT_SCRIPT_POSITION === 'footer' )
		) {
			/**
			 * Consent not given - no cache
			 */
			$this->buffer_output->add_tag(
				'wp_footer',
				$script_priority,
				array(
					'GoogleAnalyticsObject' => $this->get_cookie_types(),
				),
				false
			);
		}

		/**
		 * ga scripts are loaded in wp_head priority is defined in option variable
		 */
		if (
			has_action( 'wp_head', 'caos_analytics_render_tracking_code' ) ||
			has_action( 'wp_head', 'caos_render_tracking_code' ) ||
			has_action( 'wp_head', 'add_ga_header_script' ) ||
			( defined( 'CAOS_OPT_SCRIPT_POSITION' ) && CAOS_OPT_SCRIPT_POSITION !== 'footer' )
		) {
			/**
			 * Consent not given - no cache
			 */
			$this->buffer_output->add_tag(
				'wp_head',
				$script_priority,
				array(
					'GoogleAnalyticsObject' => $this->get_cookie_types(),
				),
				false
			);
		}
	}

	/**
	 * Get priority of script
	 *
	 * @return integer
	 *
	 * @since 1.3.0
	 */
	public function cookiebot_addon_host_analyticsjs_local_priority() {
		return ( esc_attr( get_option( 'sgal_enqueue_order' ) ) ) ? esc_attr( get_option( 'sgal_enqueue_order' ) ) : 0;
	}
}
