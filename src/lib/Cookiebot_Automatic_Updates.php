<?php
namespace cybot\cookiebot\lib;

class Cookiebot_Automatic_Updates {

	public function register_hooks() {
		if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			add_filter( 'auto_update_plugin', array( $this, 'automatic_updates' ), 10, 2 );
		}
	}

	/**
	 * Cookiebot_WP Automatic update plugin if activated
	 *
	 * @version 2.2.0
	 * @since       1.5.0
	 */
	public function automatic_updates( $update, $item ) {
		$item = (array) $item;
		if (
			// Do not update from subsite on a multisite installation
			( is_multisite() && ! is_main_site() ) ||
			// Check if we have everything we need
			( ! isset( $item['new_version'] ) || ! isset( $item['slug'] ) ) ||
			// It is not Cookiebot
			( $item['slug'] !== 'cookiebot' ) ||
			// Check if cookiebot autoupdate is disabled
			! get_option( 'cookiebot-autoupdate', false ) ||
			// Check if multisite autoupdate is disabled
			( is_multisite() && ! get_site_option( 'cookiebot-autoupdate', false ) )
		) {
			return $update;
		}

		return true;
	}
}
