<?php

namespace cybot\cookiebot\addons\controller\addons\litespeed_cache;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class Litespeed_Cache extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Litespeed Cache';
	const DEFAULT_PLACEHOLDER_CONTENT = 'This is not used.';
	const OPTION_NAME                 = 'litespeed_cache';
	const PLUGIN_FILE_PATH            = 'litespeed-cache/litespeed-cache.php';
	const DEFAULT_COOKIE_TYPES        = array( 'necessary' );
	const ENABLE_ADDON_BY_DEFAULT     = true;
	const SVN_URL_BASE_PATH           = 'https://plugins.svn.wordpress.org/litespeed-cache/trunk/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'litespeed-cache.php';

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
	 * @param  array $excluded_files  Array of script URLs to be excluded
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
	 * @return array
	 */
	public function get_extra_information() {
		return array(
			__(
				'Excludes cookiebot javascript files when the Litespeed Cache deter option is enabled.',
				'cookiebot'
			),
		);
	}
}
