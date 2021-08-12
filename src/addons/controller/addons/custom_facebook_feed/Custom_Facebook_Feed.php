<?php

namespace cybot\cookiebot\addons\controller\addons\custom_facebook_feed;

use cybot\cookiebot\addons\controller\addons\custom_facebook_feed_old\Custom_Facebook_Feed_Old;
use cybot\cookiebot\addons\lib\Open_Source_Addon_Interface;

class Custom_Facebook_Feed extends Custom_Facebook_Feed_Old implements Open_Source_Addon_Interface {

	/**
	 * Return addon/plugin name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_addon_name() {
		return 'Custom Facebook Feed';
	}

	/**
	 * Checks if addon is installed
	 *
	 * @since 1.3.0
	 */
	public function is_addon_installed() {
		$installed = $this->settings->is_addon_installed( $this->get_plugin_file() );

		if ( $installed && version_compare( $this->get_addon_version(), '2.17.1', '<=' ) ) {
			$installed = false;
		}

		return $installed;
	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url() {
		return 'http://plugins.svn.wordpress.org/custom-facebook-feed/trunk/custom-facebook-feed.php';
	}

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_addon_custom_facebook_feed() {

		if ( class_exists( '\CustomFacebookFeed\Custom_Facebook_Feed' ) ) {
			$instance = \CustomFacebookFeed\Custom_Facebook_Feed::instance();

			if ( has_action( 'wp_footer', array( $instance, 'cff_js' ) ) ) {
				/**
				 * Consent not given - no cache
				 */
				$this->buffer_output->add_tag( 'wp_footer', 10, array( 'cfflinkhashtags' => $this->get_cookie_types() ), false );
			}

			// External js, so manipulate attributes
			if ( has_action( 'wp_enqueue_scripts', array( $instance, 'enqueue_scripts_assets' ) ) ) {
				/**
				 * Consent not given - no cache
				 */
				$this->script_loader_tag->add_tag( 'cffscripts', $this->get_cookie_types(), false );
			}
		}
	}

}
