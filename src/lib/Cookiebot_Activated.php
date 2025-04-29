<?php

namespace cybot\cookiebot\lib;

use cybot\cookiebot\addons\Cookiebot_Addons;
use cybot\cookiebot\admin_notices\Cookiebot_Recommendation_Notice;
use Exception;

class Cookiebot_Activated {


	/**
	 * @throws Exception
	 */
	public function run() {
		$this->delay_notice_recommandation_when_it_is_first_activation();

		$this->set_to_mode_auto_when_no_cookiebot_id_is_set();

		$this->set_addons_default_settings();

		// Set a transient to indicate plugin was just activated
		set_transient( 'cookiebot_just_activated', true, 30 );
	}

	public function __construct() {
		// Add script to track activation on next page load
		add_action(
			'admin_head',
			function() {
				if ( get_transient( 'cookiebot_just_activated' ) ) {
					delete_transient( 'cookiebot_just_activated' );
					?>
				<script>
					// Load Amplitude SDK
					(function() {
						const script = document.createElement('script');
						script.src = 'https://cdn.eu.amplitude.com/script/3573fa11b8c5b4bcf577ec4c8e9d5cb6.js';
						script.async = true;
						script.onload = function() {
							const amplitude = window.amplitude;
							amplitude.init('3573fa11b8c5b4bcf577ec4c8e9d5cb6', {
								serverZone: 'EU',
								fetchRemoteConfig: true,
								defaultTracking: false
							});

							// Track activation event
							amplitude.track('Plugin Activated', {
								source: 'Plugin Directory',
								wordpress_role: '<?php echo esc_js( current_user_can( 'manage_options' ) ? 'Admin' : 'Editor' ); ?>',
								plugin_version: '<?php echo esc_js( Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION ); ?>',
								wp_version: '<?php echo esc_js( get_bloginfo( 'version' ) ); ?>',
							});
						};
						document.head.appendChild(script);
					})();
				</script>
					<?php
				}
			}
		);
	}

	private function delay_notice_recommandation_when_it_is_first_activation() {
		// Delay display of recommendation notice in 3 days if not activated earlier
		if ( get_option( Cookiebot_Recommendation_Notice::COOKIEBOT_NOTICE_OPTION_KEY, false ) === false ) {
			// Not set yet - this must be first activation - delay in 3 days
			update_option( Cookiebot_Recommendation_Notice::COOKIEBOT_NOTICE_OPTION_KEY, strtotime( '+3 days' ) );
		}
	}

	private function set_to_mode_auto_when_no_cookiebot_id_is_set() {
		if ( Cookiebot_WP::get_cbid() === '' ) {
			if ( is_multisite() ) {
				update_site_option( 'cookiebot-cookie-blocking-mode', 'auto' );
				update_site_option( 'cookiebot-nooutput-admin', true );
			} else {
				update_option( 'cookiebot-cookie-blocking-mode', 'auto' );
				update_option( 'cookiebot-nooutput-admin', true );
			}
		}
	}

	/**
	 * @throws Exception
	 */
	private function set_addons_default_settings() {
		$cookiebot_addons = Cookiebot_Addons::instance();
		$cookiebot_addons->cookiebot_activated();
	}
}
