<?php

namespace cybot\cookiebot\addons\controller\addons\wp_rocket;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class Wp_Rocket extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME              = 'WP Rocket';
	const OPTION_NAME             = 'wp_rocket';
	const PLUGIN_FILE_PATH        = 'wp-rocket/wp-rocket.php';
	const DEFAULT_COOKIE_TYPES    = array( 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT = true;

	/**
	 * Loads addon configuration
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		/**
		 * Exclude Cookiebot files from defer setting
		 */
		add_filter( 'rocket_exclude_defer_js', array( $this, 'exclude_files' ) );
	}

	/**
	 * Exclude scripts from WP Rocket’s defer JS option.
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
				'Excludes cookiebot javascript files when the WP-Rocket deter option is enabled.',
				'cookiebot'
			),
		);
	}
}
