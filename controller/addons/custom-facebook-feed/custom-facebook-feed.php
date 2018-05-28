<?php
namespace cookiebot_addons_framework\controller\addons\custom_facebook_feed;

class Custom_Facebook_Feed {

	public function __construct() {
		/**
		 * We add the action after wp_loaded and replace the original Custom
		 * Facebook Feed action with our own adjusted version.
		 */
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_custom_facebook_feed' ), 5 );
	}

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_addon_custom_facebook_feed() {
		//Check if Custom Facebook Feed is loaded.
		if ( ! shortcode_exists( 'custom-facebook-feed' ) ) {
			return;
		}
		//Check if Cookiebot is activated and active.
		if ( ! function_exists( 'cookiebot_active' ) || ! cookiebot_active() ) {
			return;
		}

		//Remove cff_js action and replace it with our own
		if ( has_action( 'wp_footer', 'cff_js' ) ) {
			cookiebot_buffer_output( 'wp_footer', 10, array( 'cfflinkhashtags' ) );
		}

		// External js, so manipulate attributes
		if ( has_action( 'wp_enqueue_scripts', 'cff_scripts_method' ) ) {
			cookiebot_script_loader_tag( 'cffscripts', 'marketing');
		}
	}
}