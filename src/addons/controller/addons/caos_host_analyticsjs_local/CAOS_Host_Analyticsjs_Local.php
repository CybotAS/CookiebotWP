<?php

namespace cybot\cookiebot\addons\controller\addons\caos_host_analyticsjs_local;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class CAOS_Host_Analyticsjs_Local extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Complete Analytics Optimization Suite (CAOS)';
	const OPTION_NAME                 = 'caos_host_analyticsjs_local';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const PLUGIN_FILE_PATH            = 'host-analyticsjs-local/host-analyticsjs-local.php';
	const SVN_URL_BASE_PATH           = 'https://plugins.svn.wordpress.org/host-analyticsjs-local/trunk/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'host-analyticsjs-local.php';
	const ALTERNATIVE_ADDON_VERSIONS  = array(
		'4.2.6' => CAOS_Host_Analyticsjs_Local_Version_4_2_6::class,
		'1.97'  => CAOS_Host_Analyticsjs_Local_Version_1_97::class,
	);

	/**
	 * Check for Host Analyticsjs Local action hooks
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		$this->script_loader_tag->add_tag( 'caos-analytics', $this->get_cookie_types() );
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
