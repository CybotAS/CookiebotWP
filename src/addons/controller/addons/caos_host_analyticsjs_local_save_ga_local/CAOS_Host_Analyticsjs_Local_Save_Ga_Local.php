<?php

namespace cybot\cookiebot\addons\controller\addons\caos_host_analyticsjs_local_save_ga_local;

use cybot\cookiebot\addons\controller\addons\caos_host_analyticsjs_local\CAOS_Host_Analyticsjs_Local;
use cybot\cookiebot\addons\lib\Open_Source_Addon_Interface;

/**
 * This is the older version of the plugin: CAOS_Host_Analyticsjs_Local
 * Supports till the version: 2.0.0
 *
 * Class CAOS_Host_Analyticsjs_Local_Save_Ga_Local
 * @package cybot\cookiebot\addons\controller\addons\caos_host_analyticsjs_local_save_ga_local
 */
class CAOS_Host_Analyticsjs_Local_Save_Ga_Local extends CAOS_Host_Analyticsjs_Local implements Open_Source_Addon_Interface {

	const PLUGIN_FILE_PATH        = 'host-analyticsjs-local/save-ga-local.php';
	const LATEST_PLUGIN_VERSION   = false;
	const PREVIOUS_PLUGIN_VERSION = CAOS_Host_Analyticsjs_Local::PLUGIN_FILE_PATH;


	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return string
	 *
	 * @since 2.1.3
	 */
	public function get_svn_url() {
		return 'http://plugins.svn.wordpress.org/host-analyticsjs-local/trunk/save-ga-local.php';
	}
}
