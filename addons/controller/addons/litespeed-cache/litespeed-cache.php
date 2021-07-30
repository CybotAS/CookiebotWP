<?php

namespace cookiebot_addons\controller\addons\litespeed_cache;

use cookiebot_addons\controller\addons\Base_Cookiebot_Addon;

class Litespeed_Cache extends Base_Cookiebot_Addon {

	const ADDON_NAME                  = 'Litespeed Cache';
	const DEFAULT_PLACEHOLDER_CONTENT = '';
	const OPTION_NAME                 = 'litespeed_cache';
	const PLUGIN_FILE_PATH            = 'litespeed-cache/litespeed-cache.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT     = true;

	/**
	 * Loads addon configuration
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		/**
		 * Exclude Cookiebot files from defer setting
		 */
		add_filter( 'litespeed_optimize_js_excludes', array( $this, 'exclude_files' ) );
	}

	/**
	 * Exclude scripts from Litespeed cache’s defer JS option.
	 *
	 * @param  array  $excluded_files  Array of script URLs to be excluded
	 *
	 * @return array                    Extended array script URLs to be excluded
	 *
	 * @author Caspar Hübinger
	 * @since 3.6.2
	 */
	public function exclude_files( $excluded_files = array() ) {
		$excluded_files[] = 'consent.cookiebot.com';

		return $excluded_files;
	}

	/**
	 * Adds extra information under the label
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_extra_information() {
		return '<p>' . esc_html__( 'Excludes cookiebot javascript files when the Litespeed Cache deter option is enabled.',
				'cookiebot-addons' ) . '</p>';;
	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return boolean
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url() {
		return 'http://plugins.svn.wordpress.org/litespeed-cache/trunk/litespeed-cache.php';
	}
}
