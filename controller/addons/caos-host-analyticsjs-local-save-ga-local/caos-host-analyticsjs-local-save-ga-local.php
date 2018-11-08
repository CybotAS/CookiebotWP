<?php

namespace cookiebot_addons\controller\addons\caos_host_analyticsjs_local_save_ga_local;

use cookiebot_addons\controller\addons\caos_host_analyticsjs_local\CAOS_Host_Analyticsjs_Local;

/**
 * This is the older version of the plugin: CAOS_Host_Analyticsjs_Local
 * Supports till the version: 2.0.0
 *
 * Class CAOS_Host_Analyticsjs_Local_Save_Ga_Local
 * @package cookiebot_addons\controller\addons\caos_host_analyticsjs_local_save_ga_local
 */
class CAOS_Host_Analyticsjs_Local_Save_Ga_Local extends CAOS_Host_Analyticsjs_Local {

	/**
	 * plugin file name
	 *
	 * @return string
	 *
	 * @since 2.1.3
	 */
	public function get_plugin_file() {
		return 'host-analyticsjs-local/save-ga-local.php';
	}

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
